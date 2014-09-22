<?php

class LeagueAdminsTableSeeder extends Seeder {

	public function run() {

		// Add user #1 as admin to league #1
		/** @var User $user */
		/** @var League $league */
		$user = User::find(1);
		$league = League::find(1);

		$league->admins()->attach($user);
	}
} 