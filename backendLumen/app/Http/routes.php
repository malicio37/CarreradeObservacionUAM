<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

////////////////////////////////////////////////////////////////////////////////

// Raíz
// ----

$app->get('/', function () use ($app) {
    return "Carrera de observación UAM";
});

////////////////////////////////////////////////////////////////////////////////

// Business logic API

// Get the next Hint of a Circuit to a User
$app->get('/circuits/{circuit_id:[\d]+}/nexthint/user/{user_id:[\d]+}', 'CircuitsController@getNextHint');

// Validate if a QR code matches with an specific Node
// {"qr": "xxxxxxxxx"}
$app->post('/nodes/{node_id:[\d]+}/validate/user/{user_id:[\d]+}', 'NodesController@validateQR');

// Validate if an Answer matches to an specific question of a User on a Node
// {"answer": "xxxxxxxxx"}
$app->post('/nodes/{node_id:[\d]+}/answer/user/{user_id:[\d]+}', 'NodesController@answer');

// TODO //

// Get the Ranking of Users with Nodes solved in Circuit
$app->get('/circuits/{circuit_id:[\d]+}/ranking', 'CircuitsController@getRanking');

// Get the nodes solved by a User in a Circuit
$app->get('/nodes/circuit/{circuit_id:[\d]+}/user/{user_id:[\d]+}', 'NodesController@getUserSolvedNodes');

////////////////////////////////////////////////////////////////////////////////

// Users
// -----

// http://localhost:8080/users
$app->get('/users', 'UsersController@index');

// http://localhost:8080/users/1
$app->get('/users/{id:[\d]+}', [
  'as' => 'users.show',
  'uses' => 'UsersController@show'
]);

// http://localhost:8080/users/1
// {"name": "The User", "lastname": "The Administrator", "email": "admin@admin.com", "type": "admin"}
$app->put('/users/{id:[\d]+}', 'UsersController@update');

// http://localhost:8080/users
// {"name": "Another User", "lastname": "Another Lastname", "email": "another@yet.mail", "password": "hola123", "type": "user"}
$app->post('/users', 'UsersController@store');

// http://localhost:8080/users/3
$app->delete('/users/{id:[\d]+}', 'UsersController@destroy');

////////////////////////////////////////////////////////////////////////////////

// Circuits
// --------

// http://localhost:8080/circuits
$app->get('/circuits', 'CircuitsController@index');

// http://localhost:8080/circuits/1
$app->get('/circuits/{id:[\d]+}', [
  'as' => 'circuits.show',
  'uses' => 'CircuitsController@show'
]);

// http://localhost:8080/circuits/3
// {"name": "Circuito #3a", "status": "disabled", "description": "This is the circuit #3 (edited)"}
$app->put('/circuits/{id:[\d]+}', 'CircuitsController@update');

// http://localhost:8080/circuits
// {"name": "Circuito #4", "status": "disabled", "description": "This is the circuit #4"}
$app->post('/circuits', 'CircuitsController@store');

// http://localhost:8080/circuits/4
$app->delete('/circuits/{id:[\d]+}', 'CircuitsController@destroy');

////////////////////////////////////////////////////////////////////////////////

// Nodes
// -----

// http://localhost:8080/nodes
$app->get('/nodes', 'NodesController@index');

// http://localhost:8080/nodes/1
$app->get('/nodes/{id:[\d]+}', [
  'as' => 'nodes.show',
  'uses' => 'NodesController@show'
]);

// http://localhost:8080/nodes/3
// {"name": "Nodo #1ab","description": "This is the node #1ab","code": "xxxxxxxxxab","latitude": "1.113","longitude": "1.223","hint": "Hint #1ab","status": "enabled", "circuit_id": "2"}
$app->put('/nodes/{id:[\d]+}', 'NodesController@update');

// http://localhost:8080/nodes
// {"name": "Nodo #4","description": "This is the node #4","code": "xxxxxxxxx4","latitude": "1.4","longitude": "1.4","hint": "Hint #4","status": "disabled", "circuit_id": "2"}
$app->post('/nodes', 'NodesController@store');

// http://localhost:8080/nodes/4
$app->delete('/nodes/{id:[\d]+}', 'NodesController@destroy');

////////////////////////////////////////////////////////////////////////////////

// Questions of Nodes
// ------------------

// http://localhost:8080/questions
$app->get('/questions', 'NodeQuestionsController@index');

// http://localhost:8080/questions/3
$app->get('/questions/{id:[\d]+}', [
  'as' => 'questions.show',
  'uses' => 'NodeQuestionsController@show'
]);

// http://localhost:8080/questions/3
// {"question": "Question 3 for node 1a","answer": "ca","node_id": "1"}
$app->put('/questions/{id:[\d]+}', 'NodeQuestionsController@update');

// http://localhost:8080/questions
// {"question": "Question 4 for node 2","answer": "d","node_id": "2"}
$app->post('/questions', 'NodeQuestionsController@store');

// http://localhost:8080/questions/7
$app->delete('/questions/{id:[\d]+}', 'NodeQuestionsController@destroy');

////////////////////////////////////////////////////////////////////////////////

// Inscriptions (users on circuits)

/*
$app->get('/inscriptions', 'InscriptionUsersCircuitsController@index');

$app->get('/inscriptions/{id:[\d]+}', [
  'as' => 'inscriptions.show',
  'uses' => 'InscriptionUsersCircuitsController@show'
]);

$app->put('/inscriptions/{id:[\d]+}', 'InscriptionUsersCircuitsController@update');

$app->post('/inscriptions', 'InscriptionUsersCircuitsController@store');

$app->delete('/inscriptions/{id:[\d]+}', 'InscriptionUsersCircuitsController@destroy');
*/

////////////////////////////////////////////////////////////////////////////////

// Nodes discovered (nodes by users on circuit)

/*
$app->get('/nodesdiscovered', 'NodesDiscoveredController@index');

$app->get('/nodesdiscovered/{id:[\d]+}', [
  'as' => 'nodesdiscovered.show',
  'uses' => 'NodesDiscoveredController@show'
]);

$app->put('/nodesdiscovered/{id:[\d]+}', 'NodesDiscoveredController@update');

$app->post('/nodesdiscovered', 'NodesDiscoveredController@store');

$app->delete('/nodesdiscovered/{id:[\d]+}', 'NodesDiscoveredController@destroy');
*/

////////////////////////////////////////////////////////////////////////////////
