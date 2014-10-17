<?php

class LeagueTeamsTableSeeder extends Seeder {

	public function run() {
		$teams = [
			['league_id' => 1, 'name' => 'Brian Brushwood'],
			['league_id' => 1, 'name' => 'Justin Robert Young'],
			['league_id' => 1, 'name' => 'Tom Merritt'],
			['league_id' => 1, 'name' => 'C. Robert Cargill'],
			['league_id' => 1, 'name' => 'Scott Johnson'],
			['league_id' => 1, 'name' => 'Sarah Lane'],
		];

		foreach ($teams as $team) {
			LeagueTeam::create($team);
		}

	}

}