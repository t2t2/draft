<?php

class AuthController extends PageController {

	/**
	 * Page to show if user hasn't logged it but hit by auth filter
	 */
	public function loginPage() {
		$this->layout->title = 'Login';
		$this->layout->content = View::make('login');
	}

	/**
	 * Login with persona
	 *
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 */
	public function login() {
		/* Force logout */
		if (! Auth::guest()) {
			Auth::logout();
		}

		if (! $assertion = Input::get("assertion")) {
			App::abort(401, 'i don\'t even');
		}
		// Check with persona
		try {
			$response = GuzzleHttp\post(
				'https://verifier.login.persona.org/verify',
				[
					'body' => [
						'assertion' => $assertion,
						'audience'  => url(),
					],
				]
			);
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			App::abort(401, 'You are not authorized.');

			return false;
		}

		$verification = $response->json();
		if ($verification['status'] != "okay") {
			App::abort(401, 'You are not authorized.');

			return false;
		}
		$email = $verification['email'];

		// Log in to laravel
		if (Auth::attempt(['email' => $email, 'password' => 'moz:persona'], true)) {
			if (Request::ajax()) {
				return Response::json(['refresh' => true]);
			} else {
				return Redirect::back();
			}
		} else {
			// Not an user
			Session::put('register_email', $email);
			if (Request::ajax()) {
				return Response::json(['redirect' => route('auth.register')]);
			} else {
				return Redirect::route('auth.register.form');
			}
		}

	}

	/**
	 * Logout
	 *
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 */
	public function logout() {
		if (Session::has("register_email")) {
			Session::forget("register_email");
		}

		Auth::logout();

		if (Request::ajax()) {
			return Response::json(["redirect" => url("/")]);
		} else {
			return Redirect::to("/");
		}
	}

	/**
	 * Registration validation rules
	 * @var array
	 */
	public $register_valid_rules = [
		'username'    => ['required', 'unique:users', 'min:2', 'max:16'],
		'displayname' => ['max:64'],
	];

	/**
	 * Registration form
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function registerForm() {
		if (! Session::has('register_email')) {
			return Redirect::home();
		}

		$this->layout->content = View::make("register", [
			'validation_rules' => $this->register_valid_rules,
			'email'            => Session::get('register_email'),
		]);

		return $this->layout;
	}

	/**
	 * Registration handler
	 *
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function register() {
		if (! Session::has('register_email')) {
			return Redirect::home();
		}

		// Validate
		$validator = Validator::make(Input::all(), $this->register_valid_rules);
		if ($validator->fails()) {
			Notification::error("Something's wrong, check the fields bellow!");

			return Redirect::back()->withInput()->withErrors($validator);
		}

		// Create user
		$user = new User();
		$user->username = Input::get("username");
		$user->displayname = Input::get("displayname");
		$user->email = Session::get("register_email");

		// Save & Login
		if ($user->save()) {
			Auth::login($user, true);
			Session::forget("register_email");

			Notification::success("Welcome {$user->name}!");

			return Redirect::to("/");
		} else {
			Notification::error("Save errors :'(");

			return Redirect::to("register")->withInput();
		}
	}
}