<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('home');
});

Route::get('/list', function()
{

	$heroList=  new HeroList(app_path().'/database/heroes.json');
	$query = Input::get('query');
	if($query){
		//search for query 
		$heroes = $heroList->search($query);
	}
	else{
		//get all heroes
		$heroes = $heroList->get_heroes();

	}
	return View::make('list')
		->with('heroes', $heroes)
		->with('query', $query);
});

Route::get('/profile', function()
{
	return View::make('profile');
});

Route::get('/hero/', function() {
	return View::make('hero');
});

 Route::get('/hero/edit/{hero}', function() {

});

  Route::get('hero/add/', function() {
  	return View::make('addHero');
});

  Route::post('hero/add/', function() {
  	return View::make('hero');
});