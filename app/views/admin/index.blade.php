@extends('layout.admin')

@section('layout.content')

	<ul class="medium-block-grid-4">
		@foreach($stats as $key => $stat)
			<li>
				<div class="panel text-center">
					<h2>{{ $stat }}</h2>
					<p>{{ $key }}</p>
				</div>
			</li>
		@endforeach
	</ul>

@endsection