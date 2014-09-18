<?php

class LeagueTableSeeder extends Seeder {

	public function run() {
		$league = [
			'id'          => 1,
			'name'        => 'NSFWshow Summer Movie Draft 2013',
			'description' => 'It\'s back, bitches!',
			'url'         => 'http://draft.nsfwshow.com/',

			'mode'        => 'bid',
			'money'       => 100,
			'units'       => '₪',
			'extra_weeks' => 4,

			'start_date'  => '2013-04-19',
			'end_date'    => '2013-09-20',

			'private'     => false,
			'featured'    => true,
		];

		League::create($league);
	}
} 