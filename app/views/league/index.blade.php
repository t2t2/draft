@extends('layout.leagues')

@section('layout.content')

	<h2>Search</h2>
	<?php Former::populate($search); ?>
	{{ Former::vertical_open()->route('league.index')->method('GET')->addClass('clearfix') }}
		<div class="row">
			{{ Former::select('year')->options($years)->addGroupClass('medium-2 column') }}
			{{ Former::select('season')->options($seasons)->addGroupClass('medium-3 column') }}
			<div class="medium-7 column"><!-- placeholder --></div>
		</div>
		{{ Former::actions()->primary_submit('Search')->class('pull-right') }}
	{{ Former::close() }}

	@if($leagues->count())
		@foreach($leagues as $league)
			@include('partials.league', compact('league'))
		@endforeach

		{{ $leagues->appends($search)->links() }}
	@else
		<p>No leagues found :(</p>
	@endif
@endsection