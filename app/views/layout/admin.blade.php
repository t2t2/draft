<div class="row">
	<div class="small-12 column">
		<h1>Admin</h1>
	</div>
</div>


<div class="row">
	<div class="medium-3 column">
<?php
// Navigation
$items = [
	['text' => 'Home', 'url' => route('admin.index'), 'active' => 'admin.index'],
];
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
