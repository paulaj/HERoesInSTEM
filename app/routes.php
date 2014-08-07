<?php

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
			$query = Input::get('query');
			if($query){
				//search for query 
					$heroes = Hero::where('name', 'LIKE', "%$query%")
						->orWhereHas('tags', function($q) use($query) {
							$q->where('name', 'LIKE', "%$query%");
						})
						->get();
			}
			else{
				//get all heroes
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
			$user->save();
			
			$all_tags=Tag::all();

			$checked=Input::get('tag');

			//if something comes back
			if(is_array($checked)){
				foreach ($all_tags as $tag) {
					if(in_array($tag->id,$checked)) {
						if(!$user->tags->contains($tag->id)){
							$newtag=Tag::find($tag->id);
							$user->tags()->attach($newtag);
							$user->save();
						}	
					}
					else{
						if($user->tags->contains($tag->id)){
							$user->tags()->detach($tag->id);
							$user->save();
						}
					}
				}
			}
			//if nothing comes back
			else{
				foreach ($all_tags as $tag) {
					if($user->tags->contains($tag->id)){
						$user->tags()->detach($tag->id);
						$user->save();
					}	
				}
			}

			return Redirect::to('/profile/' . Auth::user()->id)
		  					->with('flash_message', 'Your Profile has been updated');	  	
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
			$all_tags=Tag::all();

			$checked=Input::get('tag');
			if(is_array($checked)){
				foreach ($all_tags as $tag) {
					if(in_array($tag->id,$checked)) {
						if(!$hero->tags->contains($tag->id)){
							$newtag=Tag::find($tag->id);
							$hero->tags()->attach($newtag);
							$hero->save();

						}	
					}
					else{
						if($hero->tags->contains($tag->id)){
							$hero->tags()->detach($tag->id);
							$hero->save();
						}
					}
				}
			}
			//if nothing comes back from checked
			else{
				foreach ($all_tags as $tag) {
					if($hero->tags->contains($tag->id)){
						$hero->tags()->detach($tag->id);
						$hero->save();
					}	
				}
			}
			return Redirect::to('/list')
			 				->with('flash_message', 'Hero has been updated!');	
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

	$checked=Input::get('tag');
	if(is_array($checked)){
		foreach ($checked as $tag) {
			$newtag=Tag::find($tag);
			$hero->tags()->attach($newtag);
			$hero->save();
		}
	}
	

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
	$tag_physics = new Tag;
	$tag_physics->name = 'physics';
	$tag_physics->save();

	$hero = new Hero();
	$hero->name = 'Ada Lovelace';
	$hero->description = "'Augusta Ada King, Countess of Lovelace (10 December 1815 – 27 November 1852), born Augusta Ada Byron and now commonly known as Ada Lovelace, was an English mathematician and writer chiefly known for her work on Charles Babbage's early mechanical general-purpose computer, the Analytical Engine. 
	Her notes on the engine include what is recognised as the first algorithm intended to be carried out by a machine. Because of this, she is often described as the world's first computer programmer.' From Wikipedia ";
	$hero->born = 1815;
	$hero->photo = 'http://upload.wikimedia.org/wikipedia/commons/thumb/a/a4/Ada_Lovelace_portrait.jpg/640px-Ada_Lovelace_portrait.jpg';
	$hero->more_info_link = 'http://en.wikipedia.org/wiki/Ada_Lovelace';
	$hero->save();
	$hero->tags()->attach($tag_math);
	$hero->tags()->attach($tag_comp);

	$hero = new Hero();

	$hero->name = 'Henrietta Swan Leavitt';
	$hero->description = "'Henrietta Swan Leavitt (July 4, 1868 – December 12, 1921) was an American astronomer. A graduate of Radcliffe College, Leavitt started working at the Harvard College Observatory as a 'computer' in 1893, examining photographic plates in order to measure and catalog the brightness of stars. Leavitt discovered the relation between the luminosity and the period of Cepheid variable stars. 
	Though she received little recognition in her lifetime, it was her discovery that first allowed astronomers to measure the distance between the Earth and faraway galaxies. After Leavitt's death, Edwin Hubble used the luminosity-period relation for Cepheids to determine that the Milky Way is not the only galaxy in the observable universe, and that the universe is expanding (see Hubble's law).' From Wikipedia";
	$hero->born = 1868;
	$hero->photo = 'http://upload.wikimedia.org/wikipedia/commons/3/3b/Leavitt_aavso.jpg';
	$hero->more_info_link = 'http://en.wikipedia.org/wiki/Henrietta_Swan_Leavitt';
	$hero->save();
	$hero->tags()->attach($tag_comp);
	$hero->tags()->attach($tag_astro);

	$hero = new Hero();

	$hero->name = 'Joycelyn Bell Burnell';
	$hero->description = "'Dame (Susan) Jocelyn Bell Burnell, DBE, FRS, FRAS (born 15 July 1943) is a Northern Irish astrophysicist. As a postgraduate student, she discovered the first radio pulsars while studying and advised by her thesis supervisor Antony Hewish, for which Hewish shared the Nobel Prize in Physics with Martin Ryle, while Bell Burnell was excluded, despite having observed the pulsars.
	Bell Burnell was President of the Royal Astronomical Society from 2002 to 2004, president of the Institute of Physics from October 2008 until October 2010, and was interim president following the death of her successor, Marshall Stoneham, in early 2011. ' From Wikipedia";
	$hero->born = 1943;
	$hero->photo = 'http://upload.wikimedia.org/wikipedia/commons/9/9d/Jocelyn_Bell_Burnell.jpg';
	$hero->more_info_link = 'http://en.wikipedia.org/wiki/Jocelyn_Bell_Burnell';
	$hero->save();
	$hero->tags()->attach($tag_astro);
	$hero->tags()->attach($tag_physics);

	$hero = new Hero();
	$hero->name = "Grete Hermann";
	$hero->born = 1901;
	$hero->description = "'Grete (Henry-)Hermann (March 2, 1901 - April 15, 1984) was a German mathematician and philosopher noted for her work in mathematics, physics, philosophy and education. 
	She is noted for her early philosophical work on the foundations of quantum mechanics, and is now known most of all for an early, but long-ignored refutation of a no-hidden-variable theorem by John von Neumann. The disputed theorem and the fact that Hermann's critique of this theorem remained nearly unknown for decades are considered to have had a strong influence on the development of quantum mechanics.' From Wikipedia ";
	$hero->photo ="http://upload.wikimedia.org/wikipedia/en/e/ea/Grete_Hermann.jpg";
	$hero->more_info_link="http://en.wikipedia.org/wiki/Grete_Hermann";
	$hero->save();
	$hero->tags()->attach($tag_math);

	$hero = new Hero();
	$hero->name = "Hedy Lamarr";
	$hero->born = 1914;
	$hero->description = "'Hedy Lamarr (1914–2000), was an actress and the co-inventor of an early form of spread-spectrum broadcasting.' From Wikipedia ";
	$hero->photo ="http://upload.wikimedia.org/wikipedia/commons/thumb/d/de/Hedy_Lamarr-Algiers-38.JPG/640px-Hedy_Lamarr-Algiers-38.JPG";
	$hero->more_info_link ="http://en.wikipedia.org/wiki/Hedy_Lamarr";
	$hero->save();
	$hero->tags()->attach($tag_comp);

	$hero = new Hero();
	$hero->name = "Gertrude Blanch";
	$hero->born = 1897;
	$hero->description = "'Gertrude Blanch led the Mathematical Tables Project group throughout the war. It operated as a major computing office for the US government and did calculations for the Office for Scientific Research and Development, the Army, the Navy, the Manhattan Project and other institutions.' From Wikipedia  Photo from computerhope.com";
	$hero->photo ="http://www.computerhope.com/people/pictures/gertrude_blanch.jpg";
	$hero->more_info_link ="http://en.wikipedia.org/wiki/Gertrude_Blanch";
	$hero->save();
	$hero->tags()->attach($tag_math);

	$hero = new Hero();
	$hero->name = "Grace Hopper";
	$hero->born = 1906;
	$hero->description = "'Grace Hopper (1906–1992), was a United States Navy officer and the first programmer of the Harvard Mark I, known as the 'Mother of COBOL'. She developed the first-ever compiler for an electronic computer, known as A-0. She also popularized the term 'debugging' – a reference to a moth extracted from a relay in the Harvard Mark II computer.' From Wikipedia";
	$hero->photo ="http://upload.wikimedia.org/wikipedia/commons/3/37/Grace_Hopper_and_UNIVAC.jpg";
	$hero->more_info_link ="http://en.wikipedia.org/wiki/Grace_Hopper";
	$hero->save();
	$hero->tags()->attach($tag_comp);
	
	
	return Redirect::to('/list');



});

Route::get('/debug', function() {

    echo '<pre>';

    echo '<h1>environment.php</h1>';
    $path   = base_path().'/environment.php';

    try {
        $contents = 'Contents: '.File::getRequire($path);
        $exists = 'Yes';
    }
    catch (Exception $e) {
        $exists = 'No. Defaulting to `production`';
        $contents = '';
    }

    echo "Checking for: ".$path.'<br>';
    echo 'Exists: '.$exists.'<br>';
    echo $contents;
    echo '<br>';

    echo '<h1>Environment</h1>';
    echo App::environment().'</h1>';

    echo '<h1>Debugging?</h1>';
    if(Config::get('app.debug')) echo "Yes"; else echo "No";

    echo '<h1>Database Config</h1>';
    print_r(Config::get('database.connections.mysql'));

    echo '<h1>Test Database Connection</h1>';
    try {
        $results = DB::select('SHOW DATABASES;');
        echo '<strong style="background-color:green; padding:5px;">Connection confirmed</strong>';
        echo "<br><br>Your Databases:<br><br>";
        print_r($results);
    } 
    catch (Exception $e) {
        echo '<strong style="background-color:crimson; padding:5px;">Caught exception: ', $e->getMessage(), "</strong>\n";
    }

    echo '</pre>';

});




