<?php

class LeaguePagesTest extends TestCase {

	public function testLeagueIndexPage() {
		/** @type League $league */
		$league = League::first();
		$this->client->request('GET', route('league.show', ['slug' => $league->slug]));

		$this->assertResponseOk();
	}

}