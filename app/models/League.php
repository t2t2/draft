<?php
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Collection;

/**
 * League
 *
 * @property integer                                                      $id
 * @property string                                                       $name
 * @property string                                                       $slug
 * @property string                                                       $description
 * @property string                                                       $url
 * @property string                                                       $mode
 * @property integer                                                      $money
 * @property string                                                       $units
 * @property integer                                                      $extra_weeks
 * @property \Carbon\Carbon                                               $start_date
 * @property \Carbon\Carbon                                               $end_date
 * @property boolean                                                      $private
 * @property boolean                                                      $featured
 * @property boolean                                                      $active
 * @property \Carbon\Carbon                                               $created_at
 * @property \Carbon\Carbon                                               $updated_at
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
 * @method static \Illuminate\Database\Query\Builder|\League whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\League whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\League whereUpdatedAt($value)
 * @method static \League season($year, $season)
 * @property-read \Illuminate\Database\Eloquent\Collection|\User[]        $admins
 * @property-read \Illuminate\Database\Eloquent\Collection|\LeagueMovie[] $movies
 * @property-read \Illuminate\Database\Eloquent\Collection|\LeagueTeam[]  $teams
 */
class League extends Eloquent implements SluggableInterface {

	use SluggableTrait;

	/**
	 * Fields to format as dates
	 * @var array
	 */
	protected $dates = ['start_date', 'end_date'];

	/**
	 * Allow filling of these fields
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
	 * League's admins relationship (n:m)
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function admins() {
		return $this->belongsToMany('User', 'league_admins')
		            ->withTimestamps();
	}

	/**
	 * League's movies relationship (1:n)
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function movies() {
		return $this->hasMany('LeagueMovie');
	}

	/**
	 * League's teams relationship (1:n)
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function teams() {
		return $this->hasMany('LeagueTeam');
	}

	/**
	 * Search by season scope
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param int                                   $year   Season year
	 * @param int                                   $season Season id as defined by configuration
	 *
	 * @return $this
	 */
	public function scopeSeason(Illuminate\Database\Eloquent\Builder $query, $year, $season) {
		$seasons = Config::get('draft.seasons');
		$search_start = $seasons[$season]['start'];
		$search_end = $seasons[$season]['end'];
		$search_start[0] = $search_end[0] = $year;

		$search_start = call_user_func_array(['Carbon', 'create'], $search_start);
		$search_end = call_user_func_array(['Carbon', 'create'], $search_end);

		return $query->whereBetween('start_date', [$search_start, $search_end]);
	}

	/**
	 * Check if user is an admin of the league
	 *
	 * @param User $user
	 *
	 * @return bool
	 */
	public function userIsAdmin(User $user) {
		if (isset($this->relations['admins'])) {
			return $this->admins->contains($user);
		} else {
			return $this->admins()->where('user_id', $user->id)->count();
		}
	}

	/**
	 * Update league's dates
	 */
	public function updateLeagueDates() {
		/** @var Collection|LeagueMovie[] $movies */
		$movies = $this->movies()->with('movie')->ordered()->get(); // Get up to date data

		if ($movies->count()) {
			/** @var Movie $earliest */
			$earliest = $movies->first()->movie;
			/** @var Movie $latest */
			$latest = $movies->last()->movie;

			// Start date = Earliest release, End date = Last release + extra weeks
			$this->start_date = $earliest->release;
			$this->end_date = $latest->release->copy()->addWeeks($this->extra_weeks);

			// The draft length must not be longer than the hard-coded limit
			$max_date = $this->start_date->copy()->addWeeks(Config::get('draft.maximum_weeks'));
			if ($this->end_date > $max_date) {
				$this->end_date = $max_date;
			}
		} else {
			// No movies = Start and end date well in the future
			$this->start_date = $this->end_date = Carbon::now()->addWeeks(Config::get('draft.maximum_weeks'));
		}
	}

	/**
	 * Calculate the last
	 *
	 * @return Carbon
	 */
	public function maxLastMovieDate() {
		return $this->start_date->copy()->addWeeks(Config::get('draft.maximum_weeks'))->subWeeks($this->extra_weeks);
	}
}