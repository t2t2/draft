@extends('layout.leagues')

@section('leagues.content')
	<h1>Create a league</h1>

	{{ Former::open()->route('league.store')->rules($validation_rules) }}
		{{ Former::text('name') }}
		{{ Former::textarea('description') }}
		{{ Former::text('url') }}
		{{ Former::checkbox('private')->unchecked_value(0) }}

		{{ Former::legend('League Settings') }}
		{{ Former::text('money')->value(Config::get('draft.league_defaults.money')) }}
		{{ Former::text('units')->value(Config::get('draft.league_defaults.units')) }}
		{{ Former::number("extra_weeks")->range(1, 12)->value(Config::get("draft.league_defaults.extra_weeks")) }}

		{{ Former::actions()->primary_submit('Submit')->reset('Reset') }}
	{{ Former::close() }}
@endsection