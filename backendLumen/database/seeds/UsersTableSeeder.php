<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersTableSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    /*
    $table->increments('id');
    $table->string('name');
    $table->string('lastname');
    $table->string('email')->unique();
    $table->string('password', 64);
    $table->enum('type', ['admin', 'user'])->default('user');
    $table->rememberToken();
    $table->softDeletes();
    $table->timestamps();
    */

    DB::table('users')->insert([
      'name' => 'User',
      'lastname' => 'Administrator',
      'email' => 'admin@admin.com',
      'password' => 'admin',
      'type' => 'admin',
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ]);

    DB::table('users')->insert([
      'name' => 'User',
      'lastname' => 'Regular',
      'email' => 'usuario@autonoma.edu.co',
      'password' => 'usuario',
      'type' => 'user',
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ]);
  }
}
