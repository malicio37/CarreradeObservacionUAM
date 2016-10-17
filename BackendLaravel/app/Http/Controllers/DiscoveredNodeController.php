<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

class DiscoveredNodeController extends Controller
{
  ////////////////////////////////////////////////////////////////////////////////
  // Specific bussiness API

  /**
  * POST /discoverednodes/showquestion
  * @param integer $user_id
  * @param integer $circuit_id
  * @return mixed
  */
  public function getUserQuestions(Request $data){
    /*
    "SELECT question.id, question.question from question INNER JOIN nodediscovered ON
		 question.id=nodediscovered.question_id INNER JOIN node on node.id=nodediscovered.node_id WHERE
		 node.circuit_id=:circuit_id AND nodediscovered.user_id=:user_id AND nodediscovered.status = 1";
    */
    $request=json_decode($data->getContent());
    try{
      $response= (DB::table('discoverednodes')->join('nodes','discoverednodes.node_id','=','nodes.id')->join('questions','questions.id','=','discoverednodes.question_id')
             ->where('nodes.circuit_id','=',$request->circuit_id)
             ->where('discoverednodes.user_id','=',$request->user_id)
             ->where('discoverednodes.status','=',1)
             ->select('questions.id','questions.question')->get()
             );
       return response()->json(['data' => $response],200);
     }
     catch(\Illuminate\Database\QueryException $e){
       return response()->json(['message' => 'Consult failure'], 500);
     }
  }
//{"user_id":1, "circuit_id":1}

    /**
    * POST /discoverednodes/tovisit
    * @param user_id $user_id
    * @param circuit_id $circuit_id
    * @return mixed
    */
    function getNotVisitedNodes(Request $data){
      /*
      "SELECT node.id FROM node WHERE node.id NOT IN (SELECT nodediscovered.node_id FROM nodediscovered
						WHERE node.circuit_id= :circuit_id AND nodediscovered.user_id=:user_id) AND node.circuit_id=:circuit_id";
      */
      try{
        $request=json_decode($data->getContent());
        $sql="SELECT nodes.id FROM nodes WHERE nodes.id NOT IN (SELECT discoverednodes.node_id FROM discoverednodes
                WHERE nodes.circuit_id = " . $request->circuit_id . " AND discoverednodes.user_id =".
                $request->user_id . " ) AND nodes.circuit_id =" . $request->circuit_id;

          $response = DB::select($sql);
          //return $response;
          if(count($response) != 0){
            return response()->json(['data' => $response],200);
          }
          return response()->json(['message' => 'there are not nodes to visit'], 404);
        }
        catch(\Illuminate\Database\QueryException $e){
          return response()->json(['message' => 'Consult failure'], 500);
        }
    }

    //{"user_id":1, "circuit_id":1}


    /**
    * GET /discoverednodes/visited
    * @param user_id $user_id
    * @param circuit_id $circuit_id
    * @return mixed
    */
    public function getVisitedNodes($user_id, $circuit_id){
      /*
      "SELECT n.id FROM node n INNER JOIN nodediscovered d ON n.id=d.node_id WHERE n.circuit_id=:circuit_id AND d.user_id=:user_id";
      */
      try{
        $response = (DB::table('nodes')->join('discoverednodes','discoverednodes.node_id','=','nodes.id')
            ->where('nodes.circuit_id','=',$circuit_id)
            ->where('discoverednodes.user_id','=',$user_id)
            ->select('nodes.id')->get()
        );
        if(count($response) != 0){
          return response()->json(['data' => $response],200);
        }
        return response()->json(['message' => 'there are not visited nodes'], 404);
      }
      catch(\Illuminate\Database\QueryException $e){
        return response()->json(['message' => 'Consult failure'], 500);
      }
    }



    /**
    * POST /discoverednodes/getid
    * @param question_id id de la pregunta
    * @param user_id id del usuario
    * @param circuit_id id de la carrera
    * @return json
    */
    public function getNodediscoveredId(Request $data) {
      /*
      "SELECT nodediscovered.id, nodediscovered.node_id, nodediscovered.statusDate1, nodediscovered.statusDate2
	  				FROM nodediscovered JOIN node ON nodediscovered.node_id=node.id
	 				WHERE nodediscovered.user_id=:user_id AND nodediscovered.question_id = :question_id AND node.circuit_id=:circuit_id"
      */
      try{
        $request=json_decode($data->getContent());
        $sql="SELECT discoverednodes.id, discoverednodes.node_id, discoverednodes.statusDate1, discoverednodes.statusDate2
  	  				FROM discoverednodes JOIN nodes ON discoverednodes.node_id=nodes.id
  	 				WHERE discoverednodes.user_id= " . $request->user_id. " AND discoverednodes.question_id = " .
             $request->question_id .  " AND nodes.circuit_id= " . $request->circuit_id;

        $response = DB::select($sql);
        if(count($response) != 0){
          return response()->json(['data' => $response],200);
        }
        return response()->json(['message' => 'there are not discovered nodes'], 404);
      }
      catch(\Illuminate\Database\QueryException $e){
        return response()->json(['message' => 'Consult failure'], 500);
      }

    }
//{"user_id":1, "circuit_id":1, "question_id":27}


    /**
    * GET /discoverednodes/{user_id}/{node_id}
    * @param integer $user_id
    * @param integer $node_id
    * @return mixed
    */
    public function getNodeDiscoveredByUserNode($user_id, $node_id){
    	 //$sql = "SELECT * FROM discoverednodes WHERE user_id=:user_id AND node_id=:node_id";
       try{
         $response = (DB::table('discoverednodes')->where('user_id', '=', $user_id)
                ->where('node_id','=',$node_id)->get());
          if(count($response) != 0){
            return response()->json(['data' => $response],200);
          }
          return response()->json(['message' => 'there are not discovered nodes'], 404);
        }
        catch(\Illuminate\Database\QueryException $e){
          return response()->json(['message' => 'Consult failure'], 500);
        }
     }



     /**
    * GET /discoverednodes/score/{user_id}/{circuit_id}
    * @param integer $user_id
    * @param integer $circuit_id
    * @return mixed
    */
    public function getPuntuacion($user_id, $circuit_id){
     /*
    	 $sql = "SELECT n.name FROM nodediscovered d JOIN node n ON d.node_id=n.id WHERE d.user_id=:user_id AND
    	 					n.circuit_id=:circuit_id AND d.status=2";
      */
      try{
        $response = (DB::table('nodes')->join('discoverednodes','discoverednodes.node_id','=','nodes.id')
            ->where('nodes.circuit_id','=',$circuit_id)
            ->where('discoverednodes.user_id','=',$user_id)
            ->where('discoverednodes.status','=',2)
            ->select('nodes.name')->get()
        );
        return response()->json(['data' => $response],200);
      }
      catch(\Illuminate\Database\QueryException $e){
        return response()->json(['message' => 'Consult failure'], 500);
      }
    }
////////////////////////////////////////////////////////////////////////////////
// Standar API

  /**
   * GET /discoverednodes
   */
    public function index()
    {
      try{
        $response = (DB::table('discoverednodes')->get());
        return response()->json(['data' => $response],200);
      }
      catch(\Illuminate\Database\QueryException $e){
        return response()->json(['message' => 'Consult failure'], 500);
      }
    }

    /**
     * Display the specified resource.
     * GET /discoverednodes/{id}
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      try{
        $response = (DB::table('discoverednodes')->where('id', '=', $id)->get());
        if(count($response) != 0){
          return response()->json(['data' => $response],200);
        }
        return response()->json(['message' => 'DiscoveredNode not found'], 404);
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
        $id = DB::table('discoverednodes')->insertGetId([
        'node_id' => $request->node_id,
        'user_id' => $request->user_id,
        'question_id' => $request->question_id,
        'status' => $request->status,
        'statusDate1' => $request->statusDate1,
        'statusDate2' => $request->statusDate2,
        'statusDate3' => $request->statusDate3,
        ]);
        $response = (DB::table('discoverednodes')->where('id', '=', $id)->get());
        if(count($response) != 0){
          return response()->json(['data' => $response],201);
        }
        return response()->json(['message' => 'failure to create DiscoveredNode'], 404);
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
        DB::table('discoverednodes')->where('id', $id)->update(
        ['node_id' => $request->node_id, 'user_id'=> $request->user_id,'status' => $request->status,'statusDate1' => $request->statusDate1,'statusDate2' => $request->statusDate2,'statusDate3' => $request->statusDate3]);
        $response = (DB::table('discoverednodes')->where('id', '=', $id)->get());
        if(count($response) != 0){
          return response()->json(['data' => $response],200);
        }
        return response()->json(['message' => 'DiscoveredNode not found'], 404);
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
        $response = (DB::table('discoverednodes')->where('id', '=', $id)->get());
        DB::table('discoverednodes')->where('id', '=', $id)->delete();
        if(count($response) != 0){
          return response()->json(['message' => 'DiscoveredNode deleted'],200);
        }
        return response()->json(['message' => 'DiscoveredNode not found'], 404);
      }
      catch(\Illuminate\Database\QueryException $e){
        return response()->json(['message' => 'Consult failure'], 500);
      }
    }
}
