<?php

class HeroList {

public $heroes;
public $path;


public function __construct($path) {
	$this->set_path($path);
}

public function get_path() {	
return $this->path;
}

public function set_path($new_path) {
$this->path = $new_path;
}


public function get_heroes($refresh = false) {


	if($this->heroes && !$refresh) {
		return $this->heroes;
	}

		$heroes = File::get($this->path);
		$heroes = json_decode($heroes,true);
		$this->heroes = $heroes;

	return $heroes;

}


/**
* @param String $query
* @return Array $results
*/
public function search($query) {

	$heroes = $this->get_heroes();

	$results = Array();

	# Loop through the books looking for matches
	foreach($heroes as $name => $hero) {

		# First compare the query against the name
		if(stristr($name,$query)) {

		# There's a match - add this hero the the $results array
		$results[$name] = $hero;
		}
		# Then compare the query against all the attributes of the hero (tags)
		else {

			if(self::search_hero_attributes($hero,$query)) {
			# There's a match - add this book the the $results array
			$results[$name] = $hero;
			}
		}
	}

	return $results;

}

/**
* Resursively search through a hero's attributes looking for a query match
* @param Array $attributes
* @param String $query
* @return Boolean Whether query was found in the attribute
*/
private function search_hero_attributes($attributes,$query) {

foreach($attributes as $k => $value) {

# Dig deeper
if (is_array($value)) {
return self::search_hero_attributes($value,$query);
}

if(stristr($value,$query)) {
return true;
}
}

return false;

}

} 