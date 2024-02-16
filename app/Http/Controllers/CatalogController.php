<?php

namespace App\Http\Controllers;

use Doctrine\DBAL\Query\QueryBuilder;
use Fanky\Admin\Models\Catalog;
use Fanky\Admin\Models\Page;
use Fanky\Admin\Models\Product;
use Fanky\Admin\Settings;
use Fanky\Auth\Auth;
use SEOMeta;
use Request;
use View;

class CatalogController extends Controller
{
    public function index()
    {
        $page = Page::getByPath(['catalog']);
        if (!$page) {
            return abort(404);
        }

        $bread = $page->getBread();
        $page->h1 = $page->getH1();
        $page->setSeo();
        $page = $this->add_region_seo($page);

        $categories = Catalog::public()->whereParentId(0)->orderBy('order')->get();

        return view(
            'catalog.index',
            [
                'h1' => $page->h1,
                'text' => $page->text,
                'title' => $page->title,
                'bread' => $bread,
                'categories' => $categories,
            ]
        );
    }

    public function view($alias)
    {
        $path = explode('/', $alias);
        /* проверка на продукт в категории */
        $product = null;
        $end = array_pop($path);
        $category = Catalog::getByPath($path);
        if ($category && $category->published) {
            $product = Product::whereAlias($end)
                ->public()
                ->whereCatalogId($category->id)->first();
        }
        if ($product) {
            return $this->product($product);
        } else {
            array_push($path, $end);

            return $this->category($path + [$end]);
        }
    }

    public function category($path)
    {
        $category = Catalog::getByPath($path);
        if (!$category || !$category->published) {
            abort(404, 'Страница не найдена');
        }
        $bread = $category->getBread();
        $category->setSeo();
        $category->ogGenerate();
        $category->generateTitle();
        $category->generateDescription();
        $category = $this->add_region_seo($category);


        if (count(request()->query())) {
            SEOMeta::setCanonical($category->url);
            $canonical = $category->url;
        } else {
            $canonical = null;
        }

        Auth::init();
        if (Auth::user() && Auth::user()->isAdmin) {
            View::share('admin_edit_link', route('admin.catalog.catalogEdit', [$category->id]));
        }

        $view = $category->products()->count() ? 'catalog.sub_category' : 'catalog.category';

        $data = [
            'bread' => $bread,
            'category' => $category,
            'canonical' => $canonical,
            'h1' => $category->getH1(),
            'text' => $category->text,
            'children' => $category->children,
            'products' => $category->products
        ];

        return view($view, $data);
    }

    public function product(Product $product)
    {
        $bread = $product->getBread();
        $product->generateTitle();
        $product->generateDescription();
        if (!$product->text) {
            $product->generateText();
        }
        $product->setSeo();
        $product->ogGenerate();
        $product = $this->add_region_seo($product);

        $images = $product->images;

        Auth::init();
        if (Auth::user() && Auth::user()->isAdmin) {
            View::share('admin_edit_link', route('admin.catalog.productEdit', [$product->id]));
        }

        $related = Product::query()
            ->where('id', '!=', $product->id)
            ->limit(Settings::get('related_per_page', 5))
            ->inRandomOrder()
            ->get();

        return view(
            'catalog.product',
            [
                'product' => $product,
                'h1' => $product->getH1(),
                'bread' => $bread,
                'images' => $images,
                'name' => $product->name,
                'text' => $product->text,
                'related' => $related
            ]
        );
    }

    public function search()
    {
        $see = Request::get('see', 'all');
        $products_inst = Product::query();
        if ($s = Request::get('search')) {
            $products_inst->where(
                function ($query) use ($s) {
                    /** @var QueryBuilder $query */
                    //сначала ищем точное совпадение с началом названия товара
                    return $query->orWhere('name', 'LIKE', $s . '%');
                }
            );

            if (Request::ajax()) {
                //если нашлось больше 10 товаров, показываем их
                if ($products_inst->count() >= 10) {
                    $products = $products_inst->limit(10)->get()->transform(
                        function ($item) {
                            return [
                                'name' => $item->name . ' [' . $item->article . ']',
                                'url' => $item->url
                            ];
                        }
                    );
                } else {
                    //если меньше 10, разницу дополняем с совпадением по всему названию товара и артиклу
                    $count_before = $products_inst->count();
                    $sub = 10 - $count_before;
                    $adds_query = Product::query()
                        ->orWhere('name', 'LIKE', '%' . str_replace(' ', '%', $s) . '%')
                        ->orWhere('article', 'LIKE', '%' . str_replace(' ', '%', $s) . '%');
                    $adds_prod = $adds_query->limit($sub)->get();
                    $prods_before = $products_inst->limit($count_before)->get();
                    $all_prods = $prods_before->merge($adds_prod);
                    $products = $all_prods->transform(
                        function ($item) {
                            return [
                                'name' => $item->name . ' [' . $item->article . ']',
                                'url' => $item->url
                            ];
                        }
                    );
                }
                return ['data' => $products];
            }

            if ($see == 'all' || !is_numeric($see)) {
                $products = $products_inst->paginate(Settings::get('search_per_page'));
            } else {
                $products = $products_inst->paginate($see);
                $filter_query = Request::only(['see', 'price', 'in_stock']);
                $filter_query = array_filter($filter_query);
                $products->appends($filter_query);
            }
        } else {
            $products = collect();
        }


        return view(
            'search.index',
            [
                'items' => $products,
                'h1' => 'Результат поиска «' . $s . '»',
                'title' => 'Результат поиска «' . $s . '»',
                'query' => $see,
                'name' => 'Поиск ' . $s,
                'keywords' => 'Поиск',
                'description' => 'Поиск',
            ]
        );
    }

}
