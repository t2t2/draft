<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * Get auth password
	 *
	 * Overwritten for mozilla persona usage.
	 */
	public function getAuthPassword() {
		return Hash::make('moz:persona');
	}

	/**
	 * Get user's preferred name (username or displayname)
	 */
	public function getNameAttribute() {
		if ($this->displayname) {
			return $this->displayname;
		}

		return $this->username;
	}
}
