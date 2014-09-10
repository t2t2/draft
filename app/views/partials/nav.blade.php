@foreach($items as $item)
	@if($item == 'divider')
		<li class="divider"></li>
	@else
		<li{{ str_is($item['active'], Route::currentRouteName()) ? ' class="active"' : '' }}>
			<a href="{{ $item['url'] }}">{{ $item['text'] }}</a>
		</li>
	@endif
@endforeach