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
Route::group(['prefix' => 'resource'], function (){
    Route::get('/upload', 'ResourceController@upload')->name('resource.upload');
    Route::get('/upload/new/category', 'ResourceController@uploadNewCategory')->name('resource.upload-new-category');
    Route::get('/create-thumbnails/{width}', 'ResourceController@createNewThumbnails')->name('resource.create-thumbnails');
    Route::get('/create-emoji-images-and-thumbs/{width}', 'ResourceController@createNewEmojiImageAndThumb')->name('resource.create-emoji-images-and-thumbs');
});
Route::group(['prefix' => 'pack'], function (){
    Route::get('braincraft/{cdoe}', 'StickerPackController@getPack');
    Route::get('/{cdoe}', 'StickerPackController@getPack');
});
Route::get('get-pack/{cdoe}', 'StickerPackController@redirectToAppStore');
