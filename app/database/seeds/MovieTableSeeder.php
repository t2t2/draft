<?php

class MovieTableSeeder extends Seeder {

	/**
	 * Movie table seeder
	 */
	public function run() {
		$movies = [
			['id' => 1, 'name' => 'Oblivion', 'boxmojo_id' => 'oblivion', 'release' => new DateTime('4/19/2013')],
			['id' => 2, 'name' => 'Pain and Gain', 'boxmojo_id' => 'painandgain', 'release' => new DateTime('4/26/2013')],
			['id' => 3, 'name' => 'Iron Man 3', 'boxmojo_id' => 'ironman3', 'release' => new DateTime('5/3/2013')],
			['id' => 4, 'name' => 'The Great Gatsby', 'boxmojo_id' => 'greatgatsby2012', 'release' => new DateTime('5/10/2013')],
			['id' => 5, 'name' => 'Star Trek Into Darkness', 'boxmojo_id' => 'startrek12', 'release' => new DateTime('5/16/2013')],
			['id' => 6, 'name' => 'Epic', 'boxmojo_id' => 'leafmen', 'release' => new DateTime('5/24/2013')],
			['id' => 7, 'name' => 'Fast & Furious 6', 'boxmojo_id' => 'fast6', 'release' => new DateTime('5/24/2013')],
			['id' => 8, 'name' => 'The Hangover Part III', 'boxmojo_id' => 'hangover3', 'release' => new DateTime('5/23/2013')],
			['id' => 9, 'name' => 'Now You See Me', 'boxmojo_id' => 'nowyouseeme', 'release' => new DateTime('5/31/2013')],
			['id' => 10, 'name' => 'After Earth', 'boxmojo_id' => '1000ae', 'release' => new DateTime('5/31/2013')],
			['id' => 11, 'name' => 'The Internship', 'boxmojo_id' => 'internship', 'release' => new DateTime('6/7/2013')],
			['id' => 12, 'name' => 'This is the End', 'boxmojo_id' => 'rogenhilluntitled', 'release' => new DateTime('6/12/2013')],
			['id' => 13, 'name' => 'Man of Steel', 'boxmojo_id' => 'superman2012', 'release' => new DateTime('6/14/2013')],
			['id' => 14, 'name' => 'Monsters University', 'boxmojo_id' => 'monstersinc2', 'release' => new DateTime('6/21/2013')],
			['id' => 15, 'name' => 'World War Z', 'boxmojo_id' => 'worldwarz', 'release' => new DateTime('6/21/2013')],
			['id' => 16, 'name' => 'White House Down', 'boxmojo_id' => 'whitehousedown', 'release' => new DateTime('6/28/2013')],
			['id' => 17, 'name' => 'The Heat', 'boxmojo_id' => 'bullockmccarthy', 'release' => new DateTime('6/28/2013')],
			['id' => 18, 'name' => 'Despicable Me 2', 'boxmojo_id' => 'despicableme2', 'release' => new DateTime('7/3/2013')],
			['id' => 19, 'name' => 'Lone Ranger', 'boxmojo_id' => 'loneranger', 'release' => new DateTime('7/3/2013')],
			['id' => 20, 'name' => 'Grown Ups 2', 'boxmojo_id' => 'grownups2', 'release' => new DateTime('7/12/2013')],
			['id' => 21, 'name' => 'Pacific Rim', 'boxmojo_id' => 'pacificrim', 'release' => new DateTime('7/12/2013')],
			['id' => 22, 'name' => 'Turbo', 'boxmojo_id' => 'turbo', 'release' => new DateTime('7/17/2013')],
			['id' => 23, 'name' => 'Red 2', 'boxmojo_id' => 'red2', 'release' => new DateTime('7/19/2013')],
			['id' => 24, 'name' => 'The Wolverine', 'boxmojo_id' => 'wolverine2', 'release' => new DateTime('7/26/2013')],
			['id' => 25, 'name' => 'The Smurfs 2', 'boxmojo_id' => 'smurfs2', 'release' => new DateTime('7/31/2013')],
			['id' => 26, 'name' => '2 Guns', 'boxmojo_id' => '2guns', 'release' => new DateTime('8/2/2013')],
			['id' => 27, 'name' => 'Elysium', 'boxmojo_id' => 'elysium', 'release' => new DateTime('8/9/2013')],
			['id' => 28, 'name' => 'Planes', 'boxmojo_id' => 'planes', 'release' => new DateTime('8/9/2013')],
			['id' => 29, 'name' => 'Kick-Ass 2', 'boxmojo_id' => 'kickass2', 'release' => new DateTime('8/16/2013')],
			['id' => 30, 'name' => 'The World\'s End', 'boxmojo_id' => 'worldsend', 'release' => new DateTime('8/23/2013')]
		];

		foreach ($movies as $movie) {
			Movie::create($movie);
		}
	}
} 