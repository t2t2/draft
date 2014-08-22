<?php
$navbar = [
	['text' => trans('navigation.home'), 'url' => route('home'), 'active' => 'home'],
	['text' => trans('navigation.league'), 'url' => route('league.index'), 'active' => 'league.*'],
];
?>

<nav class="navbar navbar-default" role="navigation">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-navbar-collapse">
				<span class="sr-only">{{ trans('navigation.toggle_nav') }}</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="{{ route('home') }}">{{ trans('app.brand') }}</a>
		</div>

		<div class="collapse navbar-collapse" id="main-navbar-collapse">
			<ul class="nav navbar-nav">
				@include('partials.nav', ['items' => $navbar])
			</ul>
			@if(Auth::check())
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Hi {{ Auth::user()->name }} <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="{{ route('user.show', ['username' => Auth::user()->username]) }}">Profile</a></li>
							<li><a href="#" data-persona="logout"><span>Logout</span></a></li>
						</ul>
					</li>
				</ul>
			@elseif(Session::has('register_email'))
				<ul class="nav navbar-nav navbar-right">
					<li {{ Route::currentRouteName() == 'auth.register.form' ? 'class="active"' : '' }}>
						<a href="{{ route('auth.register.form') }}"><span>Finish Sign Up</span></a>
					</li>
					<li>
						<a href="#" data-persona="logout">Cancel</a>
					</li>
				</ul>
			@else
				<p class="navbar-text navbar-right navbar-persona">
					<button class="persona-button dark" data-persona="login"><span>{{ trans('navigation.login') }}</span></button>
				</p>
			@endif
		</div>
	</div>
</nav>
