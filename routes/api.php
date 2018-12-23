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
        Route::patch('user/{user}/update/', 'UserController@update');
        Route::post('user/{user}/update-role', 'UserController@updateRole');
        Route::post('user/{user}/update-permission', 'UserController@updatePermission');

        //Posts
        Route::apiResource('post', 'PostController')->only('index', 'show');
        Route::apiResource('post', 'PostController')->only('store')->middleware('can:create,App\Models\Post');
        Route::apiResource('post', 'PostController')->only('delete')->middleware('can:manage,post');
        Route::put('post/{post}', 'PostController@update')->middleware('can:manage,post');
        Route::get('post/category/hot', 'PostController@listHotCategory');

        //Movies
        Route::get('movie/search-omdb', 'MovieController@searchInOmdb');
        Route::post('movie/{movie}/rate', 'MovieController@rate');
        Route::get('movie/{movie}/rating', 'MovieController@calculateRating');
        Route::apiResource('movie', 'MovieController');

        //Actors
        Route::post('actor/{actor}/rate', 'ActorController@rate');
        Route::get('actor/{actor}/movie/{movie}/rating', 'ActorController@calculateForMovieRating');
        Route::post('actor/{actor}/movie/{movie}/rate', 'ActorController@rateForMovie');
        Route::apiResource('actor', 'ActorController');

        //Directors
        Route::apiResource('director', 'DirectorController');

        //Comments
        Route::post('comment/{comment}/like', 'CommentController@likeOrDislike');
        Route::get('comment/{comment}/like-count', 'CommentController@likesCount');
        Route::apiResource('comment', 'CommentController')->only('index', 'show');
        Route::apiResource('comment', 'CommentController')->only('store');
        Route::apiResource('comment', 'CommentController')->only('update', 'delete')->middleware('can:manage,comment');

        //Genres
        Route::apiResource('genre', 'GenreController')->only('index', 'show');
        Route::apiResource('genre', 'GenreController')->only('store', 'update', 'delete')->middleware('can:manage,App/Model/Genre');

        //Tags
        Route::apiResource('tag', 'TagController')->only('index', 'show');
        Route::apiResource('tag', 'TagController')->only('store', 'update', 'delete')->middleware('can:manage,App/Model/Tag');

    });
//});
Auth::routes(['verify' => true]);
