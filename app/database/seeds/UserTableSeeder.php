<?php

class UserTableSeeder extends Seeder {

	public $faker;

	public function __construct() {
		$this->faker = Faker\Factory::create();
	}

	public function run() {
		DB::table('users')->truncate();

		for ($i=0; $i < 200; $i++) {

			$user = [
				'username' => $this->faker->userName,
				'email' => $this->faker->safeEmail,
			];
			if($i % 6 == 5) {
				$user['displayname'] = $this->faker->name;
			}

			User::create($user);

		}
	}
}