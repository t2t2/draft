<?php

class LeagueTableSeeder extends Seeder {

	public $faker;

	public function __construct() {
		$this->faker = Faker\Factory::create();
	}

	public function run() {
		DB::table('leagues')->truncate();

		for ($i = 0; $i < 200; $i++) {

			$league = [
				'name'        => implode(' ', $this->faker->words(5)),
				'description' => $this->faker->paragraph(),
				'url'         => $this->faker->boolean(80) ? $this->faker->url : '',

				'mode'        => Config::get('draft.league_defaults.mode'),
				'money'       => Config::get('draft.league_defaults.money'),
				'units'       => Config::get('draft.league_defaults.units'),
				'extra_weeks' => Config::get('draft.league_defaults.extra_weeks'),

				'private'     => $this->faker->boolean(10),
				'featured'    => $this->faker->boolean(17),
			];

			$league = new League($league);
			$league->save();
		}
	}
} 