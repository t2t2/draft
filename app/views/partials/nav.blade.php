@foreach($items as $item)
	@if($item == 'divider')
		<li class="divider"></li>
	@else
		<li{{ (isset($item['active']) && str_is($item['active'], Route::currentRouteName())) ? ' class="active"' : '' }}>
			@if(isset($item['url']))
				<a href="{{ $item['url'] }}">{{ $item['text'] }}</a>
			@else
				{{ $item['text'] }}
			@endif
		</li>
	@endif
@endforeach