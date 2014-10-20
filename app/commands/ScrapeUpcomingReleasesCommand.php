<?php

use Illuminate\Console\Command;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Indatus\Dispatcher\Scheduling\ScheduledCommandInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ScrapeUpcomingReleasesCommand extends Command implements ScheduledCommandInterface {

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
	 * Create a new command instance.
	 */
	public function __construct(\GuzzleHttp\Client $client) {
		parent::__construct();
	}

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
	 * When a command should run
	 *
	 * @param Scheduler|\Indatus\Dispatcher\Scheduling\Schedulable $scheduler
	 *
	 * @return \Indatus\Dispatcher\Scheduling\Schedulable|\Indatus\Dispatcher\Scheduling\Schedulable[]
	 */
	public function schedule(\Indatus\Dispatcher\Scheduling\Schedulable $scheduler) {
		return $scheduler->weekly();
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
