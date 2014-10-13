<?php

use Goutte\Client;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;

class MovieScraperWorker {

	/**
	 * HTTP client for requests
	 *
	 * @var Client
	 */
	protected $client;

	/**
	 * URL to scrape
	 *
	 * @var string
	 */
	protected $url = 'http://pro.boxoffice.com/statistics/release-calendar?display_all=yes';

	/**
	 * @param Client $client
	 */
	function __construct(Client $client) {
		$this->client = $client;
	}

	/**
	 * Scrape for new movie data
	 *
	 * @param Job $job
	 * @param     $data
	 */
	public function fire(Job $job, $data) {
		$movies = $this->getMovies();

		$this->scanDatabaseForChanges($movies);

	}

	/**
	 * Get the upcoming movies
	 *
	 * @return Collection|array[]
	 */
	private function getMovies() {
		// Limit the scraped movie release date to the max length of the league
		$limit = Carbon::now()->addWeeks(Config::get('draft.maximum_weeks'));

		$crawler = $this->client->request('GET', $this->url, []);
		// Generate a list of movies
		$movies = $crawler->filter('.data_table tbody tr')->each(function (Crawler $node) use ($limit) {

			$cols = $node->children();

			$release_date = Carbon::createFromFormat('M j, Y|', trim($cols->eq(0)->text()));
			if ($release_date->gt($limit)) {
				return false;
			}

			try {
				$info = [
					'release'      => $release_date,
					'name'         => $cols->eq(1)->children()->eq(0)->text(),
					'boxoffice_id' => str_replace('/statistics/movies/', '', $cols->eq(1)->children()->eq(0)
					                                                              ->attr('href'))
				];

			} catch (Exception $e) {
				return false;
			}
			// If anything is missing, return false
			$valid = array_reduce($info, function ($state, $item) {
					return $state && $item;
				}, true);

			return $valid ? $info : false;
		});

		$movies = new Collection(array_filter($movies));

		return $movies;
	}

	/**
	 * Scan database for new movies and add them to the database
	 *
	 * @param Collection $movies
	 */
	private function scanDatabaseForChanges(Collection $new_data) {
		$new_data = $new_data->keyBy('boxoffice_id');

		$movies = Movie::whereIn('boxoffice_id', $new_data->keys())->get();

		$movies_by_id = $movies->keyBy('boxoffice_id');
		$new_movies = new Collection(array_diff_key($new_data->toArray(), $movies_by_id->toArray()));

		$this->updateMovies($new_data, $movies_by_id);
		$this->addMovies($new_movies);
	}

	/**
	 * Update current movies with new data
	 *
	 * @param Collection         $new_data
	 * @param EloquentCollection $current_data
	 */
	private function updateMovies(Collection $new_data, EloquentCollection $current_data) {
		$current_data->map(function(Movie $movie) use($new_data) {
			$data = $new_data->get($movie->boxoffice_id);

			$movie->name = $data['name'];
			$movie->release = $data['release'];

			if($movie->isDirty()) {
				$movie->save();
			}
		});
	}

	/**
	 * Add new movies
	 *
	 * @param Collection $new_movies
	 */
	private function addMovies(Collection $new_movies) {
		$new_movies->map(function($data) {
			$movie = Movie::create($data);
		});
	}


}