<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'Api\AuthController@login')->name('login');;
    Route::post('register', 'Api\AuthController@register');
    Route::post('logout', 'Api\AuthController@logout');
    Route::post('refresh', 'Api\AuthController@refresh');
    Route::post('me', 'Api\AuthController@me');

});


Route::group([

    'middleware' => 'jwt.verify',
    'prefix' => 'category'

], function ($router) {

    Route::get('index', 'Api\CategoryController@index');

    Route::post('create', 'Api\CategoryController@create');

});


Route::group([

    'middleware' => 'jwt.verify',
    'prefix' => 'image'

], function ($router) {

    Route::get('index', 'Api\ImageController@index');

    Route::get('myImages', 'Api\ImageController@myImages');

    Route::post('upload', 'Api\ImageController@upload');

    Route::post('download', 'Api\ImageController@download');

    Route::post('search', 'Api\ImageController@search');

    Route::post('filterByCategory', 'Api\ImageController@filterByCategory');


});

Route::group([

    'middleware' => 'jwt.verify',
    'prefix' => 'tag'

], function ($router) {

    Route::get('index', 'Api\TagController@index');

    Route::post('listImageTags', 'Api\TagController@listImageTags');

    Route::post('create', 'Api\TagController@create');

});

Route::group([

    'middleware' => 'jwt.verify',
    'prefix' => 'user'

], function ($router) {


    Route::post('bookmark', 'Api\UserController@bookmark');

    Route::get('myBookmarks', 'Api\UserController@myBookmarks');

});
