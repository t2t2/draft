<h1>Leagues</h1>

<h2>Search</h2>
<?php Former::populate($search); ?>
{{ Former::vertical_open()->route('league.index')->method('GET')->addClass('clearfix') }}
	<div class="row">
		{{ Former::select('year')->options($years)->addGroupClass('col-sm-2') }}
		{{ Former::select('season')->options($seasons)->addGroupClass('col-sm-3') }}
	</div>
	{{ Former::actions()->primary_submit('Search')->class('pull-right') }}
{{ Former::close() }}

@if($leagues->count())
	@foreach($leagues as $league)
		<h3><a href="{{ route('league.show', ['league_slug' => $league->slug]) }}">{{ $league->name }}</a></h3>
	@endforeach

	{{ $leagues->appends($search)->links() }}
@else
	<p>No leagues found :(</p>
@endif