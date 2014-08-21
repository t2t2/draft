{{ Former::open()->route('auth.register')->rules($validation_rules) }}

	{{ Former::text('username') }}
	{{ Former::text('diplayname') }}
	{{ Former::uneditable('email')->help('registration.help.email')->forceValue($email) }}
	{{ Former::actions()->primary_submit('Register')->reset()->link('Cancel Registration', '#', ['data-persona' => 'logout']) }}

{{ Former::close() }}