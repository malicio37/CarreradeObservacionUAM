<?php

use Illuminate\Database\Seeder;

class CircuitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('circuits')->insert(['name'=>'COSem12016', 'status'=>1, 'description'=> 'Primera carrera de observación']);
      DB::table('circuits')->insert(['name'=>'Intersemestral2016', 'status'=>1, 'description'=> 'carrera de observación intersemestral']);
    }
}
