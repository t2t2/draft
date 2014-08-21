<?php

class UserController extends PageController {

	/**
	 * @param User $user
	 */
	public function show(User $user) {
		$this->layout->content = View::make('user.show', compact('user'));
	}
} 