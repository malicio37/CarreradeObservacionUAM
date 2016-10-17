<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Carbon\Carbon;

class InscriptionController extends Controller
{

  ////////////////////////////////////////////////////////////////////////////////
  // Specific bussiness API




  ////////////////////////////////////////////////////////////////////////////////
  // Standar API

  /**
   * GET /inscriptions
   */
    public function index()
    {
      try{
        $response = (DB::table('inscriptions')->get());
        return response()->json(['data' => $response],200);
      }
      catch(\Illuminate\Database\QueryException $e){
        return response()->json(['message' => 'Consult failure'], 500);
      }
    }

    /**
     * Display the specified resource.
     * GET /inscriptions/{id}
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      try{
        $response = (DB::table('inscriptions')->where('id', '=', $id)->get());
        if(count($response) != 0){
          return response()->json(['data' => $response],200);
        }
        return response()->json(['message' => 'Inscription not found'], 404);
      }
      catch(\Illuminate\Database\QueryException $e){
        return response()->json(['message' => 'Consult failure'], 500);
      }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $data)
    {
      try{
        $request=json_decode($data->getContent());
        $id = DB::table('inscriptions')->insertGetId([
        'circuit_id' => $request->circuit_id,
        'user_id' => $request->user_id,
        'inscription_date'=> Carbon::now() -> toDateTimeString()
        ]);
        $response = (DB::table('inscriptions')->where('id', '=', $id)->get());
        if(count($response) != 0){
          return response()->json(['data' => $response],201);
        }
        return response()->json(['message' => 'failure to create Inscription'], 404);
      }
      catch(\Illuminate\Database\QueryException $e){
        return response()->json(['message' => 'Consult failure'], 500);
      }
    }
    //{"question":"nn", "answer":"nn","node_id":2}


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $data, $id)
    {
      try{
        $request=json_decode($data->getContent());
        DB::table('inscriptions')->where('id', $id)->update(
        ['circuit_id' => $request->circuit_id, 'user_id'=> $request->user_id,'inscription_date' => $request->inscription_date]);
        $response= (DB::table('inscriptions')->where('id', '=', $id)->get());
        if(count($response) != 0){
          return response()->json(['data' => $response],200);
        }
        return response()->json(['message' => 'Inscription not found'], 404);
      }
      catch(\Illuminate\Database\QueryException $e){
        return response()->json(['message' => 'Consult failure'], 500);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      try{
        $response= (DB::table('inscriptions')->where('id', '=', $id)->get());
        DB::table('inscriptions')->where('id', '=', $id)->delete();
        if(count($response) != 0){
          return response()->json(['message' => 'Inscription deleted'],200);
        }
        return response()->json(['message' => 'Inscription not found'], 404);
      }
      catch(\Illuminate\Database\QueryException $e){
        return response()->json(['message' => 'Consult failure'], 500);
      }
    }
}
