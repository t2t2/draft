<?php
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

/**
 * League
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $url
 * @property string $mode
 * @property integer $money
 * @property string $units
 * @property integer $extra_weeks
 * @property \Carbon\Carbon $start_date
 * @property \Carbon\Carbon $end_date
 * @property boolean $private
 * @property boolean $featured
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
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
 */
class League extends Eloquent implements SluggableInterface {

	protected $dates = ['start_date', 'end_date'];

	use SluggableTrait;

	protected $sluggable = [
		'build_from' => 'name',
		'save_to' => 'slug',
	];

	/**
	 * Season scope
	 */
	public function season($query, $year, $season) {
		
	}
}