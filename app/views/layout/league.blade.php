<h1>{{ $league->name }}</h1>

@unless($league->active)
	<div class="alert alert-warning" role="alert">
		<strong>Your league isn't active yet</strong> <small>But it's ok</small>
		<p>In order to consider a league to be active you must do the following:</p>

		<ul>
			<li>Add movies to your league</li>
			<li>Add teams to your league</li>
			<li>Draft your movies to the teams</li>
		</ul>
	</div>
@endunless

@yield('league.content')