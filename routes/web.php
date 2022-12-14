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

$domain = config('app.url');
Route::domain($domain)->group(function() {
    Route::get('/', 'WelcomeController@index');
});
Route::get('get-sticker/items/{code}/{name}', 'WelcomeController@getSticker');
Route::post('contact/send-mail', 'ContactMailController@sendMail');
//Route::post('ajax', 'AjaxController@call');
Route::view('about-us', 'static-pages.about-us');
Route::view('privacy-policy', 'static-pages.privacy-policy');
Route::view('terms', 'static-pages.terms');
Route::view('sitemap', 'static-pages.site-map');
Route::group(['prefix' => 'how-to-use-sticker-maker'], function (){
    Route::get('/', 'PostController@list');
    Route::get('/{slug}', 'PostController@getPost');
});
Route::group(['prefix' => 'pack'], function (){
    Route::get('/{slug}', 'StickerPackController@getPackBySlug');
    Route::get('braincraft/{code}', 'StickerPackController@getPack');
    //Route::get('/{code}', 'StickerPackController@getPack');
});
Route::group(['prefix' => 'stickers'], function (){
    Route::get('/{slug}', 'StickerPackController@getPackBySlug');
});
Route::get('get-pack/{code}', 'StickerPackController@redirectToAppStore');

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');
Route::view('/page-not-found', '404');
