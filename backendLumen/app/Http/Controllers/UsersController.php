<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
* Class UsersController
* @package App\Http\Controllers
*/

class UsersController extends Controller
{
  /**
  * GET /users
  * @return array
  */

  public function index()
  {
    return ['data' => User::all()->toArray()];
  }

  /**
  * GET /users/{id}
  * @param integer $id
  * @return mixed
  */

  public function show($id)
  {
    try
    {
      return User::findOrFail($id);
    }
    catch (ModelNotFoundException $e)
    {
      return response()->json([
      'error' => [
        'message' => 'User not found'
      ]], 404);
    }
  }

  /**
  * POST /users
  * @param Request $request
  * @return \Illuminate\Http\JsonResponse
  */

  public function store(Request $request)
  {
    $this->validate($request, [
      'name'     => 'required',
      'lastname' => 'required',
      'email'    => 'required',
      'password' => 'required',
      'type'     => 'required',
    ], [
      'name.required' => 'Please fill out the :attribute.'
    ]);

    $user = User::create($request->all());

    $user -> password = $request -> password;
    $user -> save();

    return response()->json(['data' => $user->toArray()], 201, [
      'Location' => route('users.show', ['id' => $user->id])
    ]);
  }

  /**
  * PUT /users/{id}
  * @param Request $request
  * @param $id
  * @return mixed
  */

  public function update(Request $request, $id)
  {
    try
    {
      $user = User::findOrFail($id);
    }
    catch (ModelNotFoundException $e)
    {
      return response()->json([
      'error' => [
        'message' => 'User not found'
      ]], 404);
    }

    $this->validate($request, [
      'name'     => 'required',
      'lastname' => 'required',
      'email'    => 'required',
      // 'password' => 'required',
      'type'     => 'required',
    ], [
      'name.required' => 'Please fill out the :attribute.'
    ]);

    $user->fill($request->all());

    $user->save();

    return ['data' => $user->toArray()];
  }

  /**
  * DELETE /users/{id}
  * @param $id
  * @return \Illuminate\Http\JsonResponse
  */

  public function destroy($id)
  {
    try
    {
      $user = User::findOrFail($id);
    }
    catch (ModelNotFoundException $e)
    {
      return response()->json([
        'error' => [
          'message' => 'User not found'
      ]], 404);
    }

    $control = $user->delete();

    return response()->json(['deleted' => $control], 200);

    // return response(null, 204);

    /*
    return response()->json([
      'data' => $user -> toArray()], 200);
    */
  }
}
