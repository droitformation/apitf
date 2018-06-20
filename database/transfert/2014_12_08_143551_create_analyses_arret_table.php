<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnalysesArretTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('analyses_arret', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('analyse_id')->unsigned()->index();
			$table->integer('arret_id')->unsigned()->index();
            $table->integer('sorting')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('analyses_arret');
	}

}
