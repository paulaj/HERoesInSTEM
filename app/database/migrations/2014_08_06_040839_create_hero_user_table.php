<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeroUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hero_user', function($table) {

			$table->integer('hero_id')->unsigned();
			$table->integer('user_id')->unsigned();

			$table->foreign('hero_id')->references('id')->on('heroes');
			$table->foreign('user_id')->references('id')->on('users');
			

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hero_user');
	}

}
