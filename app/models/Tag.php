<?php

class Tag extends Eloquent {

	public function users() {
 		return $this->belongsToMany('User');
 	}
 	public function heroes() {
 		return $this->belongsToMany('Hero');
 	}

}