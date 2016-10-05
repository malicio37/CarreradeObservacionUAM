<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

class NodeController extends Controller
{


  ////////////////////////////////////////////////////////////////////////////////
  // Specific bussiness API

  /**
  * POST /nodes/showhint
  * @param integer $user_id
  * @param integer $circuit_id
  * @return mixed
  */
  public function getUserHints(Request $request){
    /* "SELECT n.id, n.hint FROM node n JOIN (SELECT * FROM nodediscovered d WHERE d.user_id=:user_id AND d.status= 0) AS t
		 ON n.id= t.node_id WHERE n.circuit_id=:circuit_id"; */

     return (DB::table('nodes')->join('discoverednodes','nodes.id','=','discoverednodes.node_id')
            ->where('discoverednodes.user_id','=',$request['user_id'])
            ->where('discoverednodes.status','=',0)
            ->where('nodes.circuit_id','=',$request['circuit_id'])
            ->select('nodes.id','nodes.hint')->get()
            );
  }



  /**
  * POST /nodes/validate
  * @param id id del nodo
  * @param code código QR
  * @return mixed
  */
  public function validateNodeCode(Request $request) {
    return (DB::table('nodes')
            ->select('id')
            ->where('id', '=', $request['node_id'])
            ->where('code','=',$request['code'])->get());
  }

  //{"node_id": 2, "code":"0.123456768"}


////////////////////////////////////////////////////////////////////////////////
// Standar API


  /**
   * GET /nodes
   */
    public function index()
    {
      return (DB::table('nodes')->get());
    }

    /**
     * Display the specified resource.
     * GET /nodes/{id}
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      return (DB::table('nodes')->where('id', '=', $id)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $id = DB::table('nodes')->insertGetId([
      'name' => $request['name'],
      'description' => $request['description'],
      'code' => $request['code'],
      'longitude' => $request['longitude'],
      'latitude' => $request['latitude'],
      'hint' => $request['hint'],
      'circuit_id' => $request['circuit_id']
      ]);
      return (DB::table('nodes')->where('id', '=', $id)->get());
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

      DB::table('nodes')->where('id', $id)->update(
      ['name' => $request['name'], 'description'=> $request['description'],'code' => $request['code'],'longitude' => $request['longitude'],'latitude' => $request['latitude'],'hint' => $request['hint'],'circuit_id' => $request['circuit_id']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      DB::table('nodes')->where('id', '=', $id)->delete();
    }

}
