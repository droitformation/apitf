<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArretsGroupeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('arrets_groupes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('groupe_id');
			$table->string('arret_id');
			$table->string('sorting');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('arrets_groupes');
	}

}
