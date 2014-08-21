@extends('layout.lite')

@section('content')
	@include('partials.navbar')

	<div id="content" class="container">
        {{ Notification::showAll() }}

        {{ $content }}
	</div>

@stop