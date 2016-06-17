<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class NodeQuestionsTableSeeder extends Seeder
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
    $table->increments('id');
    $table->string('question');
    $table->string('answer');
    $table->integer('node_id') -> unsigned();    // FK
    $table->timestamps();
    */
    DB::table('node_questions')->insert([
      'question' => 'Question 1 for node 1',
      'answer' => 'a',
      'node_id' => 1,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ]);

    DB::table('node_questions')->insert([
      'question' => 'Question 2 for node 1',
      'answer' => 'b',
      'node_id' => 1,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ]);

    DB::table('node_questions')->insert([
      'question' => 'Question 3 for node 1',
      'answer' => 'c',
      'node_id' => 1,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ]);

    DB::table('node_questions')->insert([
      'question' => 'Question 1 for node 2',
      'answer' => 'a',
      'node_id' => 2,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ]);

    DB::table('node_questions')->insert([
      'question' => 'Question 1 for node 3',
      'answer' => 'a',
      'node_id' => 3,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ]);
  }
}
