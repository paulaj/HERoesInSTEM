@extends('_master')

@section('title')
Welcome to HERoes In STEM
@stop

@section('content')
<h2 class= "yellow">Library of HERoes</h2>

<br><br>

{{ Form::open(array('url' => '/list', 'method' => 'GET')) }}

{{ Form::label('query','Search for a HERo:') }} &nbsp;
{{ Form::text('query') }} &nbsp;
{{ Form::submit('Search!') }}

{{ Form::close() }}
Don't see a hero you like? <a href='/add/hero'>Add them to the list here!</a>
	<br><br>

<h3 class= "yellow">Browse HERoes</h3>

@if(!empty(trim($query)))
	<p>You searched for <strong>{{{ $query }}}</strong></p>

	@if(count($heroes) == 0)
		<p>No matches found</p>
	@endif

@endif

@foreach($heroes as $name => $hero)

	<section>
	<h4 class= "teal">{{ $hero['name'] }}</h4>
	<img class='picture' src='{{ $hero['photo'] }}'>
	

	<div class='description'>
	{{ $hero['description'] }} <br/>
	</div>

	<div class='tags'>
	Tags:
	@foreach($hero->tags as $tag)
		<em>{{ $tag->name }}, </em>
	@endforeach
	
	</div> 
	<a href='{{ $hero['more_info_link'] }}'> Go to Wikipedia Entry</a> |
	 {{ Form::open(array('url' => '/add/profile/hero/' . $hero->id, 'method' => 'POST')) }}
	 	<input type="submit" value="+ Add this hero to your HERoes."> 
	 {{ Form::close() }}
	 |<a href='/edit/hero/{{$hero["id"]}}'> Edit hero</a>
	

	</section>
	<br/>

@endforeach




@stop