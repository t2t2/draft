<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
/*
 * HEY! Before editing this!
 *
 * If you were planning to add a route that Brian missaid on air, DON'T.
 * This file would grow to 3 MB.
 */

// Home
Route::get('/', ['uses' => 'HomeController@home', 'as' => 'home']);

// Authentication
Route::group(['prefix' => 'auth'], function () {
	// Login, logout
	Route::get('login', ['uses' => 'AuthController@loginPage', 'as' => 'auth.login.page']);
	Route::post('login', ['uses' => 'AuthController@login', 'as' => 'auth.login']);
	Route::post('logout', ['uses' => 'AuthController@logout', 'as' => 'auth.logout']);

	// Registration
	Route::get('register', ['uses' => 'AuthController@registerForm', 'as' => 'auth.register.form']);
	Route::post('register', ['uses' => 'AuthController@register', 'as' => 'auth.register']);
});

// User profile
Route::get('user/{user}', ['uses' => 'UserController@show', 'as' => 'user.show']);

// Leagues page
Route::group(['prefix' => 'leagues'], function () {
	Route::get('/', ['uses' => 'LeagueController@index', 'as' => 'league.index']);
	Route::get('/create', ['uses' => 'LeagueController@create', 'as' => 'league.create', 'before' => 'auth']);
	Route::post('/', ['uses' => 'LeagueController@store', 'as' => 'league.store', 'before' => 'auth']);

	Route::get('/mine', ['uses' => 'LeagueController@mine', 'as' => 'league.mine', 'before' => 'auth']);

});


Route::get('league/{league}', ['uses' => 'LeagueController@show', 'as' => 'league.show']);


// Admin
Route::group(['prefix' => 'admin', 'before' => ['auth', 'admin'], 'namespace' => 'Admin'], function () {
	Route::get('/', ['uses' => 'HomeController@index', 'as' => 'admin.index']);
});

/**
 * Global route filters
 */
Route::when('*', 'csrf', ['post']);