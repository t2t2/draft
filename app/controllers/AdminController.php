<?php

class AdminController extends PageController {

	/**
	 * Admin Homepage
	 */
	public function index() {
		$stats = [
			'users' => User::count(),
			'leagues' => League::count(),
		];


		$this->layout->title = 'Admin';
		$this->layout->content = View::make('admin.index', compact('stats'));
	}
} 