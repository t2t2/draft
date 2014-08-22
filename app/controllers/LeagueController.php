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
		if($search['season'] != 0) {
			$search_start = $seasons[$search['season']]['start'];
			$search_end = $seasons[$search['season']]['end'];
			$search_start[0] = $search_end[0] = $search['year'];
			$search_start = call_user_func_array(['Carbon', 'create'], $search_start);
			$search_end = call_user_func_array(['Carbon', 'create'], $search_end);

			$leagues_query->whereBetween('start_date', [$search_start, $search_end]);
		}

		$leagues = $leagues_query->paginate(10);

		// Output
		$this->layout->content = View::make('league.index', [
			'leagues' => $leagues,
			'seasons' => $seasons_list,
			'years'   => $years_list,
			'search'  => $search,
		]);
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