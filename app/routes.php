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
	Route::get('login', ['uses' => 'AuthController@loginPage', 'as' => 'auth.login.page', 'before' => 'guest']);
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

Route::group(['prefix' => 'league/{league}'], function () {
	Route::get('/', ['uses' => 'LeagueController@show', 'as' => 'league.show']);

	Route::group(['prefix' => 'admin', 'before' => ['auth', 'league.admin']], function () {

		Route::get('settings', ['uses' => 'LeagueAdminController@settings', 'as' => 'league.admin.settings']);
		Route::post('settings', ['uses' => 'LeagueAdminController@storeSettings', 'as' => 'league.admin.settings.store']);

		Route::get('movies', ['uses' => 'LeagueAdminController@movies', 'as' => 'league.admin.movies']);

		Route::get('movies/add', ['uses' => 'LeagueAdminController@addableMovies', 'as' => 'league.admin.movies.add']);
		Route::post('movies/add', ['uses' => 'LeagueAdminController@addMovies', 'as' => 'league.admin.movies.store']);


		Route::post('movies/remove', ['uses' => 'LeagueAdminController@removeMovie', 'as' => 'league.admin.movies.remove']);

		Route::get('admins', ['uses' => 'LeagueAdminController@admins', 'as' => 'league.admin.admins']);
		Route::post('admins/add', ['uses' => 'LeagueAdminController@addAdmin', 'as' => 'league.admin.admins.add']);
		Route::post('admins/remove', ['uses' => 'LeagueAdminController@removeAdmin', 'as' => 'league.admin.admins.remove']);
	});

});


// Admin
Route::group(['prefix' => 'admin', 'before' => ['auth', 'admin'], 'namespace' => 'Admin'], function () {
	Route::get('/', ['uses' => 'HomeController@index', 'as' => 'admin.index']);
});

/**
 * Global route filters
 */
Route::when('*', 'csrf', ['post']);