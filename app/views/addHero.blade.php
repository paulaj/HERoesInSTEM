@extends('_master')

@section('title')
Welcome to HERoes In STEM
@stop

@section('content')
<h1>Add a new HERo</h1>

<a href='/profile'>View Profile</a> |
<a href='/list'>View all HERoes</a> | <a href='/'>Back to Home</a>

<br><br>


{{ Form::open(array('url' => '/hero/add', 'method' => 'POST')) }}

{{ Form::label('query','Add a new HERo:') }} &nbsp;
{{ Form::text('query') }} &nbsp;
{{ Form::submit('Add HERo!') }}

{{ Form::close() }}


@stop