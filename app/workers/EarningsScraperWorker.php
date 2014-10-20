<?php

use Carbon\Carbon;
use Goutte\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\Jobs\Job;
use Symfony\Component\DomCrawler\Crawler;

class EarningsScraperWorker {

	/**
	 * @type Client
	 */
	private $client;

	/**
	 * @param Client $client
	 */
	function __construct(Client $client) {
		$this->client = $client;
	}

	/**
	 * Scrape earnings
	 *
	 * @param Job $job
	 * @param     $data
	 */
	public function fire(Job $job, $data) {
		if ($job->attempts() > 3) {
			$job->delete();

			return;
		}

		/** @type Carbon $date */
		$date = $data['day'] instanceof Carbon ? $data['day'] : new Carbon($data['day']);
		list($dateFor, $earnings) = $this->getEarnings($date);

		$this->storeInteresting($dateFor, $earnings);

		$job->delete();
	}

	/**
	 * Get earnings data
	 *
	 * @param Carbon $date
	 *
	 * @return array
	 */
	private function getEarnings(Carbon $date) {
		$crawler = $this->client->request('GET', $this->getUrl($date), [], [], [
			'HTTP_USER_AGENT' => "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:33.0) Gecko/20100101 Firefox/33.0"
		]);

		$date = new Carbon($crawler->filter('.data_table>h1 span')->text());
		$movies = $crawler->filter('.data_table tbody tr')->each(function (Crawler $node) {
			$cols = $node->children();

			$info = [
				'boxoffice_id'   => str_replace('/statistics/movies/', '', $cols->eq(1)->children()->eq(0)
				                                                                ->attr('href')),
				'domestic_total' => intval(str_replace(['$', ','], '', $cols->eq(7)->text())),
			];

			return $info;
		});

		return [$date, $movies];
	}

	/**
	 * Get the URL to scrape
	 *
	 * @param Carbon $date
	 *
	 * @return string
	 */
	private function getUrl(Carbon $date) {
		return 'http://pro.boxoffice.com/statistics/bo_numbers/daily/' . $date->copy()->addDay()
		                                                                      ->toDateString() . '?force_kind=true';
	}

	/**
	 * Store
	 *
	 * @param Carbon $dateFor
	 * @param        $earnings
	 */
	private function storeInteresting(Carbon $dateFor, $earnings) {
		$movies = $this->getActiveMovies($dateFor)->keyBy('boxoffice_id');

		foreach ($earnings as $info) {
			if (! $movies->has($info['boxoffice_id'])) {
				continue;
			}

			/** @type Movie $movie */
			$movie = $movies->get($info['boxoffice_id']);
			$earnings = $movie->earnings()->where('date', $dateFor)->first();
			if (! $earnings) {
				$earnings = new MovieEarning([
					'movie_id' => $movie->id,
					'date'     => $dateFor,
				]);
			}

			$earnings->domestic = $info['domestic_total'];

			if ($earnings->isDirty()) {
				$earnings->save();

				if (! $movie->latestEarnings || $movie->latestEarnings->date < $earnings->date) {
					$movie->latest_earnings_id = $earnings->id;
					$movie->save();
				}

				// Find any LeagueMovies that might should be updated
				$leagueMovies = DB::table('league_movies')
				                  ->where('league_movies.movie_id', $movie->id)
				                  ->join('leagues', 'league_movies.league_id', '=', 'leagues.id')
				                  ->where('leagues.end_date', '>', $dateFor)
				                  ->get(['league_movies.id']); // . infront bypasses clearing of dots, which is actually needed here

				foreach ($leagueMovies as $leagueMovie) {
					Queue::push('UpdateLeagueMovieEarnings', [
						'league_movie_id' => $leagueMovie->id, 'earnings_id' => $earnings->id
					]);
				}
			}

		}

	}

	/**
	 * Get movies that are being used in an active League
	 *
	 * @param Carbon $dateFor
	 *
	 * @return Collection
	 */
	private function getActiveMovies(Carbon $dateFor) {
		$movies = Movie::query()
		               ->where('release', '<', $dateFor)
		               ->whereExists(function (\Illuminate\Database\Query\Builder $query) use ($dateFor) {
			               $query->select(DB::raw(1))
			                     ->from('league_movies')
			                     ->join('leagues', 'league_movies.league_id', '=', 'leagues.id')
			                     ->whereRaw('league_movies.movie_id = movies.id')
			                     ->where('leagues.end_date', '>', $dateFor);
		               })->get();

		return $movies;
	}
}