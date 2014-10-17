<?php

class LeagueController extends PageController {


	/**
	 * Leagues list page
	 */
	public function index() {
		$search = Input::all();

		// Generate seasons array, also default season.
		$seasons_list = [];
		$seasons = Config::get('draft.seasons');
		$now = Carbon::now();

		if (isset($search['season']) && ! array_key_exists($search['season'], $seasons) && $search['season'] != 0) {
			unset($search['season']);
		}

		foreach ($seasons as $key => $info) {
			$seasons_list[$key] = $info['name'];
			if (! isset($search['season'])) {
				$start = call_user_func_array(['Carbon', 'create'], $info['start']);
				$end = call_user_func_array(['Carbon', 'create'], $info['end']);
				if ($now->between($start, $end)) {
					$search['season'] = $key;
				}
			}
		}
		// Generate years array, also default year
		$years_list = range($now->year + 1, Config::get('draft.earliest_year'), -1);
		$years_list = array_combine($years_list, $years_list);
		if (! isset($search['year']) || ! isset($years_list[$search['year']])) {
			$search['year'] = $now->year;
		}

		// Generate the query
		$leagues_query = League::query();

		// Season
		if ($search['season'] != 0) {
			$leagues_query->season($search['year'], $search['season']);
		}

		$leagues = $leagues_query->paginate(10);

		// Output
		$this->layout->title = 'Leagues';
		$this->layout->content = View::make('league.index', [
			'leagues' => $leagues,
			'seasons' => $seasons_list,
			'years'   => $years_list,
			'search'  => $search,
		]);
	}

	/**
	 * Show leagues related to the current user
	 */
	public function mine() {
		$leagues_query = League::query();

		// Where the user is a player
		// TODO

		// Where the user is an admin
		$leagues_query->join('league_admins', function ($join) {
			$join->on('leagues.id', '=', 'league_admins.league_id');
		});
		$leagues_query->where('league_admins.user_id', Auth::user()->id);

		$leagues_query->orderBy('start_date', 'desc');
		$leagues = $leagues_query->paginate(10, ['leagues.*']);

		// Output
		$this->layout->title = 'My Leagues';
		$this->layout->content = View::make('league.mine', compact('leagues'));
	}

	/**
	 * New league validation rules
	 * @var array
	 */
	public $league_valid_rules = [
		'name'        => ['required', 'max:255'],
		'description' => ['required'],
		'url'         => ['url'],
		'private'     => ['boolean'],

		'money'       => ['required', 'integer'],
		'units'       => ['required', 'max:16'],
		'extra_weeks' => ['required', 'integer', 'between:1,12'],
	];

	/**
	 * League creation form
	 */
	public function create() {

		$this->layout->title = 'Create league';
		$this->layout->content = View::make('league.create', [
			'validation_rules' => $this->league_valid_rules,
		]);
	}

	/**
	 * League creation
	 */
	public function store() {
		$validator = Validator::make(Input::all(), $this->league_valid_rules);
		if ($validator->fails()) {
			Notification::error('Whoops, something is wrong with your input. Check your errors and try again.');

			return Redirect::route('league.create')->withInput()->withErrors($validator);
		}

		// Create the league
		$league = new League(Input::only([
			'name', 'description', 'url', 'private', 'money', 'units'
		]));
		$league->mode = 'bid';
		$league->extra_weeks = Input::get('extra_weeks');
		$league->start_date = $league->end_date = Carbon::now()->addWeeks(Config::get('draft.maximum_weeks'));

		if ($league->save()) {
			// Attach current user as league admin
			$league->admins()->attach(Auth::user());

			Notification::success('Your league has been created.');

			return Redirect::route('league.show', ['league_slug' => $league->slug]);
		} else {
			Notification::error('Database error, please try again later.');

			return Redirect::back()->withInput();
		}
	}

	/**
	 * League page
	 *
	 * @param League $league
	 */
	public function show(League $league) {
		$league->load('teams');


		$this->layout->content = View::make('league.show', compact('league'));
		$this->layout->content->show_league_info = true;
	}
} 