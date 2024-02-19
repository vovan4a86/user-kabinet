<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\PageController;

Route::get('robots.txt', 'PageController@robots')->name('robots');

Route::group(
    ['prefix' => 'ajax', 'as' => 'ajax.'],
    function () {
        Route::post('add-to-cart', [AjaxController::class, 'postAddToCart'])->name('add-to-cart');
        Route::post('update-to-cart', [AjaxController::class, 'postUpdateToCart'])->name('update-to-cart');
        Route::post('remove-from-cart', [AjaxController::class, 'postRemoveFromCart'])->name('remove-from-cart');
        Route::post('purge-cart', [AjaxController::class, 'postPurgeCart'])->name('purge-cart');
        Route::post('edit-cart-product', [AjaxController::class, 'postEditCartProduct'])->name('edit-cart-product');

        Route::post('calc', 'AjaxController@postCalc')->name('calc');
        Route::post('callback', 'AjaxController@postCallback')->name('callback');
        Route::post('feedback', 'AjaxController@postFeedback')->name('feedback');

        Route::post('user/upload-image', 'AjaxController@postUploadUserImage')->name('uploadUserImage');
        Route::post('user/save-info/{id}', 'AjaxController@postUserSaveInfo')->name('userSaveInfo');
    }
);

Route::get('/dashboard', [PageController::class, 'dashboard'])
    ->middleware(['auth.site'])->name('dashboard');

require __DIR__.'/auth.php';

Route::group([], function () {
        Route::get('/', ['as' => 'main', 'uses' => 'WelcomeController@index']);
        Route::any('news', ['as' => 'news', 'uses' => 'NewsController@index']);
        Route::any('news/{alias}', ['as' => 'news.item', 'uses' => 'NewsController@item']);

        Route::any('reviews', ['as' => 'reviews', 'uses' => 'ReviewsController@index']);
        Route::any('reviews/{alias}', ['as' => 'reviews.item', 'uses' => 'ReviewsController@item']);

        Route::any('contacts', ['as' => 'contacts', 'uses' => 'ContactsController@index']);

        Route::any('search', ['as' => 'search', 'uses' => 'CatalogController@search']);

        Route::get('cart', ['as' => 'cart', 'uses' => 'CartController@getIndex']);

        Route::any('policy', ['as' => 'policy', 'uses' => 'PageController@policy']);

        Route::any('catalog', ['as' => 'catalog.index', 'uses' => 'CatalogController@index']);

        Route::any('catalog/{alias}', ['as' => 'catalog.view', 'uses' => 'CatalogController@view'])
            ->where('alias', '([A-Za-z0-9\-\/_]+)');

        Route::any('{alias}', ['as' => 'default', 'uses' => 'PageController@page'])
            ->where('alias', '([A-Za-z0-9\-\/_]+)');
    }
);
