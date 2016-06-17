<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_questions', function (Blueprint $table) {
            // $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('question');
            $table->string('answer');
            $table->integer('node_id') -> unsigned();    // FK
            $table->timestamps();

            $table->index('node_id');

            $table ->
              foreign('node_id') ->
              references('id') ->
              on('nodes') ->
              onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('node_questions');
    }
}
