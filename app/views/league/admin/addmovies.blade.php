@extends('layout.league')

@section('layout.content')

	<div class="row">
		<div class="small-6 column">
			<h2>Add Movies</h2>
		</div>
		<div class="small-6 column">
			<a class="button right small secondary" href="{{ route('league.admin.movies', ['league' => $league->slug]) }}"><i class="fa fa-backward"></i> Back to movies</a>
		</div>
	</div>

	<div class="clearfix">
		<p>Search range: {{ $date_range[0]->toFormattedDateString() }} - {{ $date_range[1]->toFormattedDateString() }}</p>
	</div>

	{{ Form::open(['route' => ['league.admin.movies.store', 'league' => $league->slug]]) }}

		<button type="submit"><i class="fa fa-plus"></i> Add Selected Movies</button>

		<table>
			<thead>
				<tr>
					<th>Movie</th>
					<th class="small-1">Add</th>
				</tr>
			</thead>
			<tbody>
				@forelse($movies as $movie)
					<tr>
						<td>
							<strong>{{ $movie->name }}</strong><br />
							<em>Release: {{ $movie->release->toFormattedDateString() }}</em>
						</td>
						<td>
							{{ Form::checkbox('movie[]', $movie->id) }}
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="2">No movies found</td>
					</tr>
				@endforelse
			</tbody>
		</table>

		<button type="submit"><i class="fa fa-plus"></i> Add Selected Movies</button>

	{{ Form::close() }}

@endsection