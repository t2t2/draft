@extends('layout.league')

@section('layout.content')

    <h2>Movies</h2>

    {{ Former::raw_open()->route('league.admin.movies.remove', ['league' => $league->slug]) }}

        <table>
            <thead>
                <tr>
                    <th>Movie</th>
                    <th class="small-3 medium-3 large-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($league->movies as $movie)
                    <tr>
                        <td>
                            <strong>{{ $movie->movie->name }}</strong><br />
                            <em>Release: {{ $movie->movie->release->toFormattedDateString() }}</em>
                        </td>
                        <td>
                            <ul class="button-group">
                            	<li><a href="#" class="button tiny"><i class="fa fa-exchange"></i></a></li>
                            	<li><button class="tiny alert" type="submit" name="movie" value="{{ $movie->id }}"><i class="fa fa-remove"></i></button></li>
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    {{ Former::close() }}


@endsection