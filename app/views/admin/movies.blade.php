@extends('layout.admin')

@section('layout.content')

		<table>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>boxmojo_id</th>
			<th>Release</th>
			<th>Created</th>
			<th>Updated</th>
			<th>Edit</th>
		</tr>
		@foreach($movies as $key => $movie)
		<tr>
			<td>{{$movie->id}}</td>
			<td>{{$movie->name}}</td>
			<td><a href="http://boxofficemojo.com/movies/?id={{$movie->boxmojo_id}}.htm" target="_blank">{{$movie->boxmojo_id}}</a></td>
			<td>{{$movie->release}}</td>
			<td>{{$movie->created_at}}</td>
			<td>{{$movie->updated_at}}</td>
			<td><a href="./addMovie?movie={{$movie->boxmojo_id}}">Edit</a></td>
    	</tr>
		@endforeach
		</table>
		{{$movies->links()}}
@endsection