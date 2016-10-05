<?php

use Illuminate\Database\Seeder;

class NodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('nodes')-> insert (['name' =>'BIBLIOTECA', 'description' =>'BIBLIOTECA', 'code' => RAND(), 'latitude' =>5.0672036513457535,'longitude'=>-75.5031082034111, 'hint'=>'SILENCIO, ESPACIO Y CONOCIMIENTO ENCONTRARAS EN ESTE LUGAR', 'circuit_id' =>1]);
      DB::table('nodes')-> insert (['name' =>'CAJA', 'description' =>'CAJA', 'code' => RAND(), 'latitude' =>5.067820819841856,'longitude'=>-75.50278767943382, 'hint'=>'AQUÍ TU SEMESTRE Y LIBROS HAS DE PAGAR', 'circuit_id' =>1]);
      DB::table('nodes')-> insert (['name' =>'REGISTRO ACADEMICO', 'description' =>'REGISTRO ACADEMICO', 'code' => RAND(), 'latitude' =>5.067927688785628,'longitude'=>-75.50267234444618, 'hint'=>'EN ESTE LUGAR EL PROCESO DE INSCRIPCION ACENTARAS', 'circuit_id' =>1]);
      DB::table('nodes')-> insert (['name' =>'MERCADEO', 'description' =>'MERCADEO', 'code' => RAND(), 'latitude' =>5.067828835013248,'longitude'=>-75.50270587205887, 'hint'=>'SUS ENCARGADOS LA OFERTA ACADEMICA Y AYUDA ADMINISTRATIVA TE OFRECERAN', 'circuit_id' =>1]);
      DB::table('nodes')-> insert (['name' =>'RELACIONES INTERNACIONALES', 'description' =>'RELACIONES INTERNACIONALES', 'code' => RAND(), 'latitude' =>5.067871582592347,'longitude'=>-75.50261199474335, 'hint'=>'SI UN INTERCAMBIO QUIERES HACER A ESTE LUGAR DEBES LLEGAR', 'circuit_id' =>1]);
      DB::table('nodes')-> insert (['name' =>'CARTERA', 'description' =>'CARTERA', 'code' => RAND(), 'latitude' =>5.067828835013248,'longitude'=>-75.50262004137039, 'hint'=>'PLAZOS DE FINANCIAMIENTO Y OPCIONES A PROBLEMAS ECONOMICAS ACA TE DARAN', 'circuit_id' =>1]);
      DB::table('nodes')-> insert (['name' =>'DESARROLLO HUMANO', 'description' =>'DESARROLLO HUMANO', 'code' => RAND(), 'latitude' =>5.068297722366866,'longitude'=>-75.50283528864384, 'hint'=>'SUS ENCARGADOS VELAN POR TU BIENESTAR MENTAL Y PSICOLOGICO, ADEMAS DE AYUDA CON DIFICULTADES ACADEMICAS', 'circuit_id' =>1]);
      DB::table('nodes')-> insert (['name' =>'SERVICIOS MEDICOS', 'description' =>'SERVICIOS MEDICOS', 'code' => RAND(), 'latitude' =>5.0685234828231644,'longitude'=>-75.50286144018173, 'hint'=>'EN ESTE SITIO ENCONTRARAS UN MEDICO QUE TE PUEDE INDICAR QUE TRATAMIENTO TOMAR', 'circuit_id' =>1]);
      DB::table('nodes')-> insert (['name' =>'ATENCION PREHOSPITALARIA', 'description' =>'ATENCION PREHOSPITALARIA', 'code' => RAND(), 'latitude' =>5.0682402803446776,'longitude'=>-75.50261467695236, 'hint'=>'SI TE SIENTES FISICAMENTE MAL EN ESTE LUGAR UNA PERSONA PROFESIONAL TE ATENDERA', 'circuit_id' =>1]);
      DB::table('nodes')-> insert (['name' =>'LABORATORIO ELECTRONICA', 'description' =>'LABORATORIO ELECTRONICA', 'code' => RAND(), 'latitude' =>5.068512795939442,'longitude'=>-75.50254493951797, 'hint'=>'LOS MONTAJES ELECTRONICOS Y EXPERIMENTOS FISICOS EN ESTE LUGAR DESARROLLARAS', 'circuit_id' =>1]);
      DB::table('nodes')-> insert (['name' =>'GIMNASIO', 'description' =>'GIMNASIO', 'code' => RAND(), 'latitude' =>5.068566230356298,'longitude'=>-75.50263077020645, 'hint'=>'ALLI EJERCICIO, JUGAR Y LIBERAR TENSION PUEDES REALIZAR', 'circuit_id' =>1]);
      DB::table('nodes')-> insert (['name' =>'GESTION DE TECNOLOGIA', 'description' =>'GESTION DE TECNOLOGIA', 'code' => RAND(), 'latitude' =>5.068304401671438,'longitude'=>-75.50262540578842, 'hint'=>'OLVIDO DE CONTRASEÑAS Y PROBLEMAS DE INGRESO A LOS SISTEMAS AQUÍ TE SOLUCIONARAN', 'circuit_id' =>1]);
      DB::table('nodes')-> insert (['name' =>'DECANATURA INGENIERIA', 'description' =>'DECANATURA INGENIERIA', 'code' => RAND(), 'latitude' =>5.068534169706709,'longitude'=>-75.50278097391129, 'hint'=>'LOS PROCESOS ACADEMICOS DESDE ALLÍ SE GOBERNARAN', 'circuit_id' =>1]);
      DB::table('nodes')-> insert (['name' =>'SALA DE PROFESORES DE INGENIERIA', 'description' =>'SALA DE PROFESORES DE INGENIERIA', 'code' => RAND(), 'latitude' =>5.068763937660251,'longitude'=>-75.50281316041946, 'hint'=>'ASISTENCIA Y CONSULTARIA DE QUIENES TE ENSEÑAN EN ESTE LUGAR TENDRAS', 'circuit_id' =>1]);
      DB::table('nodes')-> insert (['name' =>'CENTRO DE INFORMATICA', 'description' =>'CENTRO DE INFORMATICA', 'code' => RAND(), 'latitude' =>5.067786087431325,'longitude'=>-75.50275951623917, 'hint'=>'LAS HERRAMIENTAS COMPUTACIONES EN ESTE PISO ENTERO ENCONTRARAS', 'circuit_id' =>1]);
      DB::table('nodes')-> insert (['name' =>'VICERRECTORIA ACADEMICA', 'description' =>'VICERRECTORIA ACADEMICA', 'code' => RAND(), 'latitude' =>5.066808235722423,'longitude'=>-75.50553292036057, 'hint'=>'CUANDO PROBLEMAS ACADÉMICOS ENCUENTRES, ESTE ES EL MÁXIMO ESTAMENTO A CONSULTAR', 'circuit_id' =>1]);
      DB::table('nodes')-> insert (['name' =>'RECTORIA', 'description' =>'RECTORIA', 'code' => RAND(), 'latitude' =>5.06676548807297,'longitude'=>-75.5052325129509, 'hint'=>'DESDE ALLÍ SE DIRIGE LA UAM', 'circuit_id' =>1]);
      DB::table('nodes')-> insert (['name' =>'BIBLIOTECA', 'description' =>'BIBLIOTECA', 'code' => RAND(), 'latitude' =>5.0672036513457535,'longitude'=>-75.5031082034111, 'hint'=>'SILENCIO, ESPACIO Y CONOCIMIENTO ENCONTRARAS EN ESTE LUGAR', 'circuit_id' =>1]);
      DB::table('nodes')-> insert (['name' =>'CAJA', 'description' =>'CAJA', 'code' => RAND(), 'latitude' =>5.067820819841856,'longitude'=>-75.50278767943382, 'hint'=>'AQUÍ TU SEMESTRE Y LIBROS HAS DE PAGAR', 'circuit_id' =>2]);
      DB::table('nodes')-> insert (['name' =>'REGISTRO ACADEMICO', 'description' =>'REGISTRO ACADEMICO', 'code' => RAND(), 'latitude' =>5.067927688785628,'longitude'=>-75.50267234444618, 'hint'=>'EN ESTE LUGAR EL PROCESO DE INSCRIPCION ACENTARAS', 'circuit_id' =>2]);

    }
}
