<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
          // $table->engine = 'InnoDB';
          $table->increments('id');
          $table->string('name');
          $table->string('lastname');
          $table->string('email')->unique();
          $table->string('password', 64) -> nullable();
          $table->enum('type', ['admin', 'user'])->default('user');
          $table->rememberToken();
          $table->softDeletes();
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
        Schema::drop('users');
    }
}
