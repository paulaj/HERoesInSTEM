<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tags', function($table) {

			$table->increments('id');
			$table->timestamps();
			$table->string('name');

		});

		Schema::create('hero_tag', function($table) {

			$table->integer('hero_id')->unsigned();
			$table->integer('tag_id')->unsigned();

			$table->foreign('hero_id')->references('id')->on('heroes');
			$table->foreign('tag_id')->references('id')->on('tags');

		});

		Schema::create('user_tag', function($table) {

			$table->integer('user_id')->unsigned();
			$table->integer('tag_id')->unsigned();

			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('tag_id')->references('id')->on('tags');

		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tags');
		Schema::drop('hero_tag');
		Schema::drop('user_tag');
	}

}
