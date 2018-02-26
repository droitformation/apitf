<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDecisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decisions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero');
            $table->dateTime('publication_at');
            $table->dateTime('decision_at');
            $table->integer('categorie_id')->nullable();
            $table->text('remarque')->nullable();
            $table->string('link')->nullable();
            $table->longText('texte')->nullable();
            $table->tinyInteger('langue')->nullable();
            $table->tinyInteger('publish')->nullable();
            $table->tinyInteger('updated')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('decisions');
    }
}
