<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CircuitUserTableSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    /*
    // $table->engine = 'InnoDB';
    $table->integer('circuit_id') -> unsigned();    // FK
    $table->integer('user_id') -> unsigned();    // FK
    // $table->smallInteger('year');
    // $table->smallInteger('semester');
    $table->timestamps();
    */

    DB::table('circuit_user')->insert([
      'circuit_id' => 1,
      'user_id' => 2,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ]);

    DB::table('circuit_user')->insert([
      'circuit_id' => 2,
      'user_id' => 2,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ]);

    DB::table('circuit_user')->insert([
      'circuit_id' => 3,
      'user_id' => 2,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ]);
  }
}
