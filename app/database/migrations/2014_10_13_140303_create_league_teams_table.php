<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeagueTeamsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('league_teams', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('league_id');
			$table->string('name');
			$table->timestamps();

			$table->foreign('league_id')
			      ->references('id')->on('leagues')
			      ->onUpdate('cascade')->onDelete('cascade');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('league_teams');
	}

}
