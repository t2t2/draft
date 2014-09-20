<nav class="top-bar" data-topbar role="navigation">
	<ul class="title-area">
		<li class="name">
			<h1><a href="{{ route('home') }}">{{ trans('app.brand') }}</a></h1>
		</li>
		<li class="toggle-topbar menu-icon"><a href="#"><span>{{ trans('navigation.menu') }}</span></a></li>
	</ul>

	<section class="top-bar-section">
		<ul class="left">
<?php
$navbar = [
	['text' => trans('navigation.home'), 'url' => route('home'), 'active' => 'home'],
	['text' => trans('navigation.league'), 'url' => route('league.index'), 'active' => 'league.*'],
];
?>
			@include('partials.nav', ['items' => $navbar])
		</ul>

		<ul class="right">
			@if(Auth::check())
				@if(Auth::user()->admin)
					<li{{ str_is('admin.*', Route::currentRouteName()) ? ' class="active"' : '' }}>
						<a href="{{ route('admin.index') }}">Admin</a>
					</li>
				@endif
				<li class="has-dropdown">
					<a href="#">Hi {{ Auth::user()->name }} <span class="caret"></span></a>
					<ul class="dropdown">
						<li><a href="{{ route('user.show', ['user' => Auth::user()->username]) }}">Profile</a></li>
						<li><a href="#" data-persona="logout"><span>Logout</span></a></li>
					</ul>
				</li>
			@elseif(Session::has('register_email'))
				<li{{ Route::currentRouteName() == 'auth.register.form' ? ' class="active"' : '' }}>
					<a href="{{ route('auth.register.form') }}"><span>Finish Sign Up</span></a>
				</li>
				<li>
					<a href="#" data-persona="logout">Cancel</a>
				</li>
			@else
				<li class="has-form">
					<button class="persona-button dark" data-persona="login"><span>{{ trans('navigation.login') }}</span></button>
				</li>
			@endif
		</ul>

	</section>

</nav>
