<?php

use Illuminate\Http\Request;
//use App\Http\Middleware\JsonApiMiddleware;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api'], function () {
    Route::group(['namespace' => 'Auth', 'prefix' => 'auths'], function () {
        Route::post('register', 'RegisterController');
        Route::post('login', 'LoginController');
        Route::post('logout', 'LogoutController')->middleware('auth:api');
    });
    Route::group(['namespace' => 'Playlist', 'prefix' => 'playlists'], function () {
        Route::get('/', 'GetController')->middleware('auth:api');
        Route::post('/', 'CreateController')->middleware('auth:api');
        Route::put('/{id}', 'UpdateController')->middleware('auth:api');
        Route::delete('/{id}', 'DeleteController')->middleware('auth:api');
    });
    Route::group(['namespace' => 'Song', 'prefix' => 'songs'], function () {
        Route::get('/', 'GetController')->middleware('auth:api');
        Route::post('/', 'CreateController')->middleware('auth:api');
        Route::put('/{id}', 'UpdateController')->middleware('auth:api');
    });
    Route::group(['namespace' => 'Author', 'prefix' => 'authors'], function () {
        Route::get('/', 'GetController')->middleware('auth:api');
        Route::post('/', 'CreateController')->middleware('auth:api');
    });
    Route::group(['namespace' => 'Genre', 'prefix' => 'genres'], function () {
        Route::get('/', 'GetController')->middleware('auth:api');
        Route::post('/', 'CreateController')->middleware('auth:api');
    });
    Route::group(['namespace' => 'Order', 'prefix' => 'orders'], function () {
        Route::get('/{id}', 'GetController')->middleware('auth:api');
        Route::put('/{id}', 'UpdateController')->middleware('auth:api');
        Route::delete('/{id}', 'DeleteController')->middleware('auth:api');
    });
    Route::group(['namespace' => 'Service', 'prefix' => 'services'], function () {
        Route::post('/cover', 'GetCoverController')->middleware('auth:api');
        Route::post('/genre', 'GetGenreController')->middleware('auth:api');
    });
});
