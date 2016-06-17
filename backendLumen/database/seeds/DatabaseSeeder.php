<?php

use Illuminate\Database\Seeder;

use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Model::unguard();

      // $this->call('UsersTableSeeder');
      $this->call(UsersTableSeeder::class);
      $this->call(CircuitsTableSeeder::class);
      $this->call(NodesTableSeeder::class);
      $this->call(NodeQuestionsTableSeeder::class);
      $this->call(CircuitUserTableSeeder::class);

      Model::reguard();
    }
}
