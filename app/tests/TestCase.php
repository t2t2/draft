<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	public $dbSeeded = false;
	
	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication() {
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__ . '/../../bootstrap/start.php';
	}

	public function seedDatabase() {
		$this->app['artisan']->call('migrate');
		$this->app['artisan']->call('db:seed');
		$this->dbSeeded = true;
	}


	/**
	 * Start database transaction to speed up the tests related to database
	 */
	public function setUp() {
		parent::setUp();

		if (! $this->dbSeeded) {
			$this->seedDatabase();
		}

		DB::beginTransaction();
	}

	/**
	 * After test roll back all done stuff
	 */
	public function tearDown() {
		parent::tearDown();

		DB::rollBack();
	}
}
