<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('movies', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('boxmojo_id')->nullable();
			$table->string('boxoffice_id')->nullable();
			$table->date('release');
			$table->unsignedInteger('latest_earnings_id')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('movies');
	}

}
