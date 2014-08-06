@extends('_master')

@section('title')
Welcome to HERoes In STEM
@stop

@section('content')
<h2 class= "yellow"> User Directory </h2>

<br><br>

{{ Form::open(array('url' => '/users', 'method' => 'GET')) }}

{{ Form::label('query','Search for another User:') }} &nbsp;
{{ Form::text('query') }} &nbsp;
{{ Form::submit('Search!') }}

{{ Form::close() }}
	<br><br>

<h3 class= "yellow">Browse User Directory</h3>

<?php $trimmedQuery = trim($query); ?>
@if(!empty($trimmedQuery))
	<p>You searched for <strong>{{{ $query }}}</strong></p>

	@if(count($users) == 0)
		<p>No matches found</p>
	@endif

@endif

@foreach($users as $user)

	<section>
	<h4>{{ $user->username }}</h4>
	About:
	<div class='teal'>
	 {{ $user->about }} <br/>
	</div>

	<div class='tags'>
	{{ $user->username }}'s Interests:
	@foreach($user->tags as $tag)
		<em class='teal'>{{ $tag->name }} </em>
	@endforeach
	
	
	</div> 
	<a href='/profile/{{ $user->id }}' > Go to {{ $user->username }}'s Profile </a> | {{ Form::open(array('url' => '/add/profile/admire/' . $user->id, 'method' => 'POST')) }}
	 	<input type="submit" value="Admire this User."> 
	 {{ Form::close() }}
	

	</section>
	<br/>

@endforeach




@stop