<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovieEarningsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		DB::transaction(function () {
			Schema::create('movie_earnings', function (Blueprint $table) {
				$table->increments('id');
				$table->unsignedInteger('movie_id');
				$table->date('date');
				$table->unsignedBigInteger('domestic')->nullable();
				$table->unsignedBigInteger('worldwide')->nullable();
				$table->timestamps();

				$table->foreign('movie_id')->references('id')->on('movies')->onUpdate('cascade')->onDelete('cascade');
			});

			Schema::table('movies', function (Blueprint $table) {
				$table->foreign('latest_earnings_id')->references('id')->on('movie_earnings')->onUpdate('cascade')
				      ->onDelete('cascade');
			});

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		DB::transaction(function () {
			Schema::table('movies', function (Blueprint $table) {
				$table->dropForeign('movies_latest_earnings_id_foreign');
			});

			Schema::drop('movie_earnings');
		});
	}

}
