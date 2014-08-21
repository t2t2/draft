@foreach($items as $item)
	<li{{ str_is($item['active'], Route::currentRouteName()) ? ' class="active"' : '' }}>
		<a href="{{ $item['url'] }}">{{ $item['text'] }}</a>
	</li>
@endforeach