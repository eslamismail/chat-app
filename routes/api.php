<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Broadcast::routes(['middleware' => ['auth:api']]);

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

Route::post('/signup', 'AuthController@signup');
Route::post('login', 'AuthController@login');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('logout', 'AuthController@logout')->middleware('auth:api');
Route::middleware(['auth:api'])->group(function () {
    Route::get('/posts/all', 'PostController@index');
    Route::get('/posts', 'PostController@myPosts');
    Route::post('/posts', 'PostController@store');
    Route::post('/posts/like/{id}', 'PostController@like');
    Route::post('/posts/{id}/comments', 'CommentController@store');
    Route::get('/user/{id}', 'UserController@show');
});
