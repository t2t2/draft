<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories([

	app_path() . '/commands',
	app_path() . '/controllers',
	app_path() . '/models',
	app_path() . '/database/seeds',

]);

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path() . '/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/


App::error(function (Exception $exception, $code) {
	Log::error($exception);
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function () {
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path() . '/filters.php';


/*
 * Missing & Error pages
 */

App::missing(function ($exception) {
	$layout = View::make("layout.main");
	$layout->content = View::make("errors.404");

	return Response::make($layout, 404);
});

use Illuminate\Database\Eloquent\ModelNotFoundException;

App::error(function (ModelNotFoundException $e) {
	$layout = View::make("layout.main");
	$layout->content = View::make("errors.404");

	return Response::make($layout, 404);
});


/*
 * Layout Defaults
 */
View::composer('layout.main', function ($view) {
	// Translate js settings into data attributes
	if (! isset($view->javascript)) {
		$view->javascript = [];
	}
	$jsdata = [
		'url'       => url(),
		'asset-url' => asset(''),
		'csrf'      => Session::getToken()
	];
	if (isset($view->javascript[0])) {
		$jsdata['controller'] = $view->javascript[0];
	}
	if (isset($view->javascript[1])) {
		$jsdata['action'] = $view->javascript[1];
	}
	if (Auth::check()) {
		$jsdata['user'] = Auth::user()->email;
	} elseif(Session::has('register_email')) {
		$jsdata['user'] = Session::get('register_email');
	}
	$view->jsdata = "";
	foreach ($jsdata as $key => $value) {
		$view->jsdata .= ' data-' . $key . '="' . e($value) . '"';
	}

	// Empty content
	if (! isset($view->content)) {
		$view->content = "";
	}

});


/*
 * Route Model bindings
 */

Route::bind('username', function($value) {
	return User::where('username', $value)->firstOrFail();
});

Route::bind('league-slug', function($value) {
	return League::where('slug', $value)->firstOrFail();
});















