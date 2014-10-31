<li class="league-info">
	<h3><a href="{{ route('league.show', ['league' => $league->slug]) }}">{{ $league->name }}</a></h3>
	<p>{{ nl2br(e(str_limit($league->description, 200))) }}</p>
</li>
