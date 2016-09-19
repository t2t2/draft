<?php

use Illuminate\Support\Collection;
class GraphMovie {
	public $earnings;
	private $earliest = NULL;
	public function __construct() {
		$this->earnings = new Collection();
	}
	public function addEarning($date,$earning) {
		$this->earnings->put($date,$earning);
		if (($this->earliest != NULL && $date < $this->earliest) || $this->earliest == NULL) {
			$this->earliest = $date;
		}
	}
	public function sort() {
		$this->earnings->sortBy(function($role){return $role;});
	}
	public function getEarningForDate($date) {
		//No data yet
		if(count($this->earnings)==0) {
			return 0;
		}
		//Data exists
		if($this->earnings->contains($date)) {
			return $this->earnings->get($date);
		}
		
		//Before earliest data
		//if ($this->earliest != NULL && $date < $this->earliest) {
		//	return 0;
		//}
		
		//Gap somewhere. Iterate through earnings and find the 
		//Assume sorted
		$retval = 0;
		foreach ($this->earnings->keys() as $checkdate) {
			if ($checkdate > $date) {
				return $retval; //Return the entry before this 
			}
			$retval = $this->earnings->get($checkdate);
		}
		
		//?
		return $retval;
	}
}
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
		if(! isset($search['inactive'])) {
			$search['inactive'] = false;
		}

		// Generate the query
		/** @type League|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $leagues_query */
		$leagues_query = League::query();

		$leagues_query->where('private', 0);

		// Season
		if ($search['season'] != 0) {
			$leagues_query->season($search['year'], $search['season']);
		}
		// Active
		$leagues_query->where('active', !$search['inactive']);


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
		/** @type League|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $leagues_query */
		$leagues_query = League::query();

		// Where the user is a player
		$leagues_query->whereExists(function (\Illuminate\Database\Query\Builder $query) {
			$query->select([DB::raw(1)])
			      ->from('league_teams')
			      ->join('league_team_user', 'league_teams.id', '=', 'league_team_user.league_team_id')
			      ->where('league_team_user.user_id', Auth::user()->id)
			      ->whereRaw('league_teams.league_id = leagues.id');
		});

		// Where the user is an admin
		$leagues_query->orWhereExists(function (\Illuminate\Database\Query\Builder $query) {
			$query->select([DB::raw(1)])
			      ->from('league_admins')
			      ->where('league_admins.user_id', Auth::user()->id)
			      ->whereRaw('league_admins.league_id = leagues.id');
		});

		$leagues_query->orderBy('start_date', 'desc');
		$leagues_query->select('leagues.*');
		$leagues = $leagues_query->paginate(10);

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
			'name', 'description', 'url', 'money', 'units'
		]));
		$league->private = Input::get('private') ? true : false;
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
		$league->load(['teams', 'teams.users', 'teams.movies' => function($query) {
			/** @type LeagueMovie|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query */
			$query->ordered();
		}, 'teams.movies.movie', 'teams.movies.latestEarnings']);

		// Pre-do some work for teams and sort them by earnings
		$teams = new Collection();

		foreach ($league->teams as $team) {
			$earnings = $team->movies->reduce(function ($total, LeagueMovie $movie) {
				if ($movie->latestEarnings) {
					return $total + $movie->latestEarnings->domestic;
				} else {
					return $total;
				}
			}, 0);
			$paid_for = $team->movies->reduce(function ($total, LeagueMovie $movie) {
				return $total + $movie->price;
			}, 0);
			$teams->push(compact('team', 'earnings', 'paid_for'));
		}
		$teams->sortByDesc('earnings');

		$this->layout->content = View::make('league.show', compact('league', 'teams'));
		$this->layout->content->show_league_info = true;
	}
	
	public function getChartData(League $league) {
		$complete = new Collection();
		$possible_dates = new Collection();
		$startdate = $league->start_date;
		$enddate = $league->end_date;
		$league->load(['teams.movies.movie.earnings' => function($query) {
		}])->where('movie_earnings.date','<=',$enddate);
		
		//Find all possible dates first
		foreach ($league->teams as $team) {
			foreach ($team->movies as $movie) {
				foreach ($movie->movie->earnings as $earning){
					if($earning->date >= $league->start_date && $earning->date <= $league->end_date) {
						$possible_dates->push($earning->date->format("U"));
					}
				}
			}
		}
		$possible_dates = $possible_dates->unique();
		$possible_dates->sortBy(function($role){return $role;});
		
		//Go through the teams and populate the data
		foreach ($league->teams as $team) {
			$team_info = new Collection();
			$team_earnings = new Collection();
			$movies = new Collection();
			$complete->push($team_info);
			$team_info->put("data",$team_earnings);
			$team_info->put("label",$team->name);
			$team_info->put("id",$team->id);

			//Fill actual data for movies now
			foreach ($team->movies as $movie) {
				foreach ($movie->movie->earnings as $earning){
					$mydate = $earning->date->format("U");
					if($possible_dates->contains($mydate)) {
						$movieinfo = $movies->get($movie->movie->id,new GraphMovie());
						$movieinfo->addEarning($mydate,$earning->domestic);
						$movies->put($movie->movie->id,$movieinfo);
					}
				}
			}
			//Sort movies so we can evaluate gaps
			foreach ($movies->values() as $movie) {
				$movie->sort();
			}
			foreach ($possible_dates->values() as $date) {
				$item = new Collection();
				$item->push($date * 1000); 
				$movietotal = 0;
				foreach ($movies->values() as $movie) {
					$movietotal += $movie->getEarningForDate($date);
				}
				$item->push($movietotal);
				$team_earnings->push($item);
			}
		}
		
		return Response::json($complete);
	}

	/**
	 * League display based by movies
	 *
	 * @param League $league
	 */
	public function showMovies(League $league) {
		// Preload data
		$league->load(['movies' => function($query) {
			/** @type LeagueMovie|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query */
			$query->ordered();
		}, 'movies.movie', 'movies.latestEarnings', 'movies.teams']);

		$this->layout->content = View::make('league.show.movies', compact('league'));
		$this->layout->content->show_league_info = true;
	}
} 