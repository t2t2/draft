<?php

/**
 * MovieEarning
 *
 * @property integer        $id
 * @property integer        $movie_id
 * @property \Carbon\Carbon $date
 * @property integer        $domestic
 * @property integer        $worldwide
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Movie    $movie
 * @method static \Illuminate\Database\Query\Builder|\MovieEarning whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\MovieEarning whereMovieId($value)
 * @method static \Illuminate\Database\Query\Builder|\MovieEarning whereDate($value)
 * @method static \Illuminate\Database\Query\Builder|\MovieEarning whereDomestic($value)
 * @method static \Illuminate\Database\Query\Builder|\MovieEarning whereWorldwide($value)
 * @method static \Illuminate\Database\Query\Builder|\MovieEarning whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\MovieEarning whereUpdatedAt($value)
 */
class MovieEarning extends Eloquent {

	/**
	 * Fields to format as dates
	 * @var array
	 */
	protected $dates = ['date'];

	/**
	 * Allow filling of these fields
	 * @var array
	 */
	protected $fillable = ['movie_id', 'date', 'domestic', 'worldwide'];

	/**
	 * Get the movie of the earning
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function movie() {
		return $this->belongsTo('Movie');
	}

}