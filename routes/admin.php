<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "auth" middleware group. Now create something great!
|
*/

$domain = '{admin}.' . config('app.url');
Route::domain($domain)->group(function() {
    Route::get('/', function (){
        if(Auth::guest())
            return redirect('/login');
        else
            return redirect('/dashboard');
    });

    Route::group([ 'middleware' => ['auth'] ], function() {
        Route::get('/dashboard', 'Admin\DashboardController@index');

        Route::group(['prefix' => 'category'], function (){
            Route::get('list', 'Admin\CategoryController@index');
            Route::get('list/sort2', 'Admin\CategoryController@orderCategoryBySort2Field');
            Route::get('{id}', 'Admin\CategoryController@details');
            Route::view('add', 'admin.category.form');
            Route::get('{id}/edit', 'Admin\CategoryController@edit');
            Route::post('save', 'Admin\CategoryController@save');
            Route::post('update-order', 'Admin\CategoryController@updateOrder');
            Route::post('update-item-order', 'Admin\CategoryController@updateItemOrder');
            Route::post('add-item-to-category', 'Admin\CategoryController@addItemToCategory');
            Route::post('remove-item-from-category', 'Admin\CategoryController@removeItemFromCategory');
        });

        Route::group(['prefix' => 'item'], function (){
            Route::view('list', 'admin.item.list');
            Route::post('list', 'Admin\ItemController@list');
            Route::view('add', 'admin.item.form');
            Route::get('edit/{id}', 'Admin\ItemController@editView');
            Route::post('save', 'Admin\ItemController@save');
        });

        Route::group(['prefix' => 'telegram'], function (){
            Route::get('pack/{id}', 'Admin\TelegramController@telegramPack');
            Route::post('create-new-sticker-set', 'Admin\TelegramController@createNewStickerSet');
        });

        Route::group(['prefix' => 'static-value'], function (){
            Route::get('list', 'Admin\StaticValueController@index');
            Route::post('save', 'Admin\StaticValueController@save');
        });

        Route::group(['prefix' => 'post'], function (){
            Route::view('{type}/list', 'admin.post.list', ['type' => 'how-to-use-sm']);
            Route::post('{type}/list', 'Admin\PostController@list');
            Route::get('{type}/add', 'Admin\PostController@addView');
            Route::get('{type}/{id}/edit', 'Admin\PostController@editView');
            Route::post('{type}/save', 'Admin\PostController@save');
        });

        Route::group(['prefix' => 'search-keyword'], function (){
            Route::view('list', 'admin.search-keyword.list');
            Route::post('list', 'Admin\SearchKeywordController@list');
        });

        Route::get('cache-clear', 'WelcomeController@cacheClear');

        Route::group(['prefix' => 'resource'], function (){
            Route::get('upload', 'ResourceController@upload')->name('resource.upload');
            Route::get('upload/new/category', 'ResourceController@uploadNewCategory')->name('resource.upload-new-category');
            Route::get('create-thumbnails/{width}', 'ResourceController@createNewThumbnails')->name('resource.create-thumbnails');
            Route::get('zip-pngquant', 'ResourceController@compressZipWithPngQuant');
            Route::get('populate-item-to-categories-table-data', 'ResourceController@populateItemToCategoriesTableData');
            Route::get('create-emoji-resources', 'ResourceController@createEmojiZipFiles');
        });

    });


});
