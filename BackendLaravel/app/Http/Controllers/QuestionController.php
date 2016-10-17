<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

class QuestionController extends Controller
{

  ////////////////////////////////////////////////////////////////////////////////
  // Specific bussiness API


  /**
  * GET /questions/node/{id}
  * @param integer $node_id
  * @return mixed
  */
  public function getNodeQuestions($node_id){
    try{
      $response = (DB::table('questions')->where('node_id', '=', $node_id)->get());
      if(count($response) != 0){
        return response()->json(['data' => $response],200);
      }
      return response()->json(['message' => 'Node doesn´t have questions'], 404);
    }
    catch(\Illuminate\Database\QueryException $e){
      return response()->json(['message' => 'Consult failure'], 500);
    }
  }


  /**
  * POST /questions/validate
  * @param question_id id de la pregunta
  * @param response respuesta
  * @return mixed
  */
  public function validateResponse(Request $data) {
    try{
      $request=json_decode($data->getContent());
      $response = (DB::table('questions')
              ->select('id')
              ->where('id', '=', $request->question_id)
              ->where('answer','=',$request->answer)->get());
      if(count($response) != 0){
        return response()->json(['data' => $response],200);
      }
      return response()->json(['message' => 'Answer incorrect'], 404);
    }
    catch(\Illuminate\Database\QueryException $e){
      return response()->json(['message' => 'Consult failure'], 500);
    }
  }

//{"question_id": 1, "response":"WBEIMAR CANO RESTREPO"}


////////////////////////////////////////////////////////////////////////////////
// Standar API

  /**
   * GET /Questions
   */
    public function index()
    {
      try{
        $response = (DB::table('questions')->get());
        return response()->json(['data' => $response],200);
      }
      catch(\Illuminate\Database\QueryException $e){
        return response()->json(['message' => 'Consult failure'], 500);
      }
    }

    /**
     * Display the specified resource.
     * GET /questions/{id}
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      try{
        $response =  (DB::table('questions')->where('id', '=', $id)->get());
        if(count($response) != 0){
          return response()->json(['data' => $response],200);
        }
        return response()->json(['message' => 'Question not found'], 404);
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
        $id = DB::table('questions')->insertGetId([
        'question' => $request->question,
        'answer' => $request->answer,
        'node_id' => $request->node_id
        ]);
        $response = (DB::table('questions')->where('id', '=', $id)->get());
        if(count($response) != 0){
          return response()->json(['data' => $response],201);
        }
        return response()->json(['message' => 'failure to create Question'], 404);
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
        DB::table('questions')->where('id', $id)->update(
        ['question' => $request->question, 'answer'=> $request->answer,'node_id' => $request->node_id]);
        $response= (DB::table('questions')->where('id', '=', $id)->get());
        if(count($response) != 0){
          return response()->json(['data' => $response],200);
        }
        return response()->json(['message' => 'Question not found'], 404);
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
        $response= (DB::table('questions')->where('id', '=', $id)->get());
        DB::table('questions')->where('id', '=', $id)->delete();
        if(count($response) != 0){
          return response()->json(['message' => 'Question deleted'],200);
        }
        return response()->json(['message' => 'Question not found'], 404);
      }
      catch(\Illuminate\Database\QueryException $e){
        return response()->json(['message' => 'Consult failure'], 500);
      }
    }
}
