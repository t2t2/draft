<?php

class AdminPageTest extends TestCase {

	/**
	 * Make sure public can't access admin pages
	 *
	 * @dataProvider pubUsers
	 *
	 * @param callable $userCb
	 *
	 * @return void
	 */
	public function testMakeSurePublicCantAccess($userCb) {
		// Set current user
		$user = $userCb();
		$this->flushSession();
		Route::enableFilters();
		if ($user) {
			$this->be($user);
		}

		// get admin homepage
		$crawler = $this->client->request('GET', '/admin');

		$this->assertTrue($this->client->getResponse()->isRedirection());

		Route::disableFilters();
	}

	/**
	 * Return public users to test with.
	 * Because this is ran before application is created it returns callbacks.
	 *
	 * @return array
	 */
	public function pubUsers() {
		return [
			[function () {
				return null;
			}], // Not logged in
			[function () {
				User::find(2);
			}], // Nonadmin user
		];
	}

	public function testAdminHomepage() {
		$user = User::find(1);
		$this->be($user);

		$crawler = $this->client->request('GET', '/admin');

		$this->assertTrue($this->client->getResponse()->isOk());
	}
}
