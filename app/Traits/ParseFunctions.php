<?php namespace App\Traits;

use Carbon\Carbon;
use Fanky\Admin\Models\Catalog;
use Fanky\Admin\Models\CatalogTest;
use Fanky\Admin\Models\Filter;
use Fanky\Admin\Models\NewProduct;
use Fanky\Admin\Models\Param;
use Fanky\Admin\Models\Product;
use Fanky\Admin\Models\ProductImage;
use Fanky\Admin\Text;
use GuzzleHttp\Cookie\CookieJar;
use SVG\SVG;
use Symfony\Component\DomCrawler\Crawler;

trait ParseFunctions {

    public $userAgents = [
        "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36",
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36",
        "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36",
        "Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36",
        "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36",
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:106.0) Gecko/20100101 Firefox/106.0",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 13.0; rv:106.0) Gecko/20100101 Firefox/106.0",
        "Mozilla/5.0 (X11; Linux i686; rv:106.0) Gecko/20100101 Firefox/106.0",
        "Mozilla/5.0 (X11; Linux x86_64; rv:106.0) Gecko/20100101 Firefox/106.0",
        "Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:106.0) Gecko/20100101 Firefox/106.0",
        "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:106.0) Gecko/20100101 Firefox/106.0",
        "Mozilla/5.0 (X11; Fedora; Linux x86_64; rv:106.0) Gecko/20100101 Firefox/106.0",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 13_0) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.1 Safari/605.1.15",
        "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0)",
        "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)",
        "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0)",
        "Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0)",
        "Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)",
        "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)",
        "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)",
        "Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko",
        "Mozilla/5.0 (Windows NT 6.2; Trident/7.0; rv:11.0) like Gecko",
        "Mozilla/5.0 (Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko",
        "Mozilla/5.0 (Windows NT 10.0; Trident/7.0; rv:11.0) like Gecko",
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Edg/106.0.1370.52",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Edg/106.0.1370.52",
        "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Vivaldi/5.5.2805.38",
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Vivaldi/5.5.2805.38",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Vivaldi/5.5.2805.38",
        "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Vivaldi/5.5.2805.38",
        "Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Vivaldi/5.5.2805.38",
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 OPR/92.0.4561.21",
        "Mozilla/5.0 (Windows NT 10.0; WOW64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 OPR/92.0.4561.21",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 OPR/92.0.4561.21",
        "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 OPR/92.0.4561.21",
        "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 YaBrowser/22.9.1 Yowser/2.5 Safari/537.36",
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 YaBrowser/22.9.1 Yowser/2.5 Safari/537.36",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 YaBrowser/22.9.1 Yowser/2.5 Safari/537.36",
    ];

    //парсим категории
    public function parseCategory($categoryName, $categoryUrl, $parentId) {
        $this->info($categoryName . ' => ' . $categoryUrl);
        $catalog = $this->getCatalogByName($categoryName, $parentId);

        //['beget' => 'begetok', 'PHPSESSID' => '1db29869cfa1ece545d452dc2aa9cc80']
        $res = $this->client->get($categoryUrl);
        $html = $res->getBody()->getContents();
        $sectionCrawler = new Crawler($html); //section page from url

        var_dump($html);
    }

    //парсим товары
    public function parseListProducts($catalog, $categoryUrl, $subcatName) {
        $this->info('Parse products from: ' . $catalog->name);
        $res = $this->client->get($categoryUrl);
        $html = $res->getBody()->getContents();
        $crawler = new Crawler($html); //page from url

        $table = $crawler->filter('.inner_wrapper')->first(); //table of products
        $table->filter('.dark_link')
//        ->reduce(function (Crawler $nnode, $i) {
//            return ($i < 1); //по одному товару на странице
//        })
            ->each(function (Crawler $node, $n) use ($catalog) {
                $data = [];
                try {
                    $url = $this->baseUrl . trim($node->attr('href'));
                    $data['name'] = $this->getNameFromString($node->text());
                    $data['h1'] = $node->text();
                    $data['title'] = $node->text();
                    $data['alias'] = Text::translit($node->text());
                    $data['articul'] = $this->getArticulFromName($data['title']);

                    $this->info(++$n . ') ' . $data['name']);

                    $product = Product::whereParseUrl($url)->first();

                    if (!$product) {
                        $productPage = $this->client->get($url);
                        $productHtml = $productPage->getBody()->getContents();
                        $productCrawler = new Crawler($productHtml); //product page

                        $propsTable = $productCrawler->filter('table.props_list');
                        $propsTable->filter('tr')->each(function (Crawler $prop) use (&$data) {
                            $propName = trim($prop->filter('.char_name span')->first()->text());
                            $propValue = trim($prop->filter('.char_value span')->first()->text());
                            if ($propName != 'Код') {
                                $dbProp = $this->propsMap[$propName];
                                if ($propName == 'Применяемость') {
                                    $data[$dbProp] = $propValue;
                                } else {
                                    $data[$dbProp] = preg_replace("/[^,.0-9]/", '', $propValue);
                                }
                            }
                        });
                        $order = $catalog->products()->max('order') + 1;

                        NewProduct::create(array_merge([
                            'catalog_id' => $catalog->id,
                            'parse_url' => $url,
                            'published' => 1,
                            'order' => $order,
                        ], $data));
                    } else {
                        $product->update($data);
                        $product->save();
                    }
                } catch (\Exception $e) {
                    $this->warn('error: ' . $e->getMessage());
                    $this->warn('see line: ' . $e->getLine());
                }
                sleep(rand(0, 2));
            });

//        проход по страницам
        if ($nextUrl = $crawler->filter('a#navigation_1_next_page')) {
            $pages = $crawler->filter('.navigation-pages a')->last()->text();
            $currentPage = $crawler->filter('.navigation-pages .nav-current-page')->first()->text();

            $nextUrl = $this->baseUrl . $nextUrl->attr('href');
            $this->info('parse: ' . $nextUrl . ' (' . ++$currentPage . '/' . $pages . ')');
            $this->parseListProducts($catalog, $nextUrl, $subcatName);
        }
    }


    /**
     * @param string $str
     * @return bool
     */
    public function checkIsImageJpg(string $str): bool {
        $imgEnds = ['.jpg', 'jpeg', 'png'];
        foreach ($imgEnds as $ext) {
            if (str_ends_with($str, $ext)) {
                return true;
            }
        }
        return false;
    }

    public function downloadJpgFile($url, $uploadPath, $fileName): bool {
        $safeUrl = str_replace(' ', '%20', $url);
        $this->info('downloadImageFile: ' . $safeUrl);
        $file = file_get_contents($safeUrl);
        if (!is_dir(public_path($uploadPath))) {
            mkdir(public_path($uploadPath), 0777, true);
        }
        try {
            file_put_contents(public_path($uploadPath . $fileName), $file);
            return true;
        } catch (\Exception $e) {
            $this->warn('download jpg error: ' . $e->getMessage());
            return false;
        }
    }

    public function downloadSvgFile($url, $uploadPath, $fileName): bool {
        $safeUrl = str_replace(' ', '%20', $url);

        $image = SVG::fromFile($this->baseUrl . $safeUrl);
        if (!is_dir(public_path($uploadPath))) {
            mkdir(public_path($uploadPath), 0777, true);
        }
        try {
            file_put_contents(public_path($fileName), $image->toXMLString());
            return true;
        } catch (\Exception $e) {
            $this->warn('download svg error: ' . $e->getMessage());
            return false;
        }
    }

    public function parseProductWallFromString($str, $productSize, $rectangle = null) {
        if (!$productSize) return null;
        if (!$rectangle) {
            $sizePos = mb_stripos($str, $productSize); //находим место в строке с текущим размером
            $subStr = mb_substr($str, $sizePos + mb_strlen($productSize) + 1); //вырезаем подстроку в которой есть размер стенки
            $charX = null;
        } else {
            //для прямоугольника, напр: 'трубы нерж. электросварные ЭСВ прямоугольные 30x15x1.5 шлиф';
            $sizeTempPos = mb_stripos($str, $productSize); //находим size 30
            $tempSubStr = mb_substr($str, $sizeTempPos + mb_strlen($productSize)); //'x15x1.5 шлиф'
            $charX = $tempSubStr[0];
            $sizeTempPos = mb_strripos($tempSubStr, $tempSubStr[0]); // 3 символ = последняя x
            $subStr = mb_substr($tempSubStr, $sizeTempPos + 1); // '1.5 шлиф'
        }

        if (mb_stripos($subStr, ' ')) {
            // если есть пробел в подстроке, отбрасываем лишнее и берем первую часть
            $arr = explode(' ', $subStr);
            return $arr[0];
        } else {
            // если в подстроке нет пробелов, т.е. строка заканчивается размером стенки
            if ($charX) {
                $arr = array_reverse(explode($charX, $subStr));
                return $arr[0];
            } else {
                return $subStr;
            }
        }
    }

    public function getKFromScriptUrl($scriptUrl) {
        try {
            $scriptPage = $this->client->get($scriptUrl);
            $scriptHtml = $scriptPage->getBody()->getContents();
            $scriptCrawler = new Crawler($scriptHtml);
            $scriptText = $scriptCrawler->filter('script[language="Javascript"]')->first()->text();
            $findStart = stripos($scriptText, 'var k=');
            $findEnd = stripos($scriptText, ';', $findStart);
            return substr($scriptText, $findStart + 6, $findEnd - $findStart - 6);
        } catch (\Exception $e) {
            $this->warn('/extract inner script problem/ => ' . $e->getMessage());
        }
    }

    /**
     * @param string $categoryName
     * @param int $parentId
     * @return Catalog
     */
    private function getCatalogByName(string $categoryName, int $parentId): Catalog {
        $catalog = Catalog::whereName($categoryName)->first();
        if (!$catalog) {
            $catalog = Catalog::create([
                'name' => $categoryName,
                'title' => $categoryName,
                'h1' => $categoryName,
                'parent_id' => $parentId,
                'alias' => Text::translit($categoryName),
                'slug' => Text::translit($categoryName),
                'order' => Catalog::whereParentId($parentId)->max('order') + 1,
                'published' => 1,
            ]);
            $this->info('+++ ' . ' Новый раздел: ' . $categoryName);
        }
        return $catalog;
    }

    private function getCatalogTestByName(string $categoryName, int $parentId, string $catFilters = null): CatalogTest {
        $catalog = CatalogTest::whereName($categoryName)->first();
        if (!$catalog) {
            $catalog = CatalogTest::create([
                'name' => $categoryName,
                'title' => $categoryName,
                'h1' => $categoryName,
                'parent_id' => $parentId,
                'filters' => $catFilters,
                'alias' => Text::translit($categoryName),
                'slug' => Text::translit($categoryName),
                'order' => CatalogTest::whereParentId($parentId)->max('order') + 1,
                'published' => 1,
            ]);
        } else {
            $catalog->filters = $catFilters;
            $catalog->save();
        }
        return $catalog;
    }

    private function updateCatalogUpdatedAt(Catalog $catalog) {
        $catalog->updated_at = Carbon::now();
        $catalog->save();
        if ($catalog->parent_id !== 0) {
            $cat = Catalog::find($catalog->parent_id);
            $this->updateCatalogUpdatedAt($cat);
        }
    }

    public function getInnerSiteScript($node): string {
        $idt = $node->attr('idt');
        $idf = $node->attr('idf');
        $idb = $node->attr('idb');
        //mc.ru//pages/blocks/add_basket.asp/id/XY12/idf/5/idb/1
        return 'mc.ru//pages/blocks/add_basket.asp/id/' . $idt . '/idf/' . $idf . '/idb/' . $idb;
    }

    public function getArticulFromName(string $name): string {
        $start = stripos($name, '[');
        $end = stripos($name, ']');
        if ($start && $end) {
            return substr($name, $start + 1, $end - $start - 1);
        } else {
            return $name;
        }

    }

    public function getNameFromString(string $name): string {
        $mark = stripos($name, '[');
        if ($mark) {
            return trim(substr($name, 0, $mark));
        } else {
            return $name;
        }

    }

    public function getExtensionFromSrc(string $url): string {
        $mark = strripos($url, '.');
        if ($mark) {
            return trim(substr($url, $mark));
        } else {
            return '.none';
        }

    }

    public function getTextWithNewImage(string $text, string $imgUrl): string {
        if ($text == null) return '';
        $start = stripos($text, '<img');
        if (!$start) return $text;

        $end = stripos($text, '>', $start);
        $searchString = substr($text, $start, $end - $start + 1);
        $img = '<img src="' . $imgUrl . '">';
        return str_replace($searchString, $img, $text);
    }

    public function getUpdatedTextWithNewImages(string $text, array $imgSrc, array $imgArr): string {
        if ($text == null) return '';
        if (count($imgArr) == 0) return $text;
        $res = str_replace($imgSrc, $imgArr, $text);

        return $res;
    }

    //чтобы найти название файла на русском для последующей замены
    public function encodeUrlFileName($url) {
        $start = strripos($url, '/') + 1;
        $end = strripos($url, '.');
        if ($start && $end) {
            $searchName = substr($url, $start, $end - $start);
            $encodeName = urlencode($searchName);
            $encodeName = str_replace('25', '', $encodeName);
            return str_replace($searchName, $encodeName, $url);
        }
    }

    public function curlGetData(string $url): string {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: beget=begetok")); //only for https://rus-kab.ru
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function curlSaveDataToFile(string $url, string $fileName) {
        $uploadPath = '/data-site/';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: beget=begetok")); //only for https://rus-kab.ru
        $response = curl_exec($ch);
        if (!is_dir(public_path($uploadPath))) {
            mkdir(public_path($uploadPath), 0777, true);
        }

        file_put_contents(public_path($uploadPath . $fileName), $response);
    }

    //used only for https://rus-kab.ru
    public function extractCableProductName(string $name) {
        if (!stripos($name, ' ')) return $name;

        $result = explode(' ', $name);
        return $result[0];
    }

    public function addProductParamName($name) {
        $param = Param::whereName($name)->first();
        if (!$param) {
            $param = Param::create([
                'name' => $name,
            ]);
        }
        return $param->id;
    }

    //заменить запятую в цене на точку, иначе не сохр. во FLOAT
    public function replaceFloatValue(string $str) {
        if (stripos($str, ',')) {
            return str_replace(',', '.', $str);
        }
        return $str;
    }
}
