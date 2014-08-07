@extends('_master')

@section('title')
Welcome to HERoes In STEM
@stop

@section('content')
<h1>Add a brand new HERo</h1>

<br><br>


{{ Form::open(array('url' => '/add/hero', 'method' => 'POST')) }}

	<div class='form-group'>
	{{ Form::label('name', 'Name:') }}
	{{ Form::text('name') }} <br>
	<div class='form-group'>
	{{ Form::label('description', 'Description:') }}<br>
	 {{ Form::textarea('description') }} <br>
	<div class='form-group'>
	{{ Form::label('born', 'Year of Birth (YYYY):') }}
	{{ Form::text('born') }} <br>
	<div class='form-group'>
	{{ Form::label('photo', 'Picture URL:') }}
	{{ Form::text('photo') }} <br>
	<div class='form-group'>
	{{ Form::label('more_info_link', 'Link to WikiPage:') }}
	{{ Form::text('more_info_link') }} <br>
	<div class='form-group'>

	<div class='form-group'>
		{{ Form::label('tags', 'Tags:') }} <br/>
		@foreach(Tag::all() as $tag)
			{{ Form::label('tags' . $tag->id, $tag-> name) }} 
			{{ Form::checkbox('tag[]', $tag->id, false ) }}
			<br/>
		@endforeach
	</div>

	{{ Form::submit('Add this new HERo!') }}

{{ Form::close() }}


@stop