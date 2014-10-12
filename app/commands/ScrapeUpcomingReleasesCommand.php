<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

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

}
