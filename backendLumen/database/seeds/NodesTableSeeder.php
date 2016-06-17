<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class NodesTableSeeder extends Seeder
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
    $table->text('description');
    $table->string('code');
    $table->float('latitude');
    $table->float('longitude');
    $table->text('hint');
    $table->enum('status', ['enabled', 'disabled'])->default('enabled');
    $table->integer('circuit_id') -> unsigned();    // FK
    $table->timestamps();
    */

    DB::table('nodes')->insert([
      'name' => 'Nodo #1',
      'description' => 'This is the node #1',
      'code' => 'xxxxxxxxx',
      'latitude' => 1.1,
      'longitude' => 1.2,
      'hint' => 'Hint #1',
      'status' => 'enabled',
      'circuit_id' => 1,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ]);

    DB::table('nodes')->insert([
      'name' => 'Nodo #2',
      'description' => 'This is the node #3',
      'code' => 'xxxxxxxxx',
      'latitude' => 2.1,
      'longitude' => 2.2,
      'hint' => 'Hint #2',
      'status' => 'enabled',
      'circuit_id' => 1,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ]);

    DB::table('nodes')->insert([
      'name' => 'Nodo #1a',
      'description' => 'This is the node #1a',
      'code' => 'xxxxxxxxx',
      'latitude' => 1.11,
      'longitude' => 1.22,
      'hint' => 'Hint #1a',
      'status' => 'enabled',
      'circuit_id' => 2,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ]);
  }
}
