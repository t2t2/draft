<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaguesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leagues', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('slug');
			$table->text('description');
			$table->text('url')->nullable();

			$table->string('mode', 16)->default(Config::get('draft.league_defaults.mode'));
			$table->integer('money')->default(Config::get("draft.league_defaults.money"));
			$table->string('units', 16)->default(Config::get('draft.league_defaults.units'));
			$table->integer('extra_weeks')->default(Config::get('draft.league_defaults.extra_weeks'));

			$table->date('start_date');
			$table->date('end_date');
			$table->boolean('active')->default(false)->nullable();

			$table->boolean('private')->default(false)->nullable();
			$table->boolean('featured')->default(false)->nullable();

			$table->timestamps();

			$table->unique('slug');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('leagues');
	}

}
