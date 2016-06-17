<?php

namespace App\Http\Controllers;

use App\Circuit;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\User;
use App\Node;
use Carbon\Carbon;

/**
* Class CircuitsController
* @package App\Http\Controllers
*/

class CircuitsController extends Controller
{
  /**
  * GET /circuits
  * @return array
  */

  public function index()
  {
    return ['data' => Circuit::all()->toArray()];
  }

  /**
  * GET /circuits/{id}
  * @param integer $id
  * @return mixed
  */

  public function show($id)
  {
    try
    {
      return Circuit::findOrFail($id);
    }
    catch (ModelNotFoundException $e)
    {
      return response()->json([
      'error' => [
        'message' => 'Circuit not found'
      ]], 404);
    }
  }

  /**
  * POST /circuits
  * @param Request $request
  * @return \Illuminate\Http\JsonResponse
  */

  public function store(Request $request)
  {
    $this->validate($request, [
      'name'        => 'required',
      'status'      => 'required',
      'description' => 'required',
    ], [
      'name.required' => 'Please fill out the :attribute.'
    ]);

    $circuit = Circuit::create($request->all());

    return response()->json(['data' => $circuit->toArray()], 201, [
      'Location' => route('circuits.show', ['id' => $circuit->id])
    ]);
  }

  /**
  * PUT /circuits/{id}
  * @param Request $request
  * @param $id
  * @return mixed
  */

  public function update(Request $request, $id)
  {
    try
    {
      $circuit = Circuit::findOrFail($id);
    }
    catch (ModelNotFoundException $e)
    {
      return response()->json([
      'error' => [
        'message' => 'Circuit not found'
      ]], 404);
    }

    $this->validate($request, [
      'name'        => 'required',
      'status'      => 'required',
      'description' => 'required',
    ], [
      'name.required' => 'Please fill out the :attribute.'
    ]);

    $circuit->fill($request->all());

    $circuit->save();

    return ['data' => $circuit->toArray()];
  }

  /**
  * DELETE /circuits/{id}
  * @param $id
  * @return \Illuminate\Http\JsonResponse
  */

  public function destroy($id)
  {
    try
    {
      $circuit = Circuit::findOrFail($id);
    }
    catch (ModelNotFoundException $e)
    {
      return response()->json([
        'error' => [
          'message' => 'Circuit not found'
      ]], 404);
    }

    $control = $circuit->delete();

    return response()->json(['deleted' => $control], 200);

    // return response(null, 204);
  }

  public function getNextHint($circuit_id, $user_id)
  {
    // Verificar que el circuito exista

    try
    {
      $circuit = Circuit::findOrFail($circuit_id);
    }
    catch (ModelNotFoundException $e)
    {
      return response()->json([
      'error' => [
        'message' => 'Circuit not found'
      ]], 404);
    }

    // Verificar que el usuario exista

    try
    {
      $user = User::findOrFail($user_id);
    }
    catch (ModelNotFoundException $e)
    {
      return response()->json([
      'error' => [
        'message' => 'User not found'
      ]], 404);
    }

    // verificar que el usuario esté inscrito en el circuito

    try
    {
        $circuit -> users() -> findOrFail($user_id);
    }
    catch (ModelNotFoundException $e)
    {
      return response()->json([
      'error' => [
        'message' => 'User not found in circuit'
      ]], 404);
    }

    // Validar que se le pueda dar una nueva pista
    // NO TIENE UN NODO ABIERTO

    // Si tiene un nodo abierto (entonces no)
    $nodesOpened = $user -> nodesDiscovered() -> wherePivot('status', '<', '2') -> get();

    if(count($nodesOpened) > 0)
    {
      return response()->json([
      'error' => [
        'message' => 'User already has a discovered node'
      ]], 403);
    }

    // Si ya no hay mas nodos (entonces no)
    $pending = count($circuit -> nodes) - count($user -> nodesDiscovered);

    if($pending == 0)
    {
      return response()->json([
      'error' => [
        'message' => 'There are no more new nodes in this circuit to discover'
      ]], 403);
    }

    /*
    $count = count($user -> nodesDiscovered);

    if($count != 0)
    {
      return response()->json([
      'error' => [
        'message' => 'First hint of user was already used for this circuit'
      ]], 403);
    }
    */

    // calcular un nodo al azar
    // QUE NO HAYA SIDO RESPONDIDO

    $nodes = $circuit -> nodes;

    do
    {
      $nodeIndex = rand(0, count($nodes)-1);

      // $node = Node::find($nodes[$nodeIndex] -> id);
      $node = $nodes[$nodeIndex];

    } while ($user -> nodesDiscovered() -> wherePivot('node_id', $node -> id) -> first() != null);

    // genera un nodo-usuario en estado 0

    $user -> nodesDiscovered() -> attach($node, [
      'circuit_id'    => $circuit -> id,
      'status'        => 0,
      'date_status_0' => Carbon::now() -> toDateTimeString()
    ]);

    // retorna la información del primer nodo de este circuito para ese usuario

    return ['data' => [
      'node' => $node
    ]];
  }
}
