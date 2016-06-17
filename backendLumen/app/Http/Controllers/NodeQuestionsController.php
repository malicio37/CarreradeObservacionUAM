<?php

namespace App\Http\Controllers;

use App\NodeQuestion;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
* Class NodeQuestionsController
* @package App\Http\Controllers
*/

class NodeQuestionsController extends Controller
{
  /**
  * GET /questions
  * @return array
  */

  public function index()
  {
    return ['data' => NodeQuestion::all()->toArray()];
  }

  /**
  * GET /questions/{id}
  * @param integer $id
  * @return mixed
  */

  public function show($id)
  {
    try
    {
      return NodeQuestion::findOrFail($id);
    }
    catch (ModelNotFoundException $e)
    {
      return response()->json([
      'error' => [
        'message' => 'NodeQuestion not found'
      ]], 404);
    }
  }

  /**
  * POST /questions
  * @param Request $request
  * @return \Illuminate\Http\JsonResponse
  */

  public function store(Request $request)
  {
    $this->validate($request, [
      'question'  => 'required',
      'answer'    => 'required',
      'node_id'   => 'required|exists:nodes,id',
    ], [
      'name.required' => 'Please fill out the :attribute.'
    ]);

    $question = NodeQuestion::create($request->all());

    return response()->json(['data' => $question->toArray()], 201, [
      'Location' => route('questions.show', ['id' => $question->id])
    ]);
  }

  /**
  * PUT /questions/{id}
  * @param Request $request
  * @param $id
  * @return mixed
  */

  public function update(Request $request, $id)
  {
    try
    {
      $question = NodeQuestion::findOrFail($id);
    }
    catch (ModelNotFoundException $e)
    {
      return response()->json([
      'error' => [
        'message' => 'NodeQuestion not found'
      ]], 404);
    }

    $this->validate($request, [
      'question'  => 'required',
      'answer'    => 'required',
      'node_id'   => 'required|exists:nodes,id',
    ], [
      'question.required' => 'Please fill out the :attribute.'
    ]);

    $question->fill($request->all());

    $question->save();

    return ['data' => $question->toArray()];
  }

  /**
  * DELETE /questions/{id}
  * @param $id
  * @return \Illuminate\Http\JsonResponse
  */

  public function destroy($id)
  {
    try
    {
      $question = NodeQuestion::findOrFail($id);
    }
    catch (ModelNotFoundException $e)
    {
      return response()->json([
        'error' => [
          'message' => 'NodeQuestion not found'
      ]], 404);
    }

    $control = $question->delete();

    return response()->json(['deleted' => $control], 200);

    // return response(null, 204);
  }
}
