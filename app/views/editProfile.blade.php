@extends('_master')

@section('title')
Welcome to HERoes In STEM
@stop

@section('content')
<h2>Edit Your Profile</h2>


<br><br>


{{ Form::open(array('url' => '/edit/profile', 'method' => 'POST')) }}

	<div class='form-group'>
	{{ Form::label('about', "Tell us about your self, your accomplishments, what you're proud of, and/or what you're good at!") }}
	<br/>
	{{ Form::textarea('about', Auth::user()->about) }}
	</div>

	{{ Form::submit('Save') }}

{{ Form::close() }}


@stop