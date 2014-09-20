<?php

/**
 * Route model bindings
 */

Route::bind('user', function($value) {
	return User::where('username', $value)->firstOrFail();
});

Route::bind('league', function($value) {
	return League::where('slug', $value)->firstOrFail();
});


