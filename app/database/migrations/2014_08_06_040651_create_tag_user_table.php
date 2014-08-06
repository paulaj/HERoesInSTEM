<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tag_user', function($table) {

			$table->integer('tag_id')->unsigned();
			$table->integer('user_id')->unsigned();
			
			$table->foreign('tag_id')->references('id')->on('tags');
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
		Schema::drop('tag_user');
	}

}
