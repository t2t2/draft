<?php

class MovieEarningsTableSeeder extends Seeder {

	/**
	 * Cache earnings in a static variable for fast re-seeding
	 * @var
	 */
	private static $earnings;

	/**
	 * Seed the Movie Earnings Table
	 */
	public function run() {

		if (! self::$earnings) {
			self::$earnings = $this->readFile(app_path('database/seeds/movie_earnings.csv'));
		}

		// Dump everything into the database, chunked due to amount of data
		foreach (array_chunk(self::$earnings, 199) as $chunk) {
			DB::table('movie_earnings')->insert($chunk);
		}

		// Get newest entries ID's to update movie's latest earnings ID's
		$latest = DB::table('movie_earnings')->select([DB::raw('max(id) as maxID'), 'movie_id'])
		            ->groupBy('movie_id')
		            ->get();

		$movies = Movie::all();

		/** @var stdClass $earning */
		foreach ($latest as $earning) {
			/** @var Movie $movie */
			$movie = $movies->find($earning->movie_id);
			$movie->latest_earnings_id = $earning->maxID;
		}

		// Save latest earnings ID's
		$movies->map(function (Movie $movie) {
			$movie->save();
		});
	}


	/**
	 * Reads a file and returns a generator
	 *
	 * @param      $path
	 * @param bool $hasHeader
	 *
	 * @return array
	 */
	public function readFile($path, $hasHeader = true) {
		$header = null;

		$data = [];

		if (($handle = fopen($path, 'r')) !== false) {
			while (($row = fgetcsv($handle)) !== false) {
				if (! $header && $hasHeader) {
					$header = $row;
				} else {
					$assoc = array_combine($header, $row);
					$assoc['created_at'] = $assoc['updated_at'] = Carbon::now();

					$data[] = $assoc;
				}
			}
			fclose($handle);
		}

		return $data;
	}
}