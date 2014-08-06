<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeroesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('heroes', function($table) {

        $table->increments('id');
        $table->timestamps();

        $table->string('name');
        $table->text('description');
        $table->integer('born');
        $table->string('photo');
        $table->string('more_info_link');


    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('heroes');
	}

}
