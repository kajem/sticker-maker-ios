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
    Route::redirect('/', '/login');

    Route::group([ 'middleware' => ['auth'] ], function() {
        Route::view('/dashboard', 'admin.dashboard');

        Route::get('category/list', 'Admin\CategoryController@index');
        Route::get('category/list/sort2', 'Admin\CategoryController@orderCategoryBySort2Field');
        Route::get('category/{id}', 'Admin\CategoryController@details');
        Route::post('category/update-order', 'Admin\CategoryController@updateOrder');
        Route::post('category/update-item-order', 'Admin\CategoryController@updateItemOrder');
        
        Route::get('static-value/list', 'Admin\StaticValueController@index');
        Route::post('static-value/save', 'Admin\StaticValueController@save');

        Route::view('report/search-keyword', 'admin.report.search-keyword');
        Route::post('report/search-keyword', 'Admin\ReportController@searchKeyword');

        Route::get('cache-clear', 'WelcomeController@cacheClear');
        
        Route::group(['prefix' => 'resource'], function (){
            Route::get('/upload', 'ResourceController@upload')->name('resource.upload');
            Route::get('/upload/new/category', 'ResourceController@uploadNewCategory')->name('resource.upload-new-category');
            Route::get('/create-thumbnails/{width}', 'ResourceController@createNewThumbnails')->name('resource.create-thumbnails');
            Route::get('/zip-pngquant', 'ResourceController@compressZipWithPngQuant');
            Route::get('/populate-item-to-categories-table-data', 'ResourceController@populateItemToCategoriesTableData');
        });
    });

    
});