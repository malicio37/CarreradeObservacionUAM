<?php

namespace App\Http\Controllers;

use App\Node;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\User;
use App\NodeQuestion;
use Carbon\Carbon;

/**
* Class NodesController
* @package App\Http\Controllers
*/

class NodesController extends Controller
{
  /**
  * GET /nodes
  * @return array
  */

  public function index()
  {
    return ['data' => Node::all()->toArray()];
  }

  /**
  * GET /nodes/{id}
  * @param integer $id
  * @return mixed
  */

  public function show($id)
  {
    try
    {
      return Node::findOrFail($id);
    }
    catch (ModelNotFoundException $e)
    {
      return response()->json([
      'error' => [
        'message' => 'Node not found'
      ]], 404);
    }
  }

  /**
  * POST /nodes
  * @param Request $request
  * @return \Illuminate\Http\JsonResponse
  */

  public function store(Request $request)
  {
    $this->validate($request, [
      'name'        => 'required',
      'description' => 'required',
      // 'code'        => 'required',
      'latitude'    => 'required',
      'longitude'   => 'required',
      'hint'        => 'required',
      'status'      => 'required',
      'circuit_id'  => 'required|exists:circuits,id',
    ], [
      'name.required' => 'Please fill out the :attribute.'
    ]);

    $request -> code = str_random(40);

    $node = Node::create($request->all());

    return response()->json(['data' => $node->toArray()], 201, [
      'Location' => route('nodes.show', ['id' => $node->id])
    ]);
  }

  /**
  * PUT /nodes/{id}
  * @param Request $request
  * @param $id
  * @return mixed
  */

  public function update(Request $request, $id)
  {
    try
    {
      $node = Node::findOrFail($id);
    }
    catch (ModelNotFoundException $e)
    {
      return response()->json([
      'error' => [
        'message' => 'Node not found'
      ]], 404);
    }

    $this->validate($request, [
      'name'        => 'required',
      'description' => 'required',
      'code'        => 'required',
      'latitude'    => 'required',
      'longitude'   => 'required',
      'hint'        => 'required',
      'status'      => 'required',
      'circuit_id'  => 'required|exists:circuits,id',
    ], [
      'name.required' => 'Please fill out the :attribute.'
    ]);

    $node->fill($request->all());

    $node->save();

    return ['data' => $node->toArray()];
  }

  /**
  * DELETE /nodes/{id}
  * @param $id
  * @return \Illuminate\Http\JsonResponse
  */

  public function destroy($id)
  {
    try
    {
      $node = Node::findOrFail($id);
    }
    catch (ModelNotFoundException $e)
    {
      return response()->json([
        'error' => [
          'message' => 'Node not found'
      ]], 404);
    }

    $control = $node->delete();

    return response()->json(['deleted' => $control], 200);

    // return response(null, 204);
  }

  public function validateQR(Request $request, $node_id, $user_id)
  {
    // Valor de QR (POST)

    $qr = $request->input('qr');

    if($qr == null)
    {
      return response()->json([
        'error' => [
          'message' => 'QR code data not found in request'
      ]], 404);
    }

    // Nodo exista

    try
    {
      $node = Node::findOrFail($node_id);
    }
    catch (ModelNotFoundException $e)
    {
      return response()->json([
        'error' => [
          'message' => 'Node not found'
      ]], 404);
    }

    // Usuario exista

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

    // Usuario tenga relación con ese Nodo

    $nodeDiscovered = $user -> nodesDiscovered() -> find($node_id);

    if($nodeDiscovered == null)
    {
      return response()->json([
        'error' => [
          'message' => 'User has not discovered this node yet'
      ]], 403);
    }

    // QR coincida

    if($qr != $node -> code)
    {
      return response()->json([
        'error' => [
          'message' => 'QR code does not match with selected node'
      ]], 400);
    }

    // Selecciona una pregunta para ese nodo

    $questions = $node -> questions;
    $questionIndex = rand(0, count($questions)-1);
    $selectedQuestion = $questions[$questionIndex];

    // Pasa estado a #1 + fecha + pregunta elegida

    $nodeDiscovered -> pivot -> status = 1;
    $nodeDiscovered -> pivot -> date_status_1 = Carbon::now();
    $nodeDiscovered -> pivot -> node_question_id = $selectedQuestion -> id;
    $nodeDiscovered -> pivot -> save();

    // retorna la información

    unset($node -> questions);
    unset($selectedQuestion -> answer);

    return ['data' => [
      'node' => $node,
      'question' => $selectedQuestion
    ]];
  }

  public function answer(Request $request, $node_id, $user_id)
  {
    // Valor answer (POST)

    $answer = $request->input('answer');

    if($answer == null)
    {
      return response()->json([
        'error' => [
          'message' => 'answer not found in request'
      ]], 404);
    }

    // Nodo exista

    try
    {
      $node = Node::findOrFail($node_id);
    }
    catch (ModelNotFoundException $e)
    {
      return response()->json([
        'error' => [
          'message' => 'Node not found'
      ]], 404);
    }

    // Usuario exista

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

    // Usuario tenga relación con ese Nodo

    $nodeDiscovered = $user -> nodesDiscovered() -> find($node_id);

    if($nodeDiscovered == null)
    {
      return response()->json([
        'error' => [
          'message' => 'User has not discovered this node yet'
      ]], 403);
    }

    // Load the question from node

    $questionId = $nodeDiscovered -> pivot -> node_question_id;

    try
    {
      $nodeQuestion = NodeQuestion::findOrFail($questionId);
    }
    catch (ModelNotFoundException $e)
    {
      return response()->json([
        'error' => [
          'message' => 'NodeQuestion not found'
      ]], 404);
    }

    $realAnswer = $nodeQuestion -> answer;

    // Answer coincida

    if($answer != $realAnswer)
    {
      return response()->json([
        'error' => [
          'message' => 'The answer does not match'
      ]], 400);
    }

    // Pasa estado a #2 + fecha

    $nodeDiscovered -> pivot -> status = 2;
    $nodeDiscovered -> pivot -> date_status_2 = Carbon::now();
    $nodeDiscovered -> pivot -> save();

    // retorna la información

    return ['data' => [
      'success' => 'ok'
    ]];
  }
}
