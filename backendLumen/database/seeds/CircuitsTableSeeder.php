<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CircuitsTableSeeder extends Seeder
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
    $table->enum('status', ['enabled', 'disabled'])->default('enabled');
    $table->text('description');
    $table->timestamps();
    */

    DB::table('circuits')->insert([
      'name' => 'Circuito #1',
      'status' => 'enabled',
      'description' => 'This is the circuit #1',
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ]);

    DB::table('circuits')->insert([
      'name' => 'Circuito #2',
      'status' => 'enabled',
      'description' => 'This is the circuit #2',
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ]);

    DB::table('circuits')->insert([
      'name' => 'Circuito #3',
      'status' => 'disabled',
      'description' => 'This is the circuit #3',
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ]);
  }
}
