<?php

use Illuminate\Console\Command;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;

class ScrapeUpcomingReleasesCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'scrape:releases';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Scrape upcoming relases.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire() {
		Queue::push('MovieScraperWorker');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments() {
		return [
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions() {
		return [
		];
	}

	/**
	 * User to run the command as
	 * @return string Defaults to false to run as default user
	 */
	public function user() {
		return false;
	}

	/**
	 * Environment(s) under which the given command should run
	 * Defaults to '*' for all environments
	 * @return string|array
	 */
	public function environment() {
		return '*';
	}
}
