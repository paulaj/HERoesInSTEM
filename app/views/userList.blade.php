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

@if(!empty(trim($query)))
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
	
	
	</div> 
	<a href='/profile/{{ $user->id }}' > Go to {{ $user->username }}'s Profile </a> |<a href='/'>+ Admire this User</a>
	

	</section>
	<br/>

@endforeach




@stop