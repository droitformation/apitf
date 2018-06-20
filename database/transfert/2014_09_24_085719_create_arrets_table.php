<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArretsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('arrets', function(Blueprint $table)
		{

            $table->increments('id')->unsigned();
            $table->integer('user_id')->nullable();
			$table->integer('site_id')->nullable();
            $table->string('reference');
            $table->dateTime('pub_date');
            $table->text('abstract')->nullable();
            $table->text('pub_text')->nullable();
            $table->text('file')->nullable();
            $table->boolean('dumois')->default(0);
			$table->timestamps();
            $table->softDeletes();

		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('arrets');
	}

}
