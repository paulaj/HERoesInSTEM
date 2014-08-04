@extends('_master')

@section('title')
Welcome to HERoes In STEM
@stop

@section('content')

<h1>Sign up</h1>

{{ Form::open(array('url' => '/signup')) }}

    Desired Username:<br>
    {{ Form::text('username') }}<br><br>

    Your Email Adress:<br>
    {{ Form::text('email') }}<br><br>

    Password:<br>
    {{ Form::password('password') }}<br><br>

    {{ Form::submit('Submit') }}

{{ Form::close() }}

@stop