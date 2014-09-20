@extends('layout.lite')

@section('content')
	<div class="contain-to-grid">
		@include('partials.navbar')
	</div>

	<br />

	@if(Notification::count())
		<div class="row">
			<div class="small-12 column">
				{{ Notification::showAll() }}
			</div>
		</div>
	@endif

	{{ $content }}

@stop