<?php

/**
 * League admin controller
 *
 * Class LeagueAdminController
 */
class LeagueAdminController extends PageController {

	/**
	 * League settings validation rules
	 *
	 * @var array
	 */
	public $league_settings_rules = [
		'name'        => ['required', 'max:255'],
		'description' => ['required'],
		'url'         => ['url'],
		'private'     => ['boolean'],

		'money'       => ['required', 'integer'],
		'units'       => ['required', 'max:16'],
		'extra_weeks' => ['required', 'integer', 'between:1,12'],
	];

	/**
	 * League settings page
	 *
	 * @param League $league
	 */
	public function settings(League $league) {
		$league->load('admins');

		$this->layout->content = View::make('league.admin.settings', ['league' => $league, 'validation_rules' => $this->league_settings_rules]);
	}

	/**
	 * Updating league settings page
	 *
	 * @param League $league
	 *
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function storeSettings(League $league) {
		$validator = Validator::make(Input::all(), $this->league_settings_rules);

		if ($validator->fails()) {
			Notification::error('Duder, check your input');

			return Redirect::back()->withInput()->withErrors($validator);
		}

		// Overwrites
		$league->fill(Input::only([
			'name', 'description', 'url', 'private', 'money', 'units', 'extra_weeks'
		]));

		if ($league->save()) {
			// TODO: End date checking

			Notification::success('Changes saved!');

			return Redirect::route('league.admin.settings', ['league' => $league->slug]);
		} else {
			Notification::error('Database error, try again later!');

			return Redirect::back()->withInput();
		}
	}

} 