<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// But seriously no
		if(App::environment() == 'production') {
			echo 'No, seriously';

			return;
		}
		$disableForeignKeyChecks = in_array(DB::connection()->getDriverName(), ['mysql']);

		Eloquent::unguard();
		if ($disableForeignKeyChecks) {
			DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		}

		// Cleanup
		$tables = [
			'league_admins',
			'league_movies',
			'leagues',
			'movie_earnings',
			'movies',
			'users'
		];
		foreach($tables as $table) {
			DB::table($table)->truncate();
		}

		if ($disableForeignKeyChecks) {
			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		}

		// Seeding
		$this->call('UserTableSeeder');
		$this->call('LeagueTableSeeder');
		$this->call('LeagueAdminsTableSeeder');
		$this->call('MovieTableSeeder');
		$this->call('MovieEarningsTableSeeder');
		$this->call('LeagueMoviesTableSeeder');
	}

}
