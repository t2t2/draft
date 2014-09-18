<?php

class UserTableSeeder extends Seeder {

	/**
	 * Dummy users
	 * @var array
	 */
	protected $dummies = [
		[
			'id'       => '1',
			'username' => 'admin',
			'admin'    => true,
		],
		[
			'id'          => '2',
			'username'    => 'shwood',
			'displayname' => 'Brian Brushwood',
		],
		[
			'id'          => '3',
			'username'    => 'justinryoung',
			'displayname' => 'Justin Robert Young',
		],
		[
			'id'          => '4',
			'username'    => 'acedtect',
			'displayname' => 'Tom Merritt',
		],
		[
			'id'          => '5',
			'username'    => 'massawyrm',
			'displayname' => 'C. Robert Cargill',
		],
		[
			'id'          => '6',
			'username'    => 'scottjohnson',
			'displayname' => 'Scott Johnson',
		],
		[
			'id'          => '7',
			'username'    => 'sarahlane',
			'displayname' => 'Sarah Lane',
		]];

	/**
	 * Users table seeder
	 */
	public function run() {
		$users = $this->dummies;

		// Main user can be logged into if admin_email is set
		if (Config::get('draft.admin_email')) {
			$users[0]['email'] = Config::get('draft.admin_email');
		}

		foreach ($users as $user) {
			User::create($user);
		}
	}
}