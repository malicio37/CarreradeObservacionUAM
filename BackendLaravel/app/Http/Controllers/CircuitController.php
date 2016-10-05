<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

class CircuitController extends Controller
{

  ////////////////////////////////////////////////////////////////////////////////
  // Specific bussiness API


  /**
  * POST /inscriptions/user
  * @param user_id
  * @return mixed
  */
  public function getCircuitInscripted(Request $data){
    /* "SELECT circuit.id, circuit.name FROM circuit INNER JOIN (SELECT circuit_id FROM inscription
						 WHERE user_id=:user_id)AS a ON circuit.id=a.circuit_id WHERE circuit.status=1";*/
    $request=json_decode($data->getContent());
    return (DB::table('circuits')->join('inscriptions','circuits.id','=','inscriptions.circuit_id')
    ->where('inscriptions.user_id','=',$request->user_id)
    ->select('circuits.id','circuits.name')
    ->where('circuits.status','=',1)->get()
    );
  }
//{"user_id":1}


    /**
    * GET /circuits/score/{circuit_id}
    * @param integer $circuit_id
    * @return mixed
    */
    public function getTotalNodes($circuit_id){

    	 $sql = "SELECT COUNT(id) as total FROM nodes WHERE nodes.circuit_id=" . $circuit_id;
       return DB::select($sql);
     }

     /**
     * GET /circuits/totalscore
     * @param integer $circuit_id
     * @return mixed
     */
     public function getTotalScore($circuit_id){
       /*
       "SELECT COUNT(u.id) AS cantidad, u.email FROM nodediscovered nd JOIN user u ON
	  nd.user_id=u.id JOIN node n ON nd.node_id=n.id WHERE nd.status=2 AND n.circuit_id= :circuit_id GROUP BY nd.user_id DESC";
        */

        return DB::table('users')
          ->select(DB::raw('count(users.id) as cantidad, users.email'))
          ->join('discoverednodes','discoverednodes.user_id','users.id')
          ->join('nodes','nodes.id', 'discoverednodes.node_id')
          ->where('discoverednodes.status','=',2)
          ->where('nodes.circuit_id','=',$circuit_id)
          ->groupBy('users.email')
          ->get();
      }


////////////////////////////////////////////////////////////////////////////////
// Standar API


  /**
   * GET /circuits
   */
    public function index()
    {
      return (DB::table('circuits')->get());
    }

    /**
     * Display the specified resource.
     * GET /circuits/{id}
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      return (DB::table('circuits')->where('id', '=', $id)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $data)
    {
      $request=json_decode($data->getContent());
      $id = DB::table('circuits')->insertGetId([
      'name' => $request->name,
      'status' => $request->status,
      'description' => $request->description
      ]);
      return (DB::table('circuits')->where('id', '=', $id)->get());
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
      $request=json_decode($data->getContent());
      DB::table('circuits')->where('id', $id)->update(
      ['name' => $request->name, 'status'=> $request->status,'description' => $request->description]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      DB::table('circuits')->where('id', '=', $id)->delete();
    }


}
