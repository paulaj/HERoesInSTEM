<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserHeroTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_hero', function($table) {

			$table->integer('user_id')->unsigned();
			$table->integer('hero_id')->unsigned();

			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('hero_id')->references('id')->on('heroes');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_hero');
	}

}
