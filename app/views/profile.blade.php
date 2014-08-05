@extends('_master')

@section('title')
Welcome to HERoes In STEM
@stop

@section('content')
	@if($userid == Auth::user()->id)
		<h1>Your Profile</h1> <span class="help-block"> <a href='/edit/profile'>edit profile</a> </span>
	@else
	    <h1 class="text-uppercase">{{User::where('id','=',$userid)->first()->username}}'s Profile</h1>
	@endif

	<br><br>
	
	@if($userid == Auth::user()->id)
		<h3>About You </h3>
		<p class="description"> {{Auth::user()->about}} </p>
		<h5>You have # admirers </h5>
		<br/><br/>
		<h3>Personal Heroes </h3>	
		<h3>People You Admire </h3> 
	@else
		<h3>About {{User::where('id','=',$userid)->first()->username}} </h3>
		<p class="description"> {{User::where('id','=',$userid)->first()->about}} </p>
		<h5>{{User::where('id','=',$userid)->first()->username}} has # admirers </h5>
		<br/><br/>
		<h3>Personal Heroes </h3>	
		<h3>People {{User::where('id','=',$userid)->first()->username}} Admires </h3>
	@endif

	

@stop