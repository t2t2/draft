<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeagueMoviesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('league_movies', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('league_id');
			$table->unsignedInteger('movie_id');
			$table->unsignedInteger('price')->nullable();
			$table->unsignedInteger('latest_earnings_id')->nullable();
			$table->timestamps();

			$table->foreign('league_id')
			      ->references('id')->on('leagues')
			      ->onUpdate('cascade')->onDelete('cascade');

			$table->foreign('movie_id')
			      ->references('id')->on('movies')
			      ->onUpdate('cascade')->onDelete('cascade');

			$table->foreign('latest_earnings_id')
			      ->references('id')->on('movie_earnings')
			      ->onUpdate('cascade')->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('league_movies');
	}

}
