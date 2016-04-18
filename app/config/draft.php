<?php

return [

	/**
	 * Admin's email
	 */
	'admin_email' => null,

	/**
	 * League defaults
	 *
	 * Mode - Movie acquirement mode
	 * Money - Money per player
	 * Units - Units used for money
	 * Extra Weeks - Amount of weeks after all movies have been released
	 */
	'league_defaults' => [
		'mode'        => 'bid',
		'money'       => 100,
		'units'       => '₪',
		'extra_weeks' => 4,
	],

	/**
	 * Maximum amount of months a league can be active
	 */
	'maximum_weeks'   => 36,

	/**
	 * Source Mode
	 */
	'source' => 'boxmojo',

	/**
	 * Rules on how the season for the league is decided (based on $league->start_date).
	 * Values is used for Carbon::create so follow it's order. (Make sure year is null to keep it current year.
	 */
	'seasons'         => [
		// Spring Mini-season
		1 => [
			'name' => 'Spring',
			'start' => [null, 1, 1, 0, 0],
			'end' => [null, 3, 20, 0, 0],
		],
		// Summer Season
		2 => [
			'name' => 'Summer',
			'start' => [null, 3, 20, 0, 0],
			'end' => [null, 9, 1, 0, 0],
		],
		// Summer Season
		3 => [
			'name' => 'Winter',
			'start' => [null, 9, 1, 0, 0],
			'end' => [null, 12, 31, 23, 59],
		],
	],

	/**
	 * Earliest year for showing leagues by season
	 */
	'earliest_year' => 2012,

];
