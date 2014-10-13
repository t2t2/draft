<?php

/**
 * League admin controller
 *
 * Class LeagueAdminController
 */
class LeagueAdminController extends PageController {

	/**
	 * League settings validation rules
	 *
	 * @var array
	 */
	public $league_settings_rules = [
		'name'        => ['required', 'max:255'],
		'description' => ['required'],
		'url'         => ['url'],
		'private'     => ['boolean'],

		'money'       => ['required', 'integer'],
		'units'       => ['required', 'max:16'],
		'extra_weeks' => ['required', 'integer', 'between:1,12'],
	];

	/**
	 * League settings page
	 *
	 * @param League $league
	 */
	public function settings(League $league) {
		$league->load('admins');

		$this->layout->content = View::make('league.admin.settings', compact('league'));
		$this->layout->content->validation_rules = $this->league_settings_rules;
	}

	/**
	 * Updating league settings page
	 *
	 * @param League $league
	 *
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function storeSettings(League $league) {
		$validator = Validator::make(Input::all(), $this->league_settings_rules);

		if ($validator->fails()) {
			Notification::error('Duder, check your input');

			return Redirect::back()->withInput()->withErrors($validator);
		}

		// Overwrites
		$league->fill(Input::only([
			'name', 'description', 'url', 'private', 'money', 'units', 'extra_weeks'
		]));

		if ($league->save()) {
			// TODO: End date checking

			Notification::success('Changes saved!');

			return Redirect::route('league.admin.settings', ['league' => $league->slug]);
		} else {
			Notification::error('Database error, try again later!');

			return Redirect::back()->withInput();
		}
	}

	/**
	 * List league's movies
	 *
	 * @param League $league
	 */
	public function movies(League $league) {
		$league->load(['movies' => function ($query) {
			/** @var \Illuminate\Database\Eloquent\Builder|Movie $query */
			$query->ordered();
		}, 'movies.movie']);

		$this->layout->content = View::make('league.admin.movies', compact('league'));
	}

	/**
	 * List movies that can be added to the league
	 *
	 * @param League $league
	 */
	public function addableMovies(League $league) {

		list($date_range, $movies) = $this->getAddableMovies($league);

		$this->layout->content = View::make('league.admin.addmovies', compact('league', 'movies', 'date_range'));
	}


	/**
	 * Add movies to a league
	 *
	 * @param League $league
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function addMovies(League $league) {
		$movie_ids = Input::get('movie');
		if (count($movie_ids) == 0) {
			Notification::error('Please choose movies to add');

			return Redirect::back();
		}

		list($date_range, $movies) = $this->getAddableMovies($league, $movie_ids);

		/** @type Movie $movie */
		foreach ($movies as $movie) {
			$league->movies()->create(['movie_id' => $movie->id, 'latest_earnings_id' => $movie->latest_earnings_id]);
		}

		$league->updateLeagueDates();
		
		Notification::success(count($movies) . ' movie(s) have been added!');

		return Redirect::route('league.admin.movies', ['league' => $league->slug]);
	}


	/**
	 * Removes a LeagueMovie from the league
	 *
	 * @param League $league
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function removeMovie(League $league) {

		$movie_id = intval(Input::get('movie'));

		/** @var LeagueMovie $movie */
		if (! $movie_id || ! ($movie = $league->movies()->find($movie_id))) {
			Notification::error('Movie not found');

			return Redirect::back();
		}

		$movie->delete();
		$league->updateLeagueDates();

		Notification::success('Movie removed from the league');

		return Redirect::back();
	}

	/**
	 * Get moves that can be added by the league, optionally filtered by ID's
	 *
	 * @param League $league
	 * @param array  $ids
	 *
	 * @return array
	 */
	protected function getAddableMovies(League $league, $movie_ids = []) {
		$date_range = [Carbon::now(), $league->maxLastMovieDate()];

		$query = Movie::query();

		if (count($movie_ids)) {
			$query->whereIn('id', $movie_ids);
		}
		// Remove movies already added
		if ($league->movies->count()) {
			$query->whereNotIn('id', $league->movies->fetch('movie_id')->toArray());
		} else {
			// If no movies set start date to a more saner value
			$date_range[1] = Carbon::now()->addWeeks(Config::get('draft.maximum_weeks'));
		}
		$query->whereBetween('release', $date_range);
		$query->orderBy('release', 'asc');

		$movies = $query->get();

		return [$date_range, $movies];
	}

} 