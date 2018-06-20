<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArretCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('arret_categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('arret_id')->unsigned()->index();
			$table->integer('categories_id')->unsigned()->index();
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
		Schema::drop('arret_categories');
	}

}
