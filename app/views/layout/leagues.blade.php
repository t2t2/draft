<h1>Leagues</h1>

<div class="row">
	<div class="col-md-3">
		<!-- Navigation -->
<?php
$items = [
	['text' => 'Leagues Index', 'url' => route('league.index'), 'active' => 'league.index'],
];
if(Auth::check()) {
	$items[] = ['text' => 'Create New League', 'url' => route('league.create'), 'active' => 'league.create'];
	$items[] = ['text' => 'My Leagues', 'url' => route('league.mine'), 'active' => 'league.mine'];
	$items[] = 'divider';
}
?>
		<ul class="nav">
			@include('partials.nav', ['items' => $items])
		</ul>

	</div>
	<div class="col-md-9">
		@yield('leagues.content')
	</div>
</div>
