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
    try{
      $request=json_decode($data->getContent());
      $response = (DB::table('circuits')->join('inscriptions','circuits.id','=','inscriptions.circuit_id')
      ->where('inscriptions.user_id','=',$request->user_id)
      ->select('circuits.id','circuits.name')
      ->where('circuits.status','=',1)->get()
      );
      if(count($response) != 0){
        return response()->json(['data' => $response],200);
      }
      return response()->json(['message' => 'User not inscripted on any circuit'], 404);
    }
    catch(\Illuminate\Database\QueryException $e){
      return response()->json(['message' => 'Consult failure'], 500);
    }
  }
//{"user_id":1}


    /**
    * GET /circuits/score/{circuit_id}
    * @param integer $circuit_id
    * @return mixed
    */
    public function getTotalNodes($circuit_id){
      try{
      	 $sql = "SELECT COUNT(id) as total FROM nodes WHERE nodes.circuit_id=" . $circuit_id;
         $response = DB::select($sql);
         return response()->json(['data' => $response],200);
       }
       catch(\Illuminate\Database\QueryException $e){
         return response()->json(['message' => 'Consult failure'], 500);
       }
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
        try{
          $response = DB::table('users')
            ->select(DB::raw('count(users.id) as cantidad, users.email'))
            ->join('discoverednodes','discoverednodes.user_id','users.id')
            ->join('nodes','nodes.id', 'discoverednodes.node_id')
            ->where('discoverednodes.status','=',2)
            ->where('nodes.circuit_id','=',$circuit_id)
            ->groupBy('users.email')
            ->orderBy('cantidad','desc')
            ->get();
          return response()->json(['data' => $response],200);
        }
        catch(\Illuminate\Database\QueryException $e){
          return response()->json(['message' => 'Consult failure'], 500);
        }
      }


////////////////////////////////////////////////////////////////////////////////
// Standar API


  /**
   * GET /circuits
   */
    public function index()
    {
      try{
        $response = (DB::table('circuits')->get());
        return response()->json(['data' => $response],200);
      }
      catch(\Illuminate\Database\QueryException $e){
        return response()->json(['message' => 'Consult failure'], 500);
      }
    }

    /**
     * Display the specified resource.
     * GET /circuits/{id}
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      try{
        $response = (DB::table('circuits')->where('id', '=', $id)->get());
        if(count($response) != 0){
          return response()->json(['data' => $response],200);
        }
        return response()->json(['message' => 'Circuit not found'], 404);
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
        $id = DB::table('circuits')->insertGetId([
        'name' => $request->name,
        'status' => $request->status,
        'description' => $request->description
        ]);
        $response = (DB::table('circuits')->where('id', '=', $id)->get());
        if(count($response) != 0){
          return response()->json(['data' => $response],201);
        }
        return response()->json(['message' => 'failure to create Circuit'], 404);
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
        DB::table('circuits')->where('id', $id)->update(
        ['name' => $request->name, 'status'=> $request->status,'description' => $request->description]);
        $response = (DB::table('circuits')->where('id', '=', $id)->get());
        if(count($response) != 0){
          return response()->json(['data' => $response],200);
        }
        return response()->json(['message' => 'Circuit not found'], 404);
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
        $response = (DB::table('circuits')->where('id', '=', $id)->get());
        DB::table('circuits')->where('id', '=', $id)->delete();
        if(count($response) != 0){
          return response()->json(['message' => 'Circuit deleted'],200);
        }
        return response()->json(['message' => 'Circuit not found'], 404);
      }
      catch(\Illuminate\Database\QueryException $e){
        return response()->json(['message' => 'Consult failure'], 500);
      }
    }


}
