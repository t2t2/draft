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
	
@if($show_past)
	<div class="row">
		<div class="small-12 column">
			<div data-alert class="alert-box warning" role="alert">
				<p>Are you sure you want to add an already released movie?</p>
				<p>Normally you would only be adding upcoming movies to a draft,
				Since estimates/gross are already available for these movies.</p>
			</div>
		</div>
	</div>
@endif

	{{ Form::open(['route' => ['league.admin.movies.store', 'league' => $league->slug, 'show_past' => $show_past]]) }}
@unless($show_past)
			<a class="button right small secondary" href="{{ route('league.admin.movies.add', ['league' => $league->slug, 'show_past' => true]) }}"><i class="fa fa-backward"></i> Add Already released movies</a>
@endunless
@if($show_past)
			<a class="button right small secondary" href="{{ route('league.admin.movies.add', ['league' => $league->slug, 'show_past' => false]) }}"><i class="fa fa-forward"></i> Add upcoming movies</a>
@endif
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