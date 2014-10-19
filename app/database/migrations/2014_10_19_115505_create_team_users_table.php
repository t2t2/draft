<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('league_team_user', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('league_team_id');
			$table->unsignedInteger('user_id');
			$table->timestamps();

			$table->foreign('league_team_id')
				->references('id')->on('league_teams')
				->onDelete('cascade')->onUpdate('cascade');

			$table->foreign('user_id')
				->references('id')->on('users')
				->onDelete('cascade')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('league_team_user');
	}

}
