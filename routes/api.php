<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('posts', 'PostController@index');

//Route::group([
//    'prefix' => 'auth'
//], function () {
//    Route::post('login', 'AuthController@login');
//    Route::post('signup', 'AuthController@signup');
//
    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
        Route::post('post', 'PostController@store');
        Route::patch('user/{user}/update/', 'UserController@update');
        Route::post('user/{user}/update-role', 'UserController@updateRole');
        Route::post('user/{user}/update-permission', 'UserController@updatePermission');

        //Movies
        Route::get('movie/search-omdb', 'MovieController@searchInOmdb');
        Route::apiResource('movie', 'MovieController');
        Route::apiResource('director', 'DirectorController');

        //Comments
        Route::post('comment/{comment}/like', 'CommentController@likeOrDislike');
        Route::get('comment/{comment}/like-count', 'CommentController@likesCount');
        Route::apiResource('comment', 'CommentController');
    });
//});
Auth::routes(['verify' => true]);
