<?php

use Illuminate\Queue\Jobs\Job;

class UpdateLeagueMovieEarnings {


	/**
	 * Update LeagueMovie with the latest earnings if applicable
	 *
	 * @param Job $job
	 * @param     $data
	 */
	public function fire(Job $job, $data) {

		/** @type LeagueMovie $leagueMovie */
		$leagueMovie = LeagueMovie::findOrFail($data['league_movie_id']);
		/** @type MovieEarning $earnings */
		$earnings = MovieEarning::findOrFail($data['earnings_id']);

		if ( // Sanity checks
			$leagueMovie->movie_id == $earnings->movie_id
			&& $leagueMovie->league->end_date > $earnings->date
			&& (! $leagueMovie->latestEarnings || $leagueMovie->latestEarnings->date < $earnings->date)
		) {
			$leagueMovie->latest_earnings_id = $earnings->id;
			$leagueMovie->save();

			// Possibly TODO: Update team's total earnings if that gets cached
		}

		$job->delete();

	}
}