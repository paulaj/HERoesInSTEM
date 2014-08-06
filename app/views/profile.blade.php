@extends('_master')

@section('title')
Welcome to HERoes In STEM
@stop

@section('content')
	@if($userid == Auth::user()->id)
		<h1>Your Profile </h1> <span class="help-block"> <a href='/edit/profile'>edit profile</a> </span>
	@else
	    <h1 class="text-uppercase">{{$thisuser->username}}'s Profile</h1>
	@endif

	
	@if($userid == Auth::user()->id)
		<h3>About You </h3>
		<p class="description"> {{$thisuser->about}} </p>
		<h5>You have {{$likedby}} admirers </h5>

		<br/><br/>

		<h3>Interests </h3>	
		@foreach(Auth::user()->tags as $tag)
			<h5>{{ $tag->name }} </h5>
		@endforeach

		<h3>Personal Heroes </h3>	

		@foreach(Auth::user()->heroes as $hero)
			<h5>{{ $hero->name }} </h5>
		@endforeach

		
		
		<h3>People You Admire </h3> 
		@foreach($likes as $liked)
			<h5> {{ User::find($liked->liked_id)->username }} </h5>
		@endforeach	
	@else
		<h3>About {{$thisuser->username}} </h3>
		<p class="description"> {{$thisuser->about}} </p>
		<h5>{{$thisuser->username}} has {{$likedby}} admirers </h5>
		<br/><br/>
		<h3>Personal Heroes </h3>
		@foreach($thisuser->heroes as $hero)
			<h5>{{ $hero->name }} </h5>
		@endforeach	
		<h3>People {{$thisuser->username}} Admires </h3>
		@foreach($likes as $liked)
			<h5> {{ User::find($liked->liked_id)->username }} </h5>
		@endforeach	
	@endif

	

@stop