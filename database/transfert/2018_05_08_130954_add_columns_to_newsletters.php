<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToNewsletters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newsletters', function (Blueprint $table) {
            $table->tinyInteger('pdf')->nullable();
            $table->string('classe')->nullable();
            $table->tinyInteger('comment')->nullable();
            $table->string('comment_title')->default('Commentaire')->nullable();
            $table->string('display')->default('bottom')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('newsletters', function (Blueprint $table) {
            $table->dropColumn('pdf');
            $table->dropColumn('classe');
            $table->dropColumn('comment');
            $table->dropColumn('Commentaire');
            $table->dropColumn('display');
        });
    }
}
