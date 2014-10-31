<?php

class LeaguesPageTest extends \TestCase {

	/**
	 * Test leagues index page and make sure at least one is found
	 */
	public function testLeaguesIndexPage() {
		$crawler = $this->client->request('GET', route('league.index'));

		$this->assertResponseOk();
		$this->assertEquals(0, $crawler->filter('.league-info')->count(), 'More than 1 league found');
	}

	/**
	 * Make sure admins see their leagues
	 */
	public function testShowAdminsLeagues() {
		$this->be(User::find(1));

		$this->getUsersLeagues(1);
	}

	/**
	 * Make sure players see their leagues
	 */
	public function testShowPlayersLeagues() {
		$this->be(User::find(2));

		$this->getUsersLeagues(1);
	}

	/**
	 * But not just anyone
	 */
	public function testShowNewUsersLeagues() {
		$new_user = User::create(['username' => 'test']);

		$this->be($new_user);

		$this->getUsersLeagues(0);
	}

	/**
	 * Check that the user has as many leagues as asked for
	 *
	 * @param int $expected
	 */
	protected function getUsersLeagues($expected = 0) {
		$crawler = $this->client->request('GET', route('league.mine'));

		$this->assertResponseOk();
		$this->assertEquals($expected, $crawler->filter('.league-info')->count(), 'Amount of leagues');
	}

}