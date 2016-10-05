<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('users')->insert(['name' => 'JULIAN MAURICIO', 'lastname' => 'MEJIA CARDONA', 'birthDate'=> '1984-01-28', 'email'=>'jmmejia@autonoma.edu.co', 'password'=>sha1('123456'), 'color'=> 'verde', 'gender'=>'hombre', 'type'=>'administrador']);
      DB::table('users')->insert(['name' => 'JORGE IVAN', 'lastname' => 'MEZA MARTINEZ', 'birthDate'=> '1978-04-14', 'email'=>'jimezam@autonoma.edu.co', 'password'=>sha1('123456'), 'color'=> 'azul', 'gender'=>'hombre', 'type'=>'usuario']);


    }
}
