<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

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
      return (DB::table('inscriptions')->get());
    }

    /**
     * Display the specified resource.
     * GET /inscriptions/{id}
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      return (DB::table('inscriptions')->where('id', '=', $id)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $id = DB::table('inscriptions')->insertGetId([
      'circuit_id' => $request['circuit_id'],
      'user_id' => $request['user_id'],
      'inscription_date' => $request['inscription_date']
      ]);
      return (DB::table('inscriptions')->where('id', '=', $id)->get());
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

      DB::table('inscriptions')->where('id', $id)->update(
      ['circuit_id' => $request['circuit_id'], 'user_id'=> $request['user_id'],'inscription_date' => $request['inscription_date']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      DB::table('inscriptions')->where('id', '=', $id)->delete();
    }

}
