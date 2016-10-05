<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
//Specific API bussiness
Route::get('/', function () {
    return "Carrera de observación UAM";
});

Route::post('/users/email', 'UserController@getLogin');
Route::post('/users/passwd', 'UserController@getLogin2');
Route::post('/inscriptions/user', 'CircuitController@getCircuitInscripted');
Route::post('/nodes/showhint', 'NodeController@getUserHints');
Route::post('/discoverednodes/showquestion', 'DiscoveredNodeController@getUserQuestions');
Route::post('/discoverednodes/tovisit', 'DiscoveredNodeController@getNotVisitedNodes');
Route::get('/discoverednodes/visited/{user_id}/{circuit_id}', 'DiscoveredNodeController@getVisitedNodes');
Route::get('/questions/node/{id}', 'QuestionController@getNodeQuestions');
Route::post('/questions/validate', 'QuestionController@validateResponse');
Route::post('/discoverednodes/getid', 'DiscoveredNodeController@getNodediscoveredId');
Route::post('/nodes/validate', 'NodeController@validateNodeCode');
Route::get('/discoverednodes/{user_id}/{node_id}', 'DiscoveredNodeController@getNodeDiscoveredByUserNode');
Route::get('/discoverednodes/score/{user_id}/{circuit_id}', 'DiscoveredNodeController@getPuntuacion');
Route::get('/circuits/score/{circuit_id}', 'CircuitController@getTotalNodes');
Route::get('/circuits/totalscore/{circuit_id}', 'CircuitController@getTotalScore');
Route::get('/users/locationvisited/{user_id}/{circuit_id}', 'UserController@getLocationVisited');

//API estándar User
Route::get('/users', 'UserController@index');
Route::get('/users/{id}', 'UserController@show');
Route::post('/users', 'UserController@store');
Route::put('/users/{id}', 'UserController@update');
Route::delete('/users/{id}', 'UserController@destroy');

//API estándar circuit
Route::get('/circuits', 'CircuitController@index');
Route::get('/circuits/{id}', 'CircuitController@show');
Route::post('/circuits', 'CircuitController@create');
Route::put('/circuits/{id}', 'CircuitController@update');
Route::delete('/circuits/{id}', 'CircuitController@destroy');

//API estándar node
Route::get('/nodes', 'NodeController@index');
Route::get('/nodes/{id}', 'NodeController@show');
Route::post('/nodes', 'NodeController@create');
Route::put('/nodes/{id}', 'NodeController@update');
Route::delete('/nodes/{id}', 'NodeController@destroy');

//API estándar question
Route::get('/questions', 'QuestionController@index');
Route::get('/questions/{id}', 'QuestionController@show');
Route::post('/questions', 'QuestionController@store');
Route::put('/questions/{id}', 'QuestionController@update');
Route::delete('/questions/{id}', 'QuestionController@destroy');

//API estándar inscription
Route::get('/inscriptions', 'InscriptionController@index');
Route::get('/inscriptions/{id}', 'InscriptionController@show');
Route::post('/inscriptions', 'InscriptionController@create');
Route::put('/inscriptions/{id}', 'InscriptionController@update');
Route::delete('/inscriptions/{id}', 'InscriptionController@destroy');

//API estándar discoveredNodes
Route::get('/discoverednodes', 'DiscoveredNodeController@index');
Route::get('/discoverednodes/{id}', 'DiscoveredNodeController@show');
Route::post('/nsapi_response_headersDiscovered', 'DiscoveredNodeController@create');
Route::put('/discoverednodes/{id}', 'DiscoveredNodeController@update');
Route::delete('/discoverednodes/{id}', 'DiscoveredNodeController@destroy');
