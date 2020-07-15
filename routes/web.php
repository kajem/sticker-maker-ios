<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('cache-clear', 'WelcomeController@cacheClear');
Route::get('/', 'WelcomeController@index');
Route::get('get-sticker/items/{code}/{name}', 'WelcomeController@getSticker');
Route::group(['middleware' => ['auth'], 'prefix' => 'resource'], function (){
    Route::get('/upload', 'ResourceController@upload')->name('resource.upload');
    Route::get('/upload/new/category', 'ResourceController@uploadNewCategory')->name('resource.upload-new-category');
    Route::get('/create-thumbnails/{width}', 'ResourceController@createNewThumbnails')->name('resource.create-thumbnails');
    Route::get('/zip-pngquant', 'ResourceController@compressZipWithPngQuant');
    Route::get('/populate-item-to-categories-table-data', 'ResourceController@populateItemToCategoriesTableData');
});
Route::group(['prefix' => 'pack'], function (){
    Route::get('braincraft/{cdoe}', 'StickerPackController@getPack');
    Route::get('/{cdoe}', 'StickerPackController@getPack');
});
Route::get('get-pack/{cdoe}', 'StickerPackController@redirectToAppStore');

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('admin', function () {
    return view('admin.dashboard');
})->middleware('auth');

Route::group([ 'middleware' => ['auth'], 'prefix' => 'admin' ], function() {
    Route::get('static-value/list', 'Admin\StaticValueController@index');
    Route::post('static-value/save', 'Admin\StaticValueController@save');

    Route::get('report/search-keyword', 'Admin\ReportController@searchKeywordView');
    Route::post('report/search-keyword', 'Admin\ReportController@searchKeyword');
});