@extends('layout.league')

@section('layout.content')
	<h2>League settings</h2>

	{{ Former::vertical_open()->route('league.admin.settings.store', ['league' => $league->slug])->rules($validation_rules)->populate($league) }}

		<fieldset>
			{{ Former::legend('League info') }}
			{{ Former::text('name') }}
			{{ Former::textarea('description')->rows(4) }}
			<div class="row">
				{{ Former::text('url')->addGroupClass('medium-10 column') }}
				{{ Former::checkbox('private')->unchecked_value(0)->addGroupClass('medium-2 column') }}
			</div>
		</fieldset>

		<fieldset>
			{{ Former::legend('League Settings') }}
			<div class="row">
				{{ Former::text('money')->value(Config::get('draft.league_defaults.money'))->addGroupClass('medium-4 columns') }}
				{{ Former::text('units')->value(Config::get('draft.league_defaults.units'))->addGroupClass('medium-4 columns') }}
				{{ Former::number("extra_weeks")->range(1, 12)->value(Config::get("draft.league_defaults.extra_weeks"))->addGroupClass('medium-4 columns') }}
			</div>
		</fieldset>

		{{ Former::actions()->submit('Submit')->reset('Reset', null, ['class' => 'secondary']) }}
	{{ Former::close() }}
@endsection