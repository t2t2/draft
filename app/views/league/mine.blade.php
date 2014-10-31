@extends('layout.leagues')

@section('layout.content')
	<h2>My Leagues</h2>

	@if($leagues->count())
		<ul class="no-bullet">
			@foreach($leagues as $league)
				@include('partials.league', compact('league'))
			@endforeach
		</ul>

		{{ $leagues->links() }}
	@else
		<p>No leagues found :(</p>
	@endif
@endsection