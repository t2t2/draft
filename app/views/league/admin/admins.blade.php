@extends('layout.league')

@section('layout.content')

	<h2>League Admins</h2>

	<h3>Current admins:</h3>

	{{ Form::open(['route' => ['league.admin.admins.remove', 'league' => $league->slug]]) }}
		<ul class="large no-bullet">
			@foreach($league->admins as $admin)
				<li class="panel">
					{{ link_to_route('user.show', $admin->name, ['user' => $admin->username]) }}
					@if($admin->id != Auth::user()->id)
						<button class="tiny alert right" type="submit" name="user" value="{{ $admin->id }}"><i class="fa fa-remove"></i></button>
					@else
						<button class="tiny alert right" disabled title="It's you!"><i class="fa fa-remove"></i></button>
					@endif
				</li>
			@endforeach
		</ul>
	{{ Form::close() }}

	<h3>Add admins:</h3>

	{{ Former::vertical_open()->route('league.admin.admins.add', ['league' => $league->slug]) }}

		{{ Former::text('username') }}

		{{ Former::actions()->submit('Add admin') }}

	{{ Former::close() }}

@endsection