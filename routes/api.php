<?php

use App\Http\Controllers\ActorController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DirectorController;
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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Route::post('oauth/token', 'Laravel\Passport\Http\Controllers\AccessTokenController@issueToken')->middleware('');
//Homepage
Route::get('post/list/homepage', [PostController::class, 'listHomepage']);
Route::get('post/hot/category', [PostController::class, 'listHotCategory']);
Route::get('post/category/{category_name}', [PostController::class, 'listForCategory']);

//Posts
Route::apiResource('post', PostController::class)->only('index', 'show');
Route::get('post/{post}/comments', [CommentController::class ,'commentsForPost']);
Route::get('post/{post}/similar', [PostController::class, 'listSimilar']);

//Movies
Route::get('movie/search-omdb', [MovieController::class, 'searchInOmdb']);
Route::get('movies/search', [MovieController::class, 'search'])->name('movies.search');
Route::get('movies/omdb', [MovieController::class, 'getFromOmdb'])->name('movies.get-from-omdb');
Route::post('movies/omdb', [MovieController::class, 'storeFromOmdb']);
Route::apiResource('movies', MovieController::class)->middleware('cors');
Route::get('movies/{movie}/comments', [CommentController::class ,'commentsForMovie']);
Route::get('movies/{movie}/rating', [MovieController::class, 'calculateRating']);

//Actors
Route::apiResource('actor', ActorController::class);
//Route::post('actor/{actor}/rate', 'ActorController@rate');
//Route::get('actor/{actor}/movie/{movie}/rating', 'ActorController@calculateForMovieRating');
Route::post('actor/{actor}/movie/{movie}/rate', [ActorController::class, 'rateForMovie']);


Route::apiResource('director', DirectorController::class);




//Route::group([
//    'prefix' => 'auth'
//], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'signup']);
//


//    Route::group([
//        'middleware' => 'auth:api',
//    ], function() {
//        Route::get('logout', 'AuthController@logout');
//        Route::get('user', 'AuthController@user');
//        Route::patch('user/{user}/update/', 'UserController@update');
//        Route::post('user/{user}/update-role', 'UserController@updateRole');
//        Route::post('user/{user}/update-permission', 'UserController@updatePermission');
//
//        //Posts
//        Route::apiResource('post', 'PostController')->only('store')->middleware('can:create,App\Models\Post');
//        Route::apiResource('post', 'PostController')->only('delete')->middleware('can:manage,post');
//        Route::put('post/{post}', 'PostController@update')->middleware('can:manage,post');
//
//        //Movies
////        Route::get('movie/search-omdb', 'MovieController@searchInOmdb');
//        Route::post('movie/{movie}/rate', 'MovieController@rate');
//        Route::get('movie/{movie}/rating', 'MovieController@calculateRating');
////        Route::apiResource('movie', 'MovieController');
//        Route::get('movie/{movie}/comments', 'CommentController@commentsForMovie');
//
//        //Actors
////        Route::post('actor/{actor}/rate', 'ActorController@rate');
////        Route::get('actor/{actor}/movie/{movie}/rating', 'ActorController@calculateForMovieRating');
////        Route::post('actor/{actor}/movie/{movie}/rate', 'ActorController@rateForMovie');
////        Route::apiResource('actor', 'ActorController');
//
//        //Directors
////        Route::apiResource('director', DirectorController::class);
//
//        //Comments
//        Route::post('comment/{comment}/like', 'CommentController@likeOrDislike');
//        Route::get('comment/{comment}/like-count', 'CommentController@likesCount');
//        Route::apiResource('comment', 'CommentController')->only('index', 'show');
//        Route::apiResource('comment', 'CommentController')->only('store');
//        Route::apiResource('comment', 'CommentController')->only('update', 'delete')->middleware('can:manage,comment');
//
//        //Genres
//        Route::apiResource('genre', 'GenreController')->only('index', 'show');
//        Route::apiResource('genre', 'GenreController')->only('store', 'update', 'delete')->middleware('can:manage,App/Model/Genre');
//
//        //Tags
//        Route::apiResource('tag', 'TagController')->only('index', 'show');
//        Route::apiResource('tag', 'TagController')->only('store', 'update', 'delete')->middleware('can:manage,App/Model/Tag');
//
//    });
//});
//Auth::routes(['verify' => true]);
