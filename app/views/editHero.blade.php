@extends('_master')

@section('title')
Welcome to HERoes In STEM
@stop

@section('content')
<h1>Edit a HERo</h1>


<br><br>


{{ Form::open(array('url' => '/edit/hero/' . $heroid, 'method' => 'POST')) }}


	<h3>Update: {{ Hero::where('id','=',$heroid)->first()->name }}</h3>

	<div class='form-group'>
	{{ Form::label('name', 'Name:') }}
	{{ Form::text('name', Hero::where('id','=',$heroid)->first()->name) }}

	<div class='form-group'>
	{{ Form::label('description', 'Description:') }}<br>
	{{ Form::textarea('description', Hero::where('id','=',$heroid)->first()->description ) }}
	</div>

	<div class='form-group'>
	{{ Form::label('born', 'Year of Birth (YYYY):') }}
	{{ Form::text('born', Hero::where('id','=',$heroid)->first()->born ) }}
	</div>

	<div class='form-group'>
	{{ Form::label('photo', 'Picture URL:') }}
	{{ Form::text('photo', Hero::where('id','=',$heroid)->first()->photo ) }}
	</div>

	<div class='form-group'>
	{{ Form::label('more_info_link', 'Link to WikiPage:') }}
	{{ Form::text('more_info_link', Hero::where('id','=',$heroid)->first()->more_info_link) }}
	</div>

	<div class='form-group'>
		{{ Form::label('tags', 'Tags:') }} <br/>
		@foreach(Tag::all() as $tag)
			{{ Form::label('tags' . $tag->id, $tag-> name) }} 
			{{ Form::checkbox('tag[]', $tag->id, Hero::find($heroid)->tags->contains($tag->id) ) }}
			<br/>
		@endforeach
	</div>

	{{ Form::submit('Save') }}

{{ Form::close() }}


@stop