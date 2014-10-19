@extends('layout.league')

@section('layout.content')

	<h2>Teams</h2>

	@if($league->teams->count() > 0)
		{{ Form::open(['route' => ['league.admin.teams.remove', 'league' => $league->slug]]) }}
			<ul class="no-bullet">
				@foreach($league->teams as $team)
					<?php
					$profiles = $team->users->map(function($user) { return link_to_route('user.show', $user->name, ['user' => $user->username]); })->all();
					?>
					<li class="clearfix">
						{{{ $team->name }}}
						<button class="tiny alert right" type="submit" name="team" value="{{ $team->id }}"><i class="fa fa-remove"></i></button>
						<p>
							Players: {{ implode(', ', $profiles) }}
						</p>
					</li>
				@endforeach
			</ul>
		{{ Form::close() }}
	@else
		<p>No teams</p>
	@endif

	<h3>Add Team</h3>

	{{ Former::vertical_open()->route('league.admin.teams.add', ['league' => $league->slug])->rules($validation_rules) }}

        {{ Former::text('username') }}
        {{ Former::text('team_name') }}

        {{ Former::actions()->submit('Add team') }}

    {{ Former::close() }}


@endsection