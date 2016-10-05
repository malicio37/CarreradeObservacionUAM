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
    return (DB::table('questions')->where('node_id', '=', $node_id)->get());
  }


  /**
  * POST /questions/validate
  * @param question_id id de la pregunta
  * @param response respuesta
  * @return mixed
  */
  public function validateResponse(Request $request) {
    return (DB::table('questions')
            ->select('id')
            ->where('id', '=', $request['question_id'])
            ->where('answer','=',$request['answer'])->get());
  }
  
//{"question_id": 1, "response":"WBEIMAR CANO RESTREPO"}


////////////////////////////////////////////////////////////////////////////////
// Standar API

  /**
   * GET /Questions
   */
    public function index()
    {
      return (DB::table('questions')->get());
    }

    /**
     * Display the specified resource.
     * GET /questions/{id}
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      return (DB::table('questions')->where('id', '=', $id)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $id = DB::table('questions')->insertGetId([
      'question' => $request['question'],
      'answer' => $request['answer'],
      'node_id' => $request['node_id']
      ]);
      return (DB::table('questions')->where('id', '=', $id)->get());
    }
    //{"question":"nn", "answer":"nn","node_id":2}


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

      DB::table('questions')->where('id', $id)->update(
      ['question' => $request['question'], 'answer'=> $request['answer'],'node_id' => $request['node_id']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      DB::table('questions')->where('id', '=', $id)->delete();
    }



}
