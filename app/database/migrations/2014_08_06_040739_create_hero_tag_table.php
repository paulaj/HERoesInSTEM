<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeroTagTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hero_tag', function($table) {

			$table->integer('hero_id')->unsigned();
			$table->integer('tag_id')->unsigned();

			$table->foreign('hero_id')->references('id')->on('heroes');
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
		Schema::drop('hero_tag');
	}

}
