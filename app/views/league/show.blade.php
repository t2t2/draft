@extends('layout.league')

@section('layout.content')
	<h4>Teams</h4>

	<ol>
		@forelse($teams as $team_array)
			<?php
			extract($team_array);
			/**
            * Sets values:
             * $team LeagueTeam The Team
             * $earnings number The amount of money team earned
             * $paid_for number How much the team paid in total
             */

			$profiles = $team->users->map(function($user) {
				return link_to_route('user.show', $user->name, ['user' => $user->username]);
			})->all();
			$movies = $team->movies->map(function($leagueMovie) use($league) {
				return e($leagueMovie->movie->name.' ('.$leagueMovie->price.$league->units.')');
			})->all();
			?>
			<li class="panel">
				<div class="row">
					<div class="medium-6 column">
						<h5>{{{ $team->name }}} <small>{{ implode(', ', $profiles) }}</small></h5>
					</div>
					<div class="medium-6 column medium-text-right">
						Total: ${{ number_format($earnings) }}
                    	<small>for {{{ number_format($paid_for).$league->units }}} / {{{ number_format($league->money).$league->units }}}</small>
					</div>
				</div>
				<p>Movies: {{ implode(', ', $movies) }}</p>
			</li>
		@empty
			<li>The ghostly team</li>
		@endforelse
	</ol>

@endsection