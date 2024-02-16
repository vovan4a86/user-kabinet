<?php

namespace App\Http\Controllers;

use App;
use App\User;
use Fanky\Admin\Models\City;
use Fanky\Admin\Models\Page;
use Fanky\Admin\Models\Product;
use Fanky\Admin\Models\SearchIndex;
use Fanky\Auth\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Request;
use SiteHelper;
use View;

class PageController extends Controller
{

    public function region_index($city_alias): Response
    {
        $this->city = City::current($city_alias);
        return $this->page();
    }

    public function region_page($alias)
    {
        return redirect(route('default', [$alias]), 301);
    }

    public function page($alias = null): Response
    {
        $path = explode('/', $alias);
        if (!$alias) {
            $current_city = SiteHelper::getCurrentCity();
            $this->city = $current_city && $current_city->id ? $current_city : null;
            $page = $this->city->generateIndexPage();
        } else {
            $page = Page::getByPath($path);
            if (!$page) {
                abort(404, 'Страница не найдена');
            }
        }

        $bread = $page->getBread();
        $page->h1 = $page->getH1();
        $view = $page->getView();
        $page->ogGenerate();
        $page->setSeo();

        Auth::init();
        if (Auth::user() && Auth::user()->isAdmin) {
            View::share('admin_edit_link', route('admin.pages.edit', [$page->id]));
        }

        return response()->view(
            $view,
            [
                'page' => $page,
                'h1' => $page->h1,
                'text' => $page->text,
                'title' => $page->title,
                'bread' => $bread,
            ]
        );
    }

    public function dashboard()
    {
//        \Illuminate\Support\Facades\Auth::guard('web')->logout();
        $user = \Illuminate\Support\Facades\Auth::user();
        return view('pages.dashboard', compact('user'));
    }

    public function policy()
    {
        $page = Page::whereAlias('policy')->first();
        if (!$page) {
            abort(404, 'Страница не найдена');
        }
        $bread = $page->getBread();
        $page->ogGenerate();
        $page->setSeo();

        return view(
            'pages.text',
            [
                'text' => $page->text,
                'h1' => $page->getH1(),
                'bread' => $bread,
            ]
        );
    }

    public function search()
    {
        \View::share('canonical', route('search'));
        $q = Request::get('q', '');

        if (!$q) {
            $items_ids = [];
        } else {
            $items_ids = SearchIndex::orWhere('name', 'LIKE', '%' . $q . '%')
                ->orderByDesc('updated_at')
                ->pluck('product_id')->all();
        }
        $items = Product::whereIn('id', $items_ids)
            ->paginate(10)
            ->appends(['q' => $q]); //Добавить параметры в строку запроса можно через метод appends().

        if (Request::ajax()) {
            $view_items = [];
            foreach ($items as $item) {
                $view_items[] = view(
                    'search.search_item',
                    [
                        'item' => $item,
                    ]
                )->render();
            }

            return response()->json(
                [
                    'items' => $view_items,
                    'paginate' => view(
                        'paginations.with_pages',
                        [
                            'paginator' => $items
                        ]
                    )->render()
                ]
            );
        }

        return view(
            'search.index',
            [
                'items' => $items,
                'title' => 'Результат поиска «' . $q . '»',
                'query' => $q,
                'name' => 'Поиск ' . $q,
                'keywords' => 'Поиск ',
                'description' => 'Поиск ',
                'headerIsWhite' => true,
            ]
        );
    }

    public function robots()
    {
        $robots = new App\Robots();
        if (App::isLocal()) {
            $robots->addUserAgent('*');
            $robots->addDisallow('/');
        } else {
            $robots->addUserAgent('*');
            $robots->addDisallow('/admin');
            $robots->addDisallow('/ajax');
        }

        $robots->addHost(config('app.url'));
        $robots->addSitemap(secure_url('sitemap.xml'));

        $response = response($robots->generate())
            ->header('Content-Type', 'text/plain; charset=UTF-8');
        $response->header('Content-Length', strlen($response->getOriginalContent()));

        return $response;
    }

    public function login(): array
    {
        $username = request()->get('name');
        $password = request()->get('password');

        if (Auth::login($username, $password)) {
            return ['success' => true, 'redirect' => route('main')];
        }

        return ['success' => true, 'errors' => 'Неправильный пароль или имя'];
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('main');
    }

    public function getRegistration()
    {
        return view('auth.registration');
    }

    public function postRegistration(): array
    {
        $data = request()->all();

        $messages = array(
            'name.required' => 'Не заполнено поле Имя',
            'email.required' => 'Не заполнено поле Email',
            'password.required' => 'Не заполнено поле Пароль',
        );

        $valid = Validator::make(
            $data,
            [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
            ],
            $messages
        );
        if ($valid->fails()) {
            return ['errors' => $valid->messages()];
        }

        $user = User::whereEmail($data['email'])->first();
        if ($user) {
            return ['errors' => 'Пользователь с таким Email уже существует!'];
        }

        $user = User::create(
            [
                'username' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'status' => 1,
                'role' => 0
            ]
        );

        Auth::login($user->username, $user->password, $user->remember_token);

        return ['success' => true, 'redirect' => route('main')];
    }
}
