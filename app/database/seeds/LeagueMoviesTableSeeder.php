<?php

class LeagueMoviesTableSeeder extends Seeder {

	/**
	 * league_movies table seeding
	 */
	public function run() {

		/** @var League $league */
		$league = League::find(1);
		$movies = Movie::all();

		$went_for = [
			1  => 14, 2 => 20, 3 => 46, 4 => 17, 5 => 48,
			6  => 15, 7 => 36, 8 => 26, 9 => 10, 10 => 33,
			11 => 17, 12 => 25, 13 => 36, 14 => 28, 15 => 30,
			16 => 15, 17 => 11, 18 => 20, 19 => 18, 20 => 14,
			21 => 22, 22 => 9, 23 => 9, 24 => 16, 25 => 10,
			26 => 12, 27 => 7, 28 => 7, 29 => 7, 30 => 6
		];

		foreach ($went_for as $movie_id => $price) {
			/** @var Movie $movie */
			$movie = $movies->find($movie_id);

			$league_movie = new LeagueMovie();

			$league_movie->movie_id = $movie_id;
			$league_movie->price = $price;
			$league_movie->latest_earnings_id = $movie->latest_earnings_id;

			$league->movies()->save($league_movie);
		}


	}

}