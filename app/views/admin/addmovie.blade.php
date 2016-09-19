@extends('layout.admin')

@section('layout.content')


	{{ Form::open(['route' => ['admin.addMovie']]) }}
		Add A New Movie via the boxofficemojo id:
		{{ Form::text('movie',$info->mojo_id) }}
		<button type="submit">Check Movie</button>
    {{Form::close()}}
	<?php if($info->found) {?>
	{{ Form::open(['route' => ['admin.confirmMovie']]) }}
	Movie Information:
	<table>
		<tr><td>Title</td><td><?=$info->title?></td></tr>
		<tr><td>Release Date</td><td><?=$info->release_date?></td></tr>
		<tr><td>Boxmojo Id</td><td><?=$info->mojo_id?></td></tr>
		</tr>
	</table>
	{{Form::hidden('title',$info->title)}}
	{{Form::hidden('release_date',$info->release_date)}}
	{{Form::hidden('mojo_id',$info->mojo_id)}}
	Existing Gross:
	<table>
		<tr><th>Date</th><th>Gross</th><tr>
		<?php foreach($info->grosses as $gross) {  ?>
		<tr><td><?=$gross['release_date']?></td><td><?=number_format($gross['domestic_total'])?></td></tr> 
		<?php } ?>
	</table>
	{{Form::hidden('grosses',serialize($info->grosses))}}
	<button type="submit">Confirm Movie</button>
	{{Form::close()}}
	<?php } ?>
	
@endsection