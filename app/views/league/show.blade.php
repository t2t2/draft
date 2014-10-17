@extends('layout.league')

@section('layout.content')
	<h4>Teams</h4>

	<ol>
		@forelse($league->teams as $team)
			<li class="panel">
				<h5>{{{ $team->name }}} <small>{{-- Team members --}}</small></h5>
				<p>Movies:</p>
			</li>
		@empty
			<li>The ghostly team</li>
		@endforelse
	</ol>

@endsection