<?php

namespace App\Http\Controllers;

use App\Classes\SiteHelper;
use Fanky\Admin\Models\Page;
use Fanky\Admin\Models\Review;
use Fanky\Admin\Settings;
use Fanky\Auth\Auth;
use Illuminate\Http\Response;

class WelcomeController extends Controller
{

    public function index(): Response
    {
        $page = Page::find(1);
        $page->ogGenerate();
        $page->setSeo();

//        dd(Auth::user());


        return response()->view(
            'pages.index',
            [
                'page' => $page,
                'text' => $page->text,
                'h1' => $page->getH1()
            ]
        );
    }
}
