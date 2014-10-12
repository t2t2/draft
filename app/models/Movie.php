<?php

/**
 * Movie
 *
 * @property integer                                                       $id
 * @property string                                                        $name
 * @property string                                                        $boxmojo_id
 * @property string                                                        $boxoffice_id
 * @property \Carbon\Carbon                                                $release
 * @property integer                                                       $latest_earnings_id
 * @property \Carbon\Carbon                                                $created_at
 * @property \Carbon\Carbon                                                $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\MovieEarning[] $earnings
 * @property-read \MovieEarning                                            $latestEarnings
 * @method static \Illuminate\Database\Query\Builder|\Movie whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Movie whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Movie whereBoxmojoId($value)
 * @method static \Illuminate\Database\Query\Builder|\Movie whereRelease($value)
 * @method static \Illuminate\Database\Query\Builder|\Movie whereLatestEarningsId($value)
 * @method static \Illuminate\Database\Query\Builder|\Movie whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Movie whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Movie whereBoxofficeId($value)
 */
class Movie extends Eloquent {

	/**
	 * Fields to format as dates
	 * @var array
	 */
	protected $dates = ['release'];

	/**
	 * Allow filling of these fields
	 * @var array
	 */
	protected $fillable = ['name', 'boxmojo_id', 'boxoffice_id', 'release'];

	/**
	 * All movie's earnings
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function earnings() {
		return $this->hasMany('MovieEarning');
	}

	/**
	 * Movie's latest earnings
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function latestEarnings() {
		return $this->belongsTo('MovieEarning');
	}
}