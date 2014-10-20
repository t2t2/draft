<?php

class LeagueTeamMoviesTableSeeder extends Seeder {

	public function run() {

		$teams = LeagueTeam::all();
		$owns = [
			1 => [1, 3, 18, 20, 30],
			2 => [6, 7, 13, 25],
			3 => [10, 14, 17, 24, 28],
			4 => [8, 12, 16, 21, 26],
			5 => [2, 5, 19, 23],
			6 => [4, 9, 11, 15, 22, 27, 29],
		];

		foreach($owns as $team_id => $movies) {
			/** @type LeagueTeam $team */
			$team = $teams->find($team_id);

			foreach($movies as $movie_id) {
				$team->movies()->attach($movie_id);
			}
		}

	}

}