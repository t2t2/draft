<?php

class AdminController extends PageController {

	/**
	 * Admin Homepage
	 */
	public function index() {
		$stats = [
			'users' => User::count(),
			'leagues' => League::count(),
			'movies' => Movie::count(),
		];


		$this->layout->title = 'Admin';
		$this->layout->content = View::make('admin.index', compact('stats'));
	}
} 