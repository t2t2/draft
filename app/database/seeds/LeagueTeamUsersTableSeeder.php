<?php

class LeagueTeamUsersTableSeeder extends Seeder {

	public function run() {

		$teams = LeagueTeam::all();

		$players = [
			1 => 2,
			2 => 3,
			3 => 4,
			4 => 5,
			5 => 6,
			6 => 7,
		];

		foreach($players as $team_id => $user) {
			/** @type LeagueTeam $team */
			$team = $teams->find($team_id);

			$team->users()->attach($user);
		}

	}

}