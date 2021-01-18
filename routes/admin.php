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
        Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard');

        Route::group(['prefix' => 'category'], function (){
            Route::get('list', 'Admin\CategoryController@index')->name('category.list');
            Route::get('list/sort2', 'Admin\CategoryController@orderCategoryBySort2Field')->name('category.sort2');
            Route::get('{id}', 'Admin\CategoryController@details')->name('category.details');
            Route::view('add', 'admin.category.form')->name('category.add');
            Route::get('{id}/edit', 'Admin\CategoryController@edit')->name('category.edit');
            Route::post('save', 'Admin\CategoryController@save')->name('category.save');
            Route::post('update-order', 'Admin\CategoryController@updateOrder')->name('category.update-order');
            Route::post('update-item-order', 'Admin\CategoryController@updateItemOrder')->name('category.update-item-order');
            Route::post('add-item-to-category', 'Admin\CategoryController@addItemToCategory')->name('category.add-item-to-category');
            Route::post('remove-item-from-category', 'Admin\CategoryController@removeItemFromCategory')->name('category.remove-item-from-category');
        });

        Route::group(['prefix' => 'item'], function (){
            Route::view('list', 'admin.item.list')->name('item.list');
            Route::post('list', 'Admin\ItemController@list')->name('item.get-list');
            Route::view('add', 'admin.item.form')->name('item.add');
            Route::get('edit/{id}', 'Admin\ItemController@editView')->name('item.edit');
            Route::post('save', 'Admin\ItemController@save')->name('item.save');
            Route::get('download-report', 'Admin\ItemController@downloadReport')->name('item.download-report');

            Route::group(['prefix' => 'search-keyword'], function (){
                Route::view('list', 'admin.search-keyword.list')->name('item.search-keyword.list');
                Route::post('list', 'Admin\SearchKeywordController@list')->name('item.search-keyword.get-list');
                Route::get('download-report', 'Admin\SearchKeywordController@downloadReport')->name('item.search-keyword.download-report');
            });
        });

        Route::group(['prefix' => 'telegram'], function (){
            Route::get('pack/{id}', 'Admin\TelegramController@telegramPack')->name('telegram.pack');
            Route::post('create-new-sticker-set', 'Admin\TelegramController@createNewStickerSet')->name('telegram.create-sticker-set');
        });

        Route::group(['prefix' => 'static-value'], function (){
            Route::get('list', 'Admin\StaticValueController@index')->name('static-value.list');
            Route::post('save', 'Admin\StaticValueController@save')->name('static-value.save');
        });

        Route::group(['prefix' => 'post'], function (){
            Route::view('{type}/list', 'admin.post.list', ['type' => 'how-to-use-sm'])->name('post.list');
            Route::post('{type}/list', 'Admin\PostController@list')->name('post.get-list');
            Route::get('{type}/add', 'Admin\PostController@addView')->name('post.add');
            Route::get('{type}/{id}/edit', 'Admin\PostController@editView')->name('post.edit');
            Route::post('{type}/save', 'Admin\PostController@save')->name('post.save');
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

        Route::resource('user', 'Admin\UserController', ['except' => ['show']]);
        Route::group(['prefix' => 'user'], function (){
            Route::view('list', 'admin.user.list')->name('user.list');
            Route::post('list', 'Admin\UserController@list')->name('user.get-list');
            Route::view('add', 'admin.user.form')->name('user.add');
            Route::get('profile', 'Admin\UserController@profile')->name('user.profile');
        });


    });


});
