<div class="row">
	<div class="small-12 column">
		<h1>Leagues</h1>
	</div>
</div>

<div class="row">
	<div class="medium-3 column">
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
		<nav>
			<ul class="side-nav">
				@include('partials.nav', ['items' => $items])
			</ul>
		</nav>

	</div>
	<div class="medium-9 column">

		@yield('layout.content')

	</div>
</div>
