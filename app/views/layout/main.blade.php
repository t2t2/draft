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

	<div class="row">
		<div class="small-12 column">
			<hr/>

			<footer>
				Box Office Draft is brought to you by the free time of @<a href="http://twitter.com/t2t2">t2t2</a> -
				<i class="fa fa-heart-o"></i> <a href="https://github.com/t2t2/draft">opensource</a>
				<p></p>
			</footer>
		</div>
	</div>
@stop