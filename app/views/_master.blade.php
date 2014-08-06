<!doctype html>
<html>
<head>

<title>@yield('title','HERoes In STEM')</title>

<link href="//netdna.bootstrapcdn.com/bootswatch/3.1.1/flatly/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/styles/heroesinstem.css" type="text/css">
<div class ="header">
<br/>
<h1 >HERoes In STEM </h1>
<hr> 
@if(Auth::check())
    <a href='/'>Home</a>|<a href='/profile/{{Auth::user()->id}}'>Your Profile</a> | <a href='/list'>Library of HERoes</a> | <a href='/users'>Directory of Users</a> | <a href='/logout'>Log out {{ Auth::user()->username; }}</a>
@else 
    <a href='/signup'>Sign up</a> | <a href='/login'>Log in</a>
@endif
</div>
<br/><br/>

@if(Session::get('flash_message'))
    	<center> <h2 class='flash-message'>{{ Session::get('flash_message') }}</h2> </center> 
@endif
@yield('head')

</head>

<body>


@yield('content')

@yield('body')

</body>

</html>