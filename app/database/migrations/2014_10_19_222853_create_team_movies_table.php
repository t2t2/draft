<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamMoviesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('league_team_movies', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('league_team_id');
			$table->unsignedInteger('league_movie_id');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('league_team_movies');
	}

}
