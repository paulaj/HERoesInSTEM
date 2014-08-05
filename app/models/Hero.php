<?php

class Hero extends Eloquent {

	public function tags() {
 		return $this->belongsToMany('Tag');
 	}

 	public function users() {
 		return $this->belongsToMany('User');
 	}

}