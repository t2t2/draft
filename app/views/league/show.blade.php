@extends('layout.league')

@section('layout.content')
	<h4>Teams</h4>

	<ol>
		@forelse($league->teams as $team)
			<?php
			$profiles = $team->users->map(function($user) { return link_to_route('user.show', $user->name, ['user' => $user->username]); })->all();
			?>
			<li class="panel">
				<h5>{{{ $team->name }}} <small>{{ implode(', ', $profiles)}}</small></h5>
				<p>Movies:</p>
			</li>
		@empty
			<li>The ghostly team</li>
		@endforelse
	</ol>

@endsection