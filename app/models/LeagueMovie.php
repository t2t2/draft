<?php

/**
 * LeagueMovie
 *
 * @property-read \League $league
 * @property-read \Movie $movie
 * @property-read \MovieEarning $latestEarnings
 * @property integer $id
 * @property integer $league_id
 * @property integer $movie_id
 * @property integer $price
 * @property integer $latest_earnings_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\LeagueMovie whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\LeagueMovie whereLeagueId($value) 
 * @method static \Illuminate\Database\Query\Builder|\LeagueMovie whereMovieId($value) 
 * @method static \Illuminate\Database\Query\Builder|\LeagueMovie wherePrice($value) 
 * @method static \Illuminate\Database\Query\Builder|\LeagueMovie whereLatestEarningsId($value) 
 * @method static \Illuminate\Database\Query\Builder|\LeagueMovie whereCreatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\LeagueMovie whereUpdatedAt($value) 
 */
class LeagueMovie extends Eloquent {


	/**
	 * The league this entry is in
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function league() {
		return $this->belongsTo('League');
	}

	/**
	 * The movie this entry represents
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function movie() {
		return $this->belongsTo('Movie');
	}

	/**
	 * The latest earning usable in the league context
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function latestEarnings() {
		return $this->belongsTo('MovieEarning');
	}
}