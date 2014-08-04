<!doctype html>
<html>
<head>

<title>@yield('title','HERoes In STEM')</title>

<link href="//netdna.bootstrapcdn.com/bootswatch/3.1.1/flatly/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="styles/heroesinstem.css" type="text/css">
@if(Session::get('flash_message'))
 	<div class="alert">
    	<button type="button" class="close" data-dismiss="alert">&times;</button>
    	<h2 class='flash-message bg-info'>{{ Session::get('flash_message') }}</h2>
    </div>
        
@endif

@if(Auth::check())
    <a href='/logout'>Log out {{ Auth::user()->username; }}</a>
@else 
    <a href='/signup'>Sign up</a> | <a href='/login'>Log in</a>
@endif

@yield('head')

</head>

<body>


@yield('content')

@yield('body')

</body>

</html>