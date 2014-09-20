<h1>{{ $league->name }}</h1>

<div class="row">
	<div class="col-md-6">
		<h4>About</h4>
		<p>
			{{{ $league->description }}}
		</p>
		@if($league->url)
			<p><a href="{{{ $league->url }}}">{{{ $league->url }}}</a></p>
		@endif
	</div>
	<div class="col-md-3">
		<h4>League Settings</h4>
		<ul class="list-unstyled">
			<li>Mode: {{ $league->mode }}</li>
			<li>Money: {{ $league->money }}{{ $league->units }}</li>
		</ul>
	</div>
	<div class="col-md-3">
		<h4>Admins</h4>
		<ul class="list-unstyled">
			@foreach($league->admins as $user)
				<li><a href="{{ route('user.show', ['user' => $user->username]) }}">{{ $user->username }}</a></li>
			@endforeach
		</ul>
	</div>
</div>

@unless($league->active || Auth::guest() || !$league->userIsAdmin(Auth::user()))
	<div class="alert alert-warning" role="alert">
		<strong>Your league isn't active yet</strong> <small>But it's ok</small>
		<p>In order for a league to be considered to be active you must do the following:</p>

		<ul>
			<li>Add movies to your league</li>
			<li>Add teams to your league</li>
			<li>Draft your movies to the teams</li>
		</ul>
	</div>
@endunless

<div class="row">
	<div class="col-md-3">
<?php
$navs = [
	['text' => 'Home', 'url' => route('league.show', ['league' => $league->slug])],
	'divider',
	['text' => 'Admin'],
];
?>
		<ul class="nav">
			@include('partials.nav', ['items' => $navs])
		</ul>
	</div>
	<div class="col-md-9">
		@yield('league.content')
	</div>
</div>

