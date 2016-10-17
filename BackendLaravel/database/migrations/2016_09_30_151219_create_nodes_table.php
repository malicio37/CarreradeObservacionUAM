<?php

use Illuminate\Support\Facades\Schema;
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
            $table->increments('id');
            $table->string('name',60);
            $table->string('description')->nullable();
            $table->string('code',60);
            $table->float('latitude',15,13);
            $table->float('longitude',15,13);
            $table->string('hint');
            $table->integer('circuit_id')->unsigned();
            $table->foreign('circuit_id')->references('id')->on('circuits')->onDelete('cascade');
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
        Schema::dropIfExists('nodes');
    }
}
