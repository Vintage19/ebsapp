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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function() {
    // My articles
    Route::get('myarticles', 'ArticleController@get_my_articles');
    // Add article
    Route::post('addarticle', 'ArticleController@store');
    // Edit article
    Route::put('editarticle/{article}', 'ArticleController@update');
    // Delete article
    Route::delete('deletearticle/{article}', 'ArticleController@delete');

    
    // Vote article
    Route::post('vote', 'ArticleController@vote');
});

// Get article
Route::get('articles/{id}', 'ArticleController@show');
// All articles
Route::get('articles', 'ArticleController@index');

// Top categories
Route::get('topcategories', 'CategoriesController@index');

Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

