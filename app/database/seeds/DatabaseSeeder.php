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
			exit('No, seriously');
		}


		Eloquent::unguard();

		// Cleanup
		$tables = [
			'leagues',
			'users'
		];

		foreach($tables as $table) {
			DB::table($table)->truncate();
		}

		// Seeding
		$this->call('UserTableSeeder');
		$this->call('LeagueTableSeeder');
	}

}
