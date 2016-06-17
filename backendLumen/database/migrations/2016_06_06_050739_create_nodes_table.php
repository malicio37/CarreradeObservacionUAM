<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nodes', function (Blueprint $table) {
          // $table->engine = 'InnoDB';
          $table->increments('id');
          $table->string('name');
          $table->text('description');
          $table->string('code');
          $table->float('latitude');
          $table->float('longitude');
          $table->text('hint');
          $table->enum('status', ['enabled', 'disabled'])->default('enabled');
          $table->integer('circuit_id') -> unsigned();    // FK
          $table->timestamps();

          $table->index('circuit_id');

          $table ->
            foreign('circuit_id') ->
            references('id') ->
            on('circuits') ->
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
        Schema::drop('nodes');
    }
}
