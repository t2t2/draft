<?php
$navs = [
	['text' => 'Home', 'url' => route('admin.index'), 'active' => 'admin.index'],
];
?>

<h1>Admin</h1>
<div class="row">
	<div class="col-md-3">
		<ul class="nav">
			@include('partials.nav', ['items' => $navs])
		</ul>
	</div>
	<div class="col-md-9">

		<div class="row">
			<div class="col-md-3">
				<div class="well text-center">
					<div class="h1">{{ $stats['users'] }}</div>
					<p>Users</p>
				</div>
			</div>
			<div class="col-md-3">
				<div class="well text-center">
					<div class="h1">{{ $stats['leagues'] }}</div>
					<p>Leagues</p>
				</div>
			</div>
			<div class="col-md-3">
				<div class="well text-center">
					<div class="h1">{{ $stats['movies'] }}</div>
					<p>Movies</p>
				</div>
			</div>
		</div>

	</div>
</div>