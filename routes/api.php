<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'item'], function (){
    Route::get('/get-categories', 'Api\ItemController@getCategories');
    Route::get('/get-category-and-items', 'Api\ItemController@getCategoryAndItems')->name('item.get-category-and-items');
    Route::get('/category/{id}', 'Api\ItemController@getItemsByCategoryId')->name('item.category.id');
    Route::get('/category/emoji', 'Api\ItemController@getEmojiItems');
    Route::get('/special-category/{type}', 'Api\ItemController@getSpecialCategoryItems');
    Route::get('/{code}/stickers', 'Api\ItemController@getStickersByItemId')->name('item.id.stickers');
    Route::get('{code}/image/{file_name}', 'Api\ItemController@getImage')->name('item.image');
    Route::get('search', 'Api\ItemController@search');
    Route::post('save-search-keyword', 'Api\ItemController@saveSearchKeyword');
    Route::get('/{code}', 'Api\ItemController@getPack');
    Route::get('update-view-count/{code}', 'Api\ItemController@updateItemViewCount');
    Route::get('update-download-count/{code}', 'Api\ItemController@updateItemDownloadCount');
    Route::get('update-view-count/{code}/{platform}', 'Api\ItemController@updateItemViewCount');
    Route::get('update-download-count/{code}/{platform}', 'Api\ItemController@updateItemDownloadCount');
});

Route::group(['prefix' => 'stickerpack'], function (){
    Route::post('/create', 'Api\StickerPackController@create');
    Route::post('/edit', 'Api\StickerPackController@edit');
    Route::get('/get/{code}', 'Api\StickerPackController@getPack');
});

