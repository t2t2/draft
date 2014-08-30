<?php
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

/**
 * League
 *
 * @property integer                                               $id
 * @property string                                                $name
 * @property string                                                $slug
 * @property string                                                $description
 * @property string                                                $url
 * @property string                                                $mode
 * @property integer                                               $money
 * @property string                                                $units
 * @property integer                                               $extra_weeks
 * @property \Carbon\Carbon                                        $start_date
 * @property \Carbon\Carbon                                        $end_date
 * @property boolean                                               $private
 * @property boolean                                               $featured
 * @property \Carbon\Carbon                                        $created_at
 * @property \Carbon\Carbon                                        $updated_at
 * @method static \Illuminate\Database\Query\Builder|\League whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\League whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\League whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\League whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\League whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\League whereMode($value)
 * @method static \Illuminate\Database\Query\Builder|\League whereMoney($value)
 * @method static \Illuminate\Database\Query\Builder|\League whereUnits($value)
 * @method static \Illuminate\Database\Query\Builder|\League whereExtraWeeks($value)
 * @method static \Illuminate\Database\Query\Builder|\League whereStartDate($value)
 * @method static \Illuminate\Database\Query\Builder|\League whereEndDate($value)
 * @method static \Illuminate\Database\Query\Builder|\League wherePrivate($value)
 * @method static \Illuminate\Database\Query\Builder|\League whereFeatured($value)
 * @method static \Illuminate\Database\Query\Builder|\League whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\League whereUpdatedAt($value)
 * @property boolean                                               $active
 * @method static \Illuminate\Database\Query\Builder|\League whereActive($value)
 * @method static \League season($year, $season)
 * @property-read \Illuminate\Database\Eloquent\Collection|\User[] $admins
 */
class League extends Eloquent implements SluggableInterface {

	use SluggableTrait;

	/**
	 * Fields to format as dates
	 * @var array
	 */
	protected $dates = ['start_date', 'end_date'];

	/**
	 * Allow filling these fields
	 * @var array
	 */
	protected $fillable = ['name', 'description', 'url', 'private', 'mode', 'money', 'units'];

	/**
	 * Sluggable configuration
	 * @var array
	 */
	protected $sluggable = [
		'build_from' => 'name',
		'save_to'    => 'slug',
	];


	/**
	 * Search by season scope
	 *
	 * @param \Illuminate\Database\Query\Builder $query
	 * @param int                                $year   Season year
	 * @param int                                $season Season id as defined by configuration
	 *
	 * @return $this
	 */
	public function scopeSeason(Illuminate\Database\Query\Builder $query, $year, $season) {
		$seasons = Config::get('draft.seasons');
		$search_start = $seasons[$season]['start'];
		$search_end = $seasons[$season]['end'];
		$search_start[0] = $search_end[0] = $year;

		$search_start = call_user_func_array(['Carbon', 'create'], $search_start);
		$search_end = call_user_func_array(['Carbon', 'create'], $search_end);

		return $query->whereBetween('start_date', [$search_start, $search_end]);
	}


	/**
	 * League's admins relationship (n:m)
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function admins() {
		return $this->belongsToMany('User', 'league_admins')
			->withTimestamps();
	}


}