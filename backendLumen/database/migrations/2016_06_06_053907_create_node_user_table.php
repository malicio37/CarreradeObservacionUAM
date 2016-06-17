<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Node discovered by user on circuit

        Schema::create('node_user', function (Blueprint $table) {
          // $table->engine = 'InnoDB';
          $table->integer('circuit_id') -> unsigned();    // FK
          $table->integer('user_id') -> unsigned();    // FK
          $table->integer('node_id') -> unsigned();    // FK
          $table->enum('status', [0, 1, 2])->default(0);
          $table->dateTime('date_status_0')->nullable();
          $table->dateTime('date_status_1')->nullable();
          $table->dateTime('date_status_2')->nullable();
          $table->integer('node_question_id') -> unsigned() -> nullable();  // FK
          $table->timestamps();

          $table->index('circuit_id');

          $table ->
            foreign('circuit_id') ->
            references('id') ->
            on('circuits') ->
            onDelete('cascade');

          $table->index('user_id');

          $table ->
            foreign('user_id') ->
            references('id') ->
            on('users') ->
            onDelete('cascade');

          $table->index('node_id');

          $table ->
            foreign('node_id') ->
            references('id') ->
            on('nodes') ->
            onDelete('cascade');

          $table->index('node_question_id');

          $table ->
            foreign('node_question_id') ->
            references('id') ->
            on('node_questions') ->
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
        Schema::drop('node_user');
    }
}
