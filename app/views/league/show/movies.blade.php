@extends('layout.league')

@section('layout.content')
	<h4>Movies</h4>

	<table>
		<thead>
			<tr>
				<th>Name</th>
				<th>Release Date</th>
				<th>Gross</th>
				<th>Owner</th>
				<th class="small-1">For</th>
				<th>Gross / {{{ $league->units }}}</th>
			</tr>
		</thead>
		<tbody>
			@foreach($league->movies as $movie)
				<tr>
					<th>{{{ $movie->movie->name }}}</th>
					<td>{{{ $movie->movie->release->toFormattedDateString() }}}</td>
					@if($movie->latestEarnings)
						<td>{{{ '$'.number_format($movie->latestEarnings->domestic) }}}</td>
					@else
						<td>No data yet</td>
					@endif
					@if($movie->teams->count())
						<td>{{{ $movie->teams->implode('name', ', ') }}}</td>
						<td>{{{ $movie->price.$league->units }}}</td>
						<td>{{{ '$'.number_format(round($movie->latestEarnings->domestic / ($movie->price ?: 1), 2)) }}} / {{{ $league->units }}}</td>
					@else
						<td colspan="3" class="text-center">Not yet sold</td>
					@endif
				</tr>
			@endforeach
		</tbody>
	</table>

@endsection