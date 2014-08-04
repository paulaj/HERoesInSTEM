@extends('_master')

@section('title')
Welcome to HERoes In STEM
@stop

@section('content')
<h1>Edit a HERo</h1>

<a href='/profile'>View Profile</a> |
<a href='/list'>View all HERoes</a> | <a href='/'>Back to Home</a>

<br><br>


{{ Form::open(array('url' => '/hero/add', 'method' => 'POST')) }}

	Name: {{ Form::text('name') }} <br>
	Desciption: {{ Form::text('description') }} <br>
	Year of Birth (YYYY): {{ Form::text('born') }} <br>
	Picture URL: {{ Form::text('photo') }} <br>
	Link to WikiPage: {{ Form::text('more_info_link') }} <br>

	{{ Form::submit('Add this new HERo!') }}

{{ Form::close() }}


@stop