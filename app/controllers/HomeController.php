<?php

class HomeController extends PageController {

	public function home() {
		$this->layout->content = View::make('home');
	}

}
