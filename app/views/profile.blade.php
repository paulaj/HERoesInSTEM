@extends('_master')

@section('title')
Welcome to HERoes In STEM
@stop

@section('content')
<div class="row">
	@if($userid == Auth::user()->id)
		<h1 class="teal profile-heading">Your Profile </h1> <span class="help-block"> <a href='/edit/profile'>edit profile</a> </span>
	@else
	    <h1 class="text-uppercase">{{$thisuser->username}}'s Profile</h1>
	@endif
</div>
<div class="row">
	@if($userid == Auth::user()->id)
		
		<div class="panel panel-default">
  			<div class="panel-heading">
    			<h3 class="panel-title">About You</h3>
  			</div>
  			<div class="panel-body">
    			<p class="description"> {{$thisuser->about}} </p>
    			<h5>You have {{$likedby}} admirers </h5>
  			</div>
		</div>


		<div class="panel panel-default">
  			<div class="panel-heading">
    			<h3 class="panel-title">Interests</h3>
  			</div>
  			<div class="panel-body">
    			@foreach(Auth::user()->tags as $tag)
				<h5>{{ $tag->name }} </h5>
				@endforeach
  			</div>
		</div>

		<div class="panel panel-default">
  			<div class="panel-heading">
    			<h3 class="panel-title">Personal Heroes</h3>
  			</div>
  			<div class="panel-body">
    			@foreach(Auth::user()->heroes as $hero)
				<h5>{{ $hero->name }} </h5>
				@endforeach
  			</div>
		</div>

		<div class="panel panel-default teal">
  			<div class="panel-heading ">
    			<h3 class="panel-title">People You Admire</h3>
  			</div>
  			<div class="panel-body">
    			@foreach($likes as $liked)
				<h5> {{ User::find($liked->liked_id)->username }} </h5>
				@endforeach	
  			</div>
		</div>

		
	@else
		<div class="panel panel-default">
  			<div class="panel-heading">
    			<h3 class="panel-title">About {{$thisuser->username}}</h3>
  			</div>
  			<div class="panel-body">
    			<p class="description"> {{$thisuser->about}} </p>
				<h5>{{$thisuser->username}} has {{$likedby}} admirers </h5>
  			</div>
		</div>


		<div class="panel panel-default">
  			<div class="panel-heading">
    			<h3 class="panel-title">Interests</h3>
  			</div>
  			<div class="panel-body">
    			@foreach($thisuser->tags as $tag)
				<h5>{{ $tag->name }} </h5>
				@endforeach
  			</div>
		</div>

		<div class="panel panel-default">
  			<div class="panel-heading">
    			<h3 class="panel-title">Personal Heroes</h3>
  			</div>
  			<div class="panel-body">
    			@foreach($thisuser->heroes as $hero)
				<h5>{{ $hero->name }} </h5>
				@endforeach	
  			</div>
		</div>

		<div class="panel panel-default teal">
  			<div class="panel-heading ">
    			<h3 class="panel-title">People You Admire</h3>
  			</div>
  			<div class="panel-body">
    			@foreach($likes as $liked)
				<h5> {{ User::find($liked->liked_id)->username }} </h5>
				@endforeach
  			</div>
		</div>



	@endif


</div>
	

@stop