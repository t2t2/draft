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
		if (! isset($search['year']) || isset($years_list[$search['year']])) {
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
		$this->layout->title = "Leagues";
		$this->layout->content = View::make('league.index', [
			'leagues' => $leagues,
			'seasons' => $seasons_list,
			'years'   => $years_list,
			'search'  => $search,
		]);
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
			'name', 'description', 'url', 'private', 'mode', 'money', 'units'
		]));
		$league->extra_weeks = Input::get('extra_weeks');

		if($league->save()) {
			// Attach current user as league admin

			//TODO

		} else {


		}
	}

	/**
	 * League page
	 *
	 * @param League $league
	 */
	public function show(League $league) {


		$this->layout->content = View::make('league.show', compact('league'));
	}
} 