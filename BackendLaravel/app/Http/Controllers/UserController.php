<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

  ////////////////////////////////////////////////////////////////////////////////
  // Specific bussiness API


  /**
  * POST /users/email
  * @param email $email
  * @return mixed
  */
  public function getLogin(Request $data) {
    $request=json_decode($data->getContent());
    try
    {
        $response=(DB::table('users')->select('email')->where('email', '=', $request->email)->get());
        if(count($response) != 0){
          return response()->json(['data' => $response],200);
        }
        return response()->json(['message' => 'User not found'], 404);
      }
    catch(\Illuminate\Database\QueryException $e){
      return response()->json(['message' => 'Consult failure'], 500);
    }
    //{"email":"jmmejia@autonoma.edu.co"}
  }


  /**
  * POST /users/passwd
  * @param email $email
  * @param password $password
  * @return mixed
  */
   public function getLogin2(Request $data) {
     $request=json_decode($data->getContent());
     try{
         $response=  (DB::table('users')->select('id')->where('email', '=', $request->email)->where('password','=',sha1($request->password))->get());
         if(count($response) != 0){
           return response()->json(['data' => $response],200);
         }
         return response()->json(['message' => 'Password incorrect'], 404);
      }
     catch(\Illuminate\Database\QueryException $e){
       return response()->json(['message' => 'Consult failure'], 500);
     }
  }


  /**
  * GET /users/locationvisited/{user_id}/{circuit_id}
  * @param integer $user_id
  * @param integer $circuit_id
  * @return mixed
  */
  public function getLocationVisited($user_id, $circuit_id){
   /*"SELECT n.longitude, n.latitude, n.name FROM discoverednodes nd JOIN node n ON nd.node_id=n.id
   WHERE nd.status=2 AND nd.user_id=:user_id AND n.circuit_id=:circuit_id";
   */
   try{
       $response = DB::table('discoverednodes') ->join('nodes', 'discoverednodes.node_id','nodes.id')
                ->where('discoverednodes.status','=',2)
                ->where('discoverednodes.user_id','=',$user_id)
                ->where('nodes.circuit_id','=',$circuit_id)
                ->select('nodes.longitude','nodes.latitude','nodes.name')
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
  * GET /users
  */
  public function index()
  {
    try{
        $response=  (DB::table('users')->get());
        return response()->json(['data' => $response],200);
    }
    catch(\Illuminate\Database\QueryException $e){
        return response()->json(['message' => 'Consult failure'], 500);
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {

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
    try{
          $id = DB::table('users')->insertGetId(
          ['name' => $request->name,
          'lastname' => $request->lastname,
          'birthdate' => $request->birthdate,
          'email' => $request->email,
          'password' =>sha1($request->password),
          'color' => $request->color,
          'gender' => $request->gender,
          'type' => $request->type,
        ]);
        $response= (DB::table('users')->where('id', '=', $id)->get());
        if(count($response) != 0){
          return response()->json(['data' => $response],201);
        }
        return response()->json(['message' => 'failure to create User'], 404);
    }
    catch(\Illuminate\Database\QueryException $e){
      return response()->json(['message' => 'Consult failure'], 500);
    }
  }

  //{"name":"nn", "lastname":"nn","birthdate":"1984-01-28","email":"@algo","password":"@password","color":"#5454545","gender":"hombre","type":"user"}

  /**
   * Display the specified resource.
   * GET /users/{id}
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    try{
      $response = (DB::table('users')->where('id', '=', $id)->get());
      if(count($response) != 0){
        return response()->json(['data' => $response],200);
      }
      return response()->json(['message' => 'User not found'], 404);
    }
    catch(\Illuminate\Database\QueryException $e){
      return response()->json(['message' => 'Consult failure'], 500);
    }
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {

  }

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
      DB::table('users')->where('id', $id)->update(
      ['name' => $request->name, 'lastname'=> $request->lastname,'birthdate' => $request->birthdate,'email' => $request->email,'password' => $request->password,'color' => $request->color,'gender' => $request->gender,'type' => $request->type]);
      $response= (DB::table('users')->where('id', '=', $id)->get());
      if(count($response) != 0){
        return response()->json(['data' => $response],200);
      }
      return response()->json(['message' => 'User not found'], 404);
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
      $response= (DB::table('users')->where('id', '=', $id)->get());
      DB::table('users')->where('id', '=', $id)->delete();
      if(count($response) != 0){
        return response()->json(['message' => 'User deleted'],200);
      }
      return response()->json(['message' => 'User not found'], 404);
    }
    catch(\Illuminate\Database\QueryException $e){
      return response()->json(['message' => 'Consult failure'], 500);
    }
  }
}
