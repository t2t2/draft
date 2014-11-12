<div class="row">
	<div class="small-12 column">
		<h1>{{{ $league->name }}}</h1>
	</div>
</div>

@if(isset($show_league_info) ? $show_league_info : false)
	<div class="row">
		<div class="medium-6 column">
			<h4>About</h4>
			<p>
				{{ nl2br(e($league->description)) }}
			</p>
			@if($league->url)
				<p><a href="{{{ $league->url }}}">{{{ $league->url }}}</a></p>
			@endif
		</div>
		<div class="medium-3 column">
			<h4>League Settings</h4>
			<ul class="no-bullet">
				<li>Money: {{ $league->money }}{{{ $league->units }}}</li>
				<li>Start date: {{{ $league->start_date->toFormattedDateString() }}} </li>
				<li>End date: {{{ $league->end_date->toFormattedDateString() }}}</li>
			</ul>
		</div>
		<div class="medium-3 column">
			<h4>Admins</h4>
			<ul class="no-bullet">
				@foreach($league->admins as $user)
					<li><a href="{{ route('user.show', ['user' => $user->username]) }}">{{ $user->username }}</a></li>
				@endforeach
			</ul>
			@if($league->ended)
				<div data-alert class="alert-box secondary">
					This league has been archived.
				</div>
			@endif
		</div>
	</div>
@endif

@unless($league->active || Auth::guest() || !$league->userIsAdmin(Auth::user()))
	<div class="row">
		<div class="small-12 column">
			<div data-alert class="alert-box warning" role="alert">
				<p><strong>Your league isn't active yet</strong> <small>But it's ok</small></p>
				<p>In order for a league to be considered to be active you must do the following:</p>

				<ul>
					<li>Add movies to your league</li>
					<li>Add teams to your league</li>
					<li>Draft the movies to the teams</li>
				</ul>
			</div>
		</div>
	</div>
@endunless

<div class="row">
	<div class="medium-3 column">
<?php
$navs = [
	[
		'text' => 'Home', 'url' => route('league.show', ['league' => $league->slug]),
		'active' => 'league.show'
	],
	[
		'text' => 'Movies', 'url' => route('league.show.movies', ['league' => $league->slug]),
		'active' => 'league.show.movies'
	],
];
if(Auth::check() && $league->userIsAdmin(Auth::user()) && !$league->ended) {
	$navs[] = 'divider';
	$navs[] = ['text' => 'League Admin'];
	$navs[] = [
		'text' => 'Settings', 'url' => route('league.admin.settings', ['league' => $league->slug]),
		'active' => 'league.admin.settings'
	];
	$navs[] = [
		'text' => 'Movies', 'url' => route('league.admin.movies', ['league' => $league->slug]),
		'active' => 'league.admin.movies*'
	];
	$navs[] = [
		'text' => 'Teams', 'url' => route('league.admin.teams', ['league' => $league->slug]),
		'active' => 'league.admin.teams'
	];
	$navs[] = [
		'text' => 'Draft', 'url' => route('league.admin.draft', ['league' => $league->slug]),
		'active' => 'league.admin.draft'
	];
	$navs[] = [
		'text' => 'Admins', 'url' => route('league.admin.admins', ['league' => $league->slug]),
		'active' => 'league.admin.admins'
	];
}
?>
		<nav>
			<ul class="side-nav">
					@include('partials.nav', ['items' => $navs])
			</ul>
		</nav>
	</div>
	<div class="medium-9 column">
		@yield('layout.content')
	</div>
</div>

