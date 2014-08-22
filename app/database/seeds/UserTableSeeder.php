<?php

class UserTableSeeder extends Seeder {

	public $faker;

	public function __construct() {
		$this->faker = Faker\Factory::create();
	}

	public function run() {
		DB::table('users')->truncate();

		for ($i = 0; $i < 99; $i++) {

			$user = [
				'username'    => $this->faker->userName,
				'displayname' => $this->faker->boolean(80) ? $this->faker->name : null,
				'email'       => $this->faker->safeEmail,
			];

			User::create($user);

		}
	}
}