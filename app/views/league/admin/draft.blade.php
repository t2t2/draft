@extends('layout.league')

@section('layout.content')

	<h2>Draft</h2>
	<p>Drafting mode: <span data-tooltip aria-haspopup="true" class="has-tip" title="A movie can be owned by 1 team, A team can own multiple movies">Movie n : 1 Team</span></p>

	{{ Form::model(['movie' => $movies]) }}
		<table>
			<thead>
				<tr>
					<th>Movie</th>
					<th class="small-3 large-2">Price</th>
					<th>Team</th>
				</tr>
			</thead>
			<tbody>
				@foreach($movies as $league_movie_id => $moviedata)
				<?php
				$movie = $moviedata['movie'];
				?>
					<tr>
						<td>
							<strong>{{ $movie->name }}</strong><br />
							<em>Release: {{ $movie->release->toFormattedDateString() }}</em>
						</td>
						<td>
							<div class="row collapse">
								<div class="small-9 columns">
									{{ Form::number("movie[{$league_movie_id}][price]") }}
								</div>
								<div class="small-3 columns">
									<span class="postfix">{{{ $league->units }}}</span>
								</div>
							</div>
						</td>
						<td>
							{{ Form::select("movie[{$league_movie_id}][team_id]", $teams) }}
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>

		{{ Form::button('Save', ['type' => 'Submit']) }}

	{{ Form::close() }}

@endsection