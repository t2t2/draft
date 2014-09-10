@extends('layout.leagues')

@section('leagues.content')
	<h2>My Leagues</h2>

	@if($leagues->count())
		@foreach($leagues as $league)
			@include('partials.league', compact('league'))
		@endforeach

		{{ $leagues->links() }}
	@else
		<p>No leagues found :(</p>
	@endif
@endsection