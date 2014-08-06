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


Route::get('/signup',
    array(
        'before' => 'guest',
        function() {
            return View::make('signup');
        }
    )
);

Route::post('/signup', 
    array(
        'before' => 'csrf', 
        function() {

            $user = new User;
            $user->email    = Input::get('email');
            $user->password = Hash::make(Input::get('password'));
            $user->username    = Input::get('username');

            # Try to add the user 
            try {
                $user->save();
            }
            # Fail
            catch (Exception $e) {
                return Redirect::to('/signup')->with('flash_message', 'Sign up failed; please try again.')->withInput();
            }

            # Log the user in
            Auth::login($user);

            return Redirect::to('/list')->with('flash_message', 'Welcome to HERoes in STEM!');

        }
    )
);

Route::get('/login',
    array(
        'before' => 'guest',
        function() {
            return View::make('login');
        }
    )
);

Route::post('/login', 
    array(
        'before' => 'csrf', 
        function() {

            $credentials = Input::only('email', 'password');

            if (Auth::attempt($credentials, $remember = true)) {
                return Redirect::intended('/')->with('flash_message', 'Welcome Back, ' . Auth::user()->username .  "!");
            }
            else {
                return Redirect::to('/login')->with('flash_message', 'Log in failed; please try again.');
            }

            return Redirect::to('login');
        }
    )
);

Route::get('/logout', function() {

    # Log out
    Auth::logout();

    # Send them to the homepage
    return Redirect::to('/login');

});

Route::get('/list',
	array(
		'before' => 'auth',
		function() {
			// $heroList=  new HeroList(app_path().'/database/heroes.json');
			$query = Input::get('query');
			if($query){
				//search for query 
				//$heroes = $heroList->search($query);
					$heroes = Hero::where('name', 'LIKE', "%$query%")
						->orWhereHas('tags', function($q) use($query) {
							$q->where('name', 'LIKE', "%$query%");
						})
						->get();
			}
			else{
				//get all heroes
				//$heroes = $heroList->get_heroes();
				$heroes = Hero::all();

			}
			return View::make('list')
				->with('heroes', $heroes)
				->with('query', $query);
		}
	)
);

Route::get('/users',
	array(
		'before' => 'auth',
		function() {
			$query = Input::get('query');
			if($query){
					$users = User::where('username', 'LIKE', "%$query%")
						->orWhereHas('tags', function($q) use($query) {
							$q->where('name', 'LIKE', "%$query%");
						})
						->get();
			}
			else{
				$users = User::all();

			}
			return View::make('userList')
				->with('users', $users)
				->with('query', $query);
		}
	)
);


Route::get('/profile/{userid}',
	array(
		'before' => 'auth',
		function($userid) {
			$user = User::where('id','=',$userid)->first();
			$likes = Like::where('likes_id','=',$user->id)->get();
			$likedby = Like::where('liked_id','=',$user->id)->count();
			return View::make('profile')
				->with('userid', $userid)
				->with('thisuser', $user)
				->with('likes', $likes)
				->with('likedby', $likedby);
		}
	)
);

Route::get('/edit/profile',
	array(
		'before' => 'auth',
		function() {
			return View::make('editProfile');
		}
	)
);

Route::post('/edit/profile',
	array(
		'before' => 'auth',
		function() {
			$user = User::find(Auth::user()->id);

			$user->about = Input::get('about');
  			$tag= new Tag;
  			$tag->name = 'math';
			$tag->save();
			$user->save();
			$user->tags()->attach($tag);
			$user->save();

		  	return Redirect::to('/profile/' . Auth::user()->id);
		}
	)
);

 Route::get('/edit/hero/{heroid}',
	array(
		'before' => 'auth',
		function($heroid) {
			return View::make('editHero')
				->with('heroid', $heroid);
		}
	)
 );

 Route::post('/edit/hero/{heroid}',
	array(
		'before' => 'auth',
		function($heroid) {
			$hero = Hero::find($heroid);

			$hero->name = Input::get('name');
			$hero->description = Input::get('description');
			$hero->born = Input::get('born');
			$hero->photo = Input::get('photo');
			$hero->more_info_link = Input::get('more_info_link');


		

			$hero->save();

		  	return Redirect::to('/list');
		}
	)
);

  Route::get('/add/hero',
  	array(
		'before' => 'auth',
		function() {
  			return View::make('addHero');
  		}
  	)
);

  Route::post('/add/hero', function() {

	$hero = new Hero();

	$hero->name = Input::get('name');
	$hero->description = Input::get('description');
	$hero->born = Input::get('born');
	$hero->photo = Input::get('photo');
	$hero->more_info_link = Input::get('more_info_link');
	$hero->save();

  	return Redirect::to('/list')
  		->with('flash_message', 'Added Hero!');
});
//add hero to profile
 Route::post('/add/profile/hero/{heroid}', function($heroid) {

	$hero = Hero::find($heroid);

	$user = User::find(Auth::user()->id);

	if ($user->heroes->has('id', $hero->id)){
		return Redirect::to('/profile/' . Auth::user()->id)
  			->with('flash_message', 'Already Added Hero!');
	}
	else{
		$user -> heroes()->attach($hero);
		return Redirect::to('/profile/' . Auth::user()->id)
		->with('flash_message', 'Added new personal Hero!');
	}
  	
  		
});

//admire user
 Route::post('/add/profile/admire/{userid}', function($userid) {

	$admired = User::find($userid);

	$user = User::find(Auth::user()->id);

	 if (Like::where('liked_id','=',$admired->id)->where('likes_id','=',$user->id)->exists()){
	 	return Redirect::to('/profile/' . Auth::user()->id)
   			->with('flash_message', 'You already admire this user!');
	 }
	else{
		$like= new Like();
		$like->likes_id = $user-> id;
		$like->liked_id = $admired-> id;
		$like->save();
		return Redirect::to('/profile/' . Auth::user()->id)
		->with('flash_message', 'Admired new user!');
	}
  	
  		
});




# Quickly seed from lecture
Route::get('/seed', function() {

	# Clear the tables to a blank slate
	DB::statement('SET FOREIGN_KEY_CHECKS=0');
	DB::statement('TRUNCATE heroes');
	DB::statement('TRUNCATE hero_user');
	DB::statement('TRUNCATE hero_tag');
	DB::statement('TRUNCATE tags');

	$tag_math = new Tag;
	$tag_math->name = 'math';
	$tag_math->save();
	$tag_comp = new Tag;
	$tag_comp->name = 'computers';
	$tag_comp->save();
	$tag_astro = new Tag;
	$tag_astro->name = 'astronomy';
	$tag_astro->save();

	$hero = new Hero();
	$hero->name = 'Ada Lovelace';
	$hero->description = "Augusta Ada King, Countess of Lovelace (10 December 1815 – 27 November 1852), born Augusta Ada Byron and now commonly known as Ada Lovelace, was an English mathematician and writer chiefly known for her work on Charles Babbage's early mechanical general-purpose computer, the Analytical Engine. 
	Her notes on the engine include what is recognised as the first algorithm intended to be carried out by a machine. Because of this, she is often described as the world's first computer programmer.Lovelace was born 10 December 1815 as the only child of the poet Lord Byron and his wife Anne Isabella Byron. All Byron's other children were born out of wedlock to other women. Byron separated from his wife a month after Ada was born and left England forever four months later, eventually dying of disease in the Greek War of Independence when Ada was eight years old. Ada's mother remained bitter at Lord Byron and promoted Ada's interest in mathematics and logic in an effort to prevent her from developing what she saw as the insanity seen in her father, but Ada remained interested in him despite this (and was, upon her eventual death, buried next to him at her request). 
	Ada described her approach as 'poetical science' and herself as an 'Analyst (& Metaphysician)'.As a young adult, her mathematical talents led her to an ongoing working relationship and friendship with fellow British mathematician Charles Babbage, and in particular Babbage's work on the Analytical Engine. Between 1842 and 1843, she translated an article by Italian military engineer Luigi Menabrea on the engine, which she supplemented with an elaborate set of notes of her own, simply called Notes. These notes contain what many consider to be the first computer program—that is, an algorithm designed to be carried out by a machine. Lovelace's notes are important in the early history of computers. She also developed a vision on the capability of computers to go beyond mere calculating or number-crunching while others, including Babbage himself, focused only on those capabilities. Her mind-set of 'poetical science' led her to ask basic questions about the Analytical Engine (as shown in her notes) examining how individuals and society relate to technology as a collaborative tool.";
	$hero->born = 1815;
	$hero->photo = 'http://upload.wikimedia.org/wikipedia/commons/thumb/a/a4/Ada_Lovelace_portrait.jpg/640px-Ada_Lovelace_portrait.jpg';
	$hero->more_info_link = 'http://en.wikipedia.org/wiki/Ada_Lovelace';
	$hero->save();
	$hero->tags()->attach($tag_math);
	$hero->tags()->attach($tag_comp);

	$hero = new Hero();

	$hero->name = 'Henrietta Swan Leavitt';
	$hero->description = "Henrietta Swan Leavitt (July 4, 1868 – December 12, 1921) was an American astronomer. A graduate of Radcliffe College, Leavitt started working at the Harvard College Observatory as a 'computer' in 1893, examining photographic plates in order to measure and catalog the brightness of stars. Leavitt discovered the relation between the luminosity and the period of Cepheid variable stars. Though she received little recognition in her lifetime, it was her discovery that first allowed astronomers to measure the distance between the Earth and faraway galaxies. After Leavitt's death, Edwin Hubble used the luminosity-period relation for Cepheids to determine that the Milky Way is not the only galaxy in the observable universe, and that the universe is expanding (see Hubble's law).";
	$hero->born = 1868;
	$hero->photo = 'http://upload.wikimedia.org/wikipedia/commons/3/3b/Leavitt_aavso.jpg';
	$hero->more_info_link = 'http://en.wikipedia.org/wiki/Henrietta_Swan_Leavitt';
	$hero->save();
	$hero->tags()->attach($tag_comp);
	$hero->tags()->attach($tag_astro);

	
	return Redirect::to('/list');



});




