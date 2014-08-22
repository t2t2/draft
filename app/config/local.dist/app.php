<?php
return [
	'debug' => true,
	'url' => 'http://draft.dev',
	'key' => 'Replace this with some random crap',
	'providers' => append_config([
		'Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider',
		'Barryvdh\Debugbar\ServiceProvider',
	]),
];
