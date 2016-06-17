<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCircuitUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('circuit_user', function (Blueprint $table) {
            // $table->engine = 'InnoDB';
            $table->integer('circuit_id') -> unsigned();    // FK
            $table->integer('user_id') -> unsigned();    // FK
            // $table->smallInteger('year');
            // $table->smallInteger('semester');
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('circuit_user');
    }
}
