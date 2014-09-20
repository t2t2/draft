<div class="row">
	<div class="small-12 column">
		{{ Former::vertical_open()->route('auth.register')->rules($validation_rules) }}
			<div class="row">
				{{ Former::text('username')->addGroupClass('medium-4 column') }}
				{{ Former::text('diplayname')->addGroupClass('medium-8 column') }}
			</div>
			{{ Former::uneditable('email')->help('registration.help.email', ['class' => 'alert-box info'])->forceValue($email) }}
			{{ Former::actions()
				->small_submit('Register')
				->small_reset(null, null, ['class' => 'secondary'])
				->small_link('Cancel Registration', '#', ['data-persona' => 'logout', 'class' => 'secondary'])
			}}

		{{ Former::close() }}
	</div>
</div>

