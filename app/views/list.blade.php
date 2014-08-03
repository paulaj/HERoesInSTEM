@extends('_master')

@section('title')
Welcome to HERoes In STEM
@stop

@section('content')
<h1>Welcome to HERoes In STEM</h1>

<a href='/profile'>View Profile</a> |
<a href='/hero/add'>+ Add new HERo</a> | <a href='/'>Back to Home</a>

<br><br>


{{ Form::open(array('url' => '/list', 'method' => 'GET')) }}

{{ Form::label('query','Search for a HERo:') }} &nbsp;
{{ Form::text('query') }} &nbsp;
{{ Form::submit('Search!') }}

{{ Form::close() }}

	<br><br>

@if(!empty(trim($query)))
	<p>You searched for <strong>{{{ $query }}}</strong></p>

	@if(count($heroes) == 0)
		<p>No matches found</p>
	@endif

@endif

@foreach($heroes as $name => $hero)

	<section>
	<h2>{{ $name }}</h2>
	<img class='picture' src='{{ $hero['picture'] }}'>
	

	<div class='description'>
	About: {{ $hero['description'] }} <br/>
	</div>

	<div class='tags'>
	Tags:
	@foreach($hero['tags'] as $tag)
	<em>{{ $tag }},</em>
	@endforeach
	</div>
	<a href='{{ $hero['wikiLink'] }}'> Go to Wikipedia Entry</a>
	

	</section>
	<br/>

@endforeach




@stop