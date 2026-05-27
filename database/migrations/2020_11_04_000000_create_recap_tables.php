<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecapTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
		Schema::create('project', function(Blueprint $table) {
			$table->id();
			$table->bigInteger('surveys_questions_id')->unsigned();
			$table->string('title');
			$table->integer('sequence_number');
			$table->boolean('follows_new_question');
			$table->timestamps();

			$table->foreign('surveys_questions_id')->references('id')->on('surveys_questions')->onDelete('cascade');
        });*/
        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('project');
    }
}
