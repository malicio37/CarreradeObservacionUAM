<?php
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim(array(
	'debug' => true,
  'MODE'  => 'development'
));

$request  = $app -> request();
$response = $app -> response();


$response -> headers -> set('Access-Control-Allow-Origin', '*');
$response -> headers -> set('Access-Control-Allow-Methods', "POST, GET, OPTIONS, PATCH, PUT, DELETE");
$response -> headers -> set('Access-Control-Allow-Headers', 'X-PINGOTHER');
$response -> headers -> set('Access-Control-Max-Age', '1728000');
$response -> headers -> set('Access-Control-Allow-Headers', 'Authorization');

define('BD_SERVIDOR', 'localhost');
define('BD_NOMBRE', 'carrera');
define('BD_USUARIO', 'root');
define('BD_PASSWORD', '');


$db = new PDO('mysql:host=' . BD_SERVIDOR . ';dbname=' . BD_NOMBRE . ';charset=utf8', BD_USUARIO, BD_PASSWORD);

$db -> setAttribute(PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION);


////////////////////////////////////////////////////////////////////////////////

/**
 * Url de inicio del backend
 */
$app->get('/', function() {
            echo "Pagina de gestión API REST de Carrea de Observación UAM.";
        });

////////////////////////////////////////////////////////////////////////////////

// API REST específica de la aplicación

/**
 * Validar los datos el usuario, verificar que el usuario exista
 */
$app->post('/users/email', 'getLogin');

/**
 * Validar la contraseña proporcionada por el usuario
 */
$app->post('/users/passwd', 'getLogin2');

/**
 * Filtrar carreras en las que el usuario se encuentra inscrito y la carrera se encuentra activa
 */
$app->post('/inscriptions/user', 'getCircuitInscripted');


/**
 * Mostrar las pistas activas de usuario en la carrera especificada
 */
$app->post('/nodes/showhint', 'getUserHints');

/**
 * Mostrar las preguntas activas de usuario en la carrera especificada
 */
$app->post('/discoveredNodes/showquestion', 'getUserQuestions');


/**
 * Filtrar todos los nodos que corresponden a la carrera que aùn no han sido visitados por el usuario
 */
 $app->post('/discoveredNodes/tovisit', 'getNotVisitedNodes');

 /**
  * Filtrar todos los nodos que corresponden a la carrera que han descubiertos
  * @param  user_id   el id del usuario
  * @param  circuit_id  el id de la carrera
  */
  $app->get('/discoveredNodes/visited/:user_id/:circuit_id', 'getVisitedNodes');

	/*
	 * Filtrar todos las preguntas de un nodo especìfico
	 * @param  node_id   el id del nodo
	 */
	 $app->get('/questions/node/:id', 'getNodeQuestions');



/**
 * Valida el valor de respuesta proporcionado por el usuario
 */
 $app->post('/questions/validate', 'validateResponse');

 /**
 * obtener el id del node descubierto del cual se tienen unos parámetros
 */
 $app->post('/discoveredNodes/getid', 'getNodediscoveredId');


 /**
 * Validar el código QR que corresponda al código del nodo
 */
$app->post('/nodes/validate', 'validateNodeCode');

/**
* Obtener el id de nodediscovered a partir del user_id y node_id
* @param integer $user_id
* @param integer $node_id
*/
$app->get('/discoveredNodes/:user_id/:node_id', 'getNodeDiscoveredByUserNode');


/**
* Obtener la cantidad de nodos en estado 2 de un usuario en una carrera específica
* @param integer $user_id
* @param integer $circuit_id
*/
$app->get('/discoveredNodes/score/:user_id/:circuit_id', 'getPuntuacion');


/**
* Obtener el total de nodos que tiene una carrera
* @param integer $circuit_id
* @return mixed
*/
$app->get('/circuits/score/:circuit_id', 'getTotalNodes');

/**
* Obtener la puntuación general de una carrera
* @param integer $circuit_id
*/
$app->get('/circuits/totalscore/:circuit_id', 'getTotalScore');


/**
* Obtener las coordenadas de los nodos visitados en estado 2 de un usuario específico
* @param integer $user_id
* @param integer $circuit_id
*/
$app->get('/users/locationvisited/:user_id/:circuit_id', 'getLocationVisited');


////////////////////////////////////////////////////////////////////////////////
//API estándar de nodos
$app->get('/nodes', 'getNodes');
$app->get('/nodes/:id', 'getNode');
$app->post('/nodes', 'addNode');
$app->put('/nodes/:id', 'updateNode');
$app->delete('/nodes/:id', 'deleteNode');

////////////////////////////////////////////////////////////////////////////////
//API estándar de carreras
$app->get('/circuits', 'getCircuits');
$app->get('/circuits/:id', 'getCircuit');
$app->post('/circuits', 'addCircuit');
$app->put('/circuits/:id', 'updateCircuit');
$app->delete('/circuits/:id', 'deleteCircuit');

////////////////////////////////////////////////////////////////////////////////
//API estándar de usuarios
$app->get('/users', 'getUsers');
$app->get('/users/:email', 'getUser');
$app->post('/users', 'addUser');
$app->put('/users/:id', 'updateUser');
$app->delete('/users/:id', 'deleteUser');

////////////////////////////////////////////////////////////////////////////////
//API estándar de preguntas
$app->get('/questions', 'getQuestions');
$app->get('/questions/:id', 'getQuestion');
$app->post('/questions', 'addQuestion');
$app->put('/questions/:id', 'updateQuestion');
$app->delete('/questions/:id', 'deleteQuestion');

////////////////////////////////////////////////////////////////////////////////
//API estándar de inscripciones
$app->get('/inscriptions', 'getInscriptions');
$app->get('/inscriptions/:id', 'getInscription');
$app->post('/inscriptions', 'addInscription');
$app->put('/inscriptions/:id', 'updateInscription');
$app->delete('/inscriptions/:id', 'deleteInscription');

////////////////////////////////////////////////////////////////////////////////
//API estándar de nodos descubiertos
$app->get('/discoveredNodes', 'getNodesDiscovered');
$app->get('/discoveredNodes/:id', 'getNodeDiscovered');
$app->post('/discoveredNodes', 'addNodeDiscovered');
$app->put('/discoveredNodes/:id', 'updateNodeDiscovered');
$app->delete('/discoveredNodes/:id', 'deleteNodeDiscovered');

////////////////////////////////////////////////////////////////////////////////
// Implementación de la API específica para la aplicación

/**
* POST /users/email
* @param email $email
* @return mixed
*/
function getLogin() {
 global $db, $request;
	 $user = json_decode($request->getBody());
 	 $sql = "SELECT email FROM users WHERE email=:email";
	 try {
			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("email", $user->email);
			 $stmt->execute();
			 $user = $stmt->fetchObject();
			 echo json_encode($user);
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}
//{"email":"jmmejia@autonoma.edu.co"}



/**
* POST /users/passwd
* @param email $email
* @param password $password
* @return mixed
*/
 function getLogin2() {
	global $db, $request;
    $user = json_decode($request->getBody());
	$sql = "SELECT id FROM users WHERE email=:email AND password=SHA1(:password)";
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam("email", $user->email);
				$stmt->bindParam("password", $user->password);
        $stmt->execute();
				$user = $stmt->fetchObject();
        echo json_encode($user);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
//{"email":"jmmejia@autonoma.edu.co", "password":"123"}



/**
* POST /inscriptions/user
* @param user_id
* @return mixed
*/
function getCircuitInscripted(){
 global $db, $request, $response;
 $inscription = json_decode($request->getBody());
		 $sql = "SELECT circuit.id, circuit.name FROM circuits INNER JOIN (SELECT circuit_id FROM inscriptions
						 WHERE user_id=:user_id)AS a ON circuit.id=a.circuit_id WHERE circuit.status=1";
		 try {
				$stmt = $db->prepare($sql);
				$stmt->bindParam("user_id", $inscription->user_id);
				$stmt->execute();
				$inscription = $stmt->fetchAll(PDO::FETCH_OBJ);
				$response->write( json_encode($inscription));
		} catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
 }
//{"user_id":1}


/**
* POST /nodesdiscovered/nodes
* @param user_id $user_id
* @param circuit_id $circuit_id
* @return mixed
*/
function getNotVisitedNodes(){
 global $db, $request, $response;
 $inscription = json_decode($request->getBody());
		$sql = "SELECT node.id FROM nodes WHERE node.id NOT IN (SELECT nodediscovered.node_id FROM discoveredNodes
						WHERE node.circuit_id= :circuit_id AND nodediscovered.user_id=:user_id) AND node.circuit_id=:circuit_id";
		try {
			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("user_id", $inscription->user_id);
			 $stmt->bindParam("circuit_id", $inscription->circuit_id);
			 $stmt->execute();
			 $nodes = $stmt->fetchAll(PDO::FETCH_OBJ);
			 $response->write( json_encode($nodes));
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
 }
//{"user_id":1, "circuit_id":1}


/**
* POST /nodesdiscovered/visited
* @param user_id $user_id
* @param circuit_id $circuit_id
* @return mixed
*/
function getVisitedNodes($user_id, $circuit_id){
 global $db, $request, $response;
 		$sql = "SELECT n.id FROM nodes n INNER JOIN discoveredNodes d ON n.id=d.node_id WHERE n.circuit_id=:circuit_id AND d.user_id=:user_id";
		try {
			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("user_id", $user_id);
			 $stmt->bindParam("circuit_id", $circuit_id);
			 $stmt->execute();
			 $inscription = $stmt->fetchAll(PDO::FETCH_OBJ);
			 $response->write( json_encode($inscription));
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
 }
//{"user_id":1, "circuit_id":1}


/**
* POST /nodes/showhint
* @param integer $user_id
* @param integer $circuit_id
* @return mixed
*/
function getUserHints(){
	global $db, $request;
		 $hint = json_decode($request->getBody());
		 $sql = "SELECT n.id, n.hint FROM nodes n JOIN (SELECT * FROM discoveredNodes d WHERE d.user_id=:user_id AND d.status= 0) AS t
		 ON n.id= t.node_id WHERE n.circuit_id=:circuit_id";
     try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam("user_id", $hint ->user_id);
				$stmt->bindParam("circuit_id", $hint->circuit_id);
        $stmt->execute();
        $hint = $stmt->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($hint);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
 }
//{"user_id":1, "circuit_id":}


/**
* POST /nodesdiscovered/showquestion
* @param integer $user_id
* @param integer $circuit_id
* @return mixed
*/
function getUserQuestions(){
	global $db, $request;
		 $question = json_decode($request->getBody());
		 $sql = "SELECT question.id, question.question from questions INNER JOIN discoveredNodes ON
		 question.id=nodediscovered.question_id INNER JOIN nodes on node.id=nodediscovered.node_id WHERE
		 node.circuit_id=:circuit_id AND nodediscovered.user_id=:user_id AND nodediscovered.status = 1";
     try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam("user_id", $question ->user_id);
				$stmt->bindParam("circuit_id", $question->circuit_id);
        $stmt->execute();
        $question = $stmt->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($question);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
 }
//{"user_id":1, "circuit_id":}


/**
* GET /questions/node/{id}
* @param integer $node_id
* @return mixed
*/
function getNodeQuestions($node_id){
 global $db, $response;
		$sql = "SELECT * FROM questions WHERE node_id=:node_id";
		try {
			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("node_id", $node_id);
			 $stmt->execute();
       $question = $stmt->fetchAll(PDO::FETCH_OBJ);
			 echo json_encode($question);
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}



/**
* POST /questions/validate
* @param question_id id de la pregunta
* @param response respuesta
* @return mixed
*/
function validateResponse() {
 global $db, $request;
	 //si status = 1 requiere un update antes de hacer el insert
	 $question = json_decode($request->getBody());
 	 $sql = "SELECT id FROM questions WHERE id=:id AND answer=:answer";
	 try {
			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("id", $question->question_id);
			 $stmt->bindParam("answer", $question->answer);
			 $stmt->execute();
			 $question = $stmt->fetchObject();
			 echo json_encode($question);
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}
//{"question_id": 2, "response":"jmmejia@autonoma.edu.co"}


/**
* POST /nodesdiscovered/getid
* @param question_id id de la pregunta
* @param user_id id del usuario
* @param circuit_id id de la carrera
* @return json
*/
function getNodediscoveredId() {
 global $db, $request;
	 $nodediscovered = json_decode($request->getBody());
 	 $sql = "SELECT nodediscovered.id, nodediscovered.node_id, nodediscovered.statusDate1, nodediscovered.statusDate2
	  				FROM discoveredNodes JOIN nodes ON nodediscovered.node_id=node.id
	 				WHERE nodediscovered.user_id=:user_id AND nodediscovered.question_id = :question_id AND node.circuit_id=:circuit_id";
	 try {
			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("user_id", $nodediscovered->user_id);
			 $stmt->bindParam("question_id", $nodediscovered->question_id);
			 $stmt->bindParam("circuit_id", $nodediscovered->circuit_id);
			 $stmt->execute();
			 $nodediscovered = $stmt->fetchObject();
			 echo json_encode($nodediscovered);
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}




/**
* POST /nodes/validate
* @param id id del nodo
* @param code código QR
* @return mixed
*/
function validateNodeCode() {
 global $db, $request;
	 $node = json_decode($request->getBody());
 	 $sql = "SELECT id FROM nodes WHERE id=:id AND code=:code";
	 try {
			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("id", $node->node_id);
			 $stmt->bindParam("code", $node->code);
			 $stmt->execute();
			 $node = $stmt->fetchObject();
			 echo json_encode($node);
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}
//{"node_id": 2, "code":"0.123456768"}


/**
* GET /nodesDiscovered/{user_id}/{node_id}
* @param integer $user_id
* @param integer $node_id
* @return mixed
*/
function getNodeDiscoveredByUserNode($user_id, $node_id){
 global $db, $response;
	 $sql = "SELECT * FROM discoveredNodes WHERE user_id=:user_id AND node_id=:node_id";
	 try {
			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("user_id", $user_id);
			 $stmt->bindParam("node_id", $node_id);
			 $stmt->execute();
			 $nodeDiscovered = $stmt->fetchObject();
			 echo json_encode($nodeDiscovered);
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}



/**
* GET /nodesdiscovered/score/{user_id}/{circuit_id}
* @param integer $user_id
* @param integer $circuit_id
* @return mixed
*/
function getPuntuacion($user_id, $circuit_id){
 global $db, $response;
	 $sql = "SELECT n.name FROM discoveredNodes d JOIN nodes n ON d.node_id=n.id WHERE d.user_id=:user_id AND
	 					n.circuit_id=:circuit_id AND d.status=2";
	 try {
			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("user_id", $user_id);
			 $stmt->bindParam("circuit_id", $circuit_id);
			 $stmt->execute();
			 $nodeDiscovered = $stmt->fetchAll(PDO::FETCH_OBJ);
			 echo json_encode($nodeDiscovered);
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}


/**
* GET /circuits/score/{circuit_id}
* @param integer $circuit_id
* @return mixed
*/
function getTotalNodes($circuit_id){
 global $db, $response;
	 $sql = "SELECT COUNT(id) as total FROM nodes n WHERE n.circuit_id=:circuit_id";
	 try {
			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("circuit_id", $circuit_id);
			 $stmt->execute();
			 $node = $stmt->fetchObject();
			 echo json_encode($node);
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}



/**
* GET /circuits/totalScore
* @param integer $circuit_id
* @return mixed
*/
function getTotalScore($circuit_id){
 global $db, $response;
	 $sql = "SELECT COUNT(u.id) AS cantidad, u.email FROM discoveredNodes nd JOIN user u ON
	  nd.user_id=u.id JOIN nodes n ON nd.node_id=n.id WHERE nd.status=2 AND n.circuit_id= :circuit_id GROUP BY nd.user_id DESC";
	 try {
			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("circuit_id", $circuit_id);
			 $stmt->execute();
       $score = $stmt->fetchAll(PDO::FETCH_OBJ);
			 echo json_encode($score);
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}



/**
* GET /users/locationvisited/{user_id}
* @param integer $user_id
* @param integer $circuit_id
* @return mixed
*/
function getLocationVisited($user_id, $circuit_id){
 global $db, $response;
	 $sql = "SELECT n.longitude, n.latitude, n.name FROM discoveredNodes nd JOIN nodes n ON nd.node_id=n.id
	 				 WHERE nd.status=2 AND nd.user_id=:user_id AND n.circuit_id=:circuit_id";
	 try {
			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("user_id", $user_id);
			 $stmt->bindParam("circuit_id", $circuit_id);
			 $stmt->execute();
       $location = $stmt->fetchAll(PDO::FETCH_OBJ);
			 echo json_encode($location);
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}


///////////////////////////////////////////////////////////////////////////////
//Implementación API básica de las entidades de la aplicación, GETALL

/**
* GET /nodes
* @return array
*/
function getNodes(){
	global $db, $response;
    $sql = "select * FROM nodes ORDER BY id";
    try {
        $stmt = $db->query($sql);
        $nodes = $stmt->fetchAll(PDO::FETCH_OBJ);
        $response->write( json_encode($nodes));
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


/**
* GET /circuits
* @return array
*/
function getCircuits(){
	global $db, $response;
    $sql = "select * FROM circuits ORDER BY id";
    try {
        $stmt = $db->query($sql);
        $circuits = $stmt->fetchAll(PDO::FETCH_OBJ);
        $response->write( json_encode($circuits));
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

/**
* GET /users
* @return array
*/
function getUsers(){
	global $db, $response;
    $sql = "select * FROM users ORDER BY id";
    try {
        $stmt = $db->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        $response->write( json_encode($users));
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

/**
* GET /Questions
* @return array
*/
function getQuestions(){
	global $db, $response;
    $sql = "select * FROM questions ORDER BY id";
    try {
        $stmt = $db->query($sql);
        $questions = $stmt->fetchAll(PDO::FETCH_OBJ);
        $response->write( json_encode($questions));
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

/**
* GET /inscriptions
* @return array
*/
function getInscriptions(){
	global $db, $response;
    $sql = "select * FROM inscriptions ORDER BY id";
    try {
        $stmt = $db->query($sql);
        $inscriptions = $stmt->fetchAll(PDO::FETCH_OBJ);
        $response->write( json_encode($inscriptions));
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

/**
* GET /NodesDiscovered
* @return array
*/
function getNodesDiscovered(){
	global $db, $response;
    $sql = "select * FROM discoveredNodes ORDER BY id";
    try {
        $stmt = $db->query($sql);
        $nodesDiscovered = $stmt->fetchAll(PDO::FETCH_OBJ);
        $response->write( json_encode($nodesDiscovered));
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

///////////////////////////////////////////////////////////////////////////////
//Implementación API básica de las entidades de la aplicación, GET específico

/**
* GET /nodes/{id}
* @param integer $id
* @return mixed
*/
function getNode($id){
	global $db;
     $sql = "SELECT * FROM nodes WHERE id=:id";
     try {

        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $node = $stmt->fetchObject();

        echo json_encode($node);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
 }

 /**
 * GET /circuits/{id}
 * @param integer $id
 * @return mixed
 */
 function getCircuit($id){
 	global $db;
      $sql = "SELECT * FROM circuits WHERE id=:id";
      try {
         $stmt = $db->prepare($sql);
         $stmt->bindParam("id", $id);
         $stmt->execute();
         $circuit = $stmt->fetchObject();
         echo json_encode($circuit);
     } catch(PDOException $e) {
         echo '{"error":{"text":'. $e->getMessage() .'}}';
     }
  }

	/**
  * GET /users/{id}
  * @param integer $id
  * @return mixed
  */
	function getUser($id){
		global $db;
	     $sql = "SELECT * FROM users WHERE id=:id";
	     try {

	        $stmt = $db->prepare($sql);
	        $stmt->bindParam("id", $id);
	        $stmt->execute();
	        $user = $stmt->fetchObject();
	        echo json_encode($user);
	    } catch(PDOException $e) {
	        echo '{"error":{"text":'. $e->getMessage() .'}}';
	    }
	 }

	 /**
   * GET /questions/{id}
   * @param integer $id
   * @return mixed
   */
function getQuestion($id){
	 	global $db, $response;
	     $sql = "SELECT * FROM questions WHERE id=:id";
			 try {
          $stmt = $db->prepare($sql);
          $stmt->bindParam("id", $id);
          $stmt->execute();
          $question = $stmt->fetchObject();
          echo json_encode($question);
      } catch(PDOException $e) {
          echo '{"error":{"text":'. $e->getMessage() .'}}';
      }
	}

	 /**
   * GET /inscriptions/{id}
   * @param integer $id
   * @return mixed
   */
	 function getInscription($id){
		global $db, $response;
			 $sql = "SELECT * FROM inscriptions WHERE id=:id";
			 try {
					 $stmt = $db->prepare($sql);
					 $stmt->bindParam("id", $id);
					 $stmt->execute();
					 $inscription = $stmt->fetchObject();
					 echo json_encode($inscription);
			 } catch(PDOException $e) {
					 echo '{"error":{"text":'. $e->getMessage() .'}}';
			 }
	 }

	 /**
	 * GET /nodesDiscovered/{id}
	 * @param integer $id
	 * @return mixed
	 */
	 function getNodeDiscovered($id){
	  global $db, $response;
	 		$sql = "SELECT * FROM discoveredNodes WHERE id=:id";
	 		try {
	 				$stmt = $db->prepare($sql);
	 				$stmt->bindParam("id", $id);
	 				$stmt->execute();
	 				$nodeDiscovered = $stmt->fetchObject();
	 				echo json_encode($nodeDiscovered);
	 		} catch(PDOException $e) {
	 				echo '{"error":{"text":'. $e->getMessage() .'}}';
	 		}
	 }



////////////////////////////////////////////////////////////////////////////
//Implementación API básica de las entidades de la aplicación, POST (Crear)

	 /**
	 * POST /nodes
	 * @param Request $request
	 * @return JsonResponse
	 */
	 function addNode() {
	  global $db, $request;
	 	 //si status = 1 requiere un update antes de hacer el insert
	 	 $node = json_decode($request->getBody());
	  	 $sql = "INSERT INTO nodes (name, description, code, latitude, longitude, hint, circuit_id) VALUES (:name, :description, SHA(:name), :latitude, :longitude, :hint, :circuit_id)";
	 	 try {
	 			 $stmt = $db->prepare($sql);
	 			 $stmt->bindParam("name", $node->name);
	 			 $stmt->bindParam("description", $node->description);
	 			 //$stmt->bindParam("code", $node->code);
	 			 $stmt->bindParam("latitude", $node->latitude);
	 			 $stmt->bindParam("longitude", $node->longitude);
	 			 $stmt->bindParam("hint", $node->hint);
	 			 $stmt->bindParam("circuit_id", $node->circuit_id);
	 			 $stmt->execute();
	 			 $node->id = $db->lastInsertId();
	 			 echo json_encode($node);
	 	 } catch(PDOException $e) {
	 			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 	 }
	 }
	 //{"name":"nn", "description":"nn","code":"1984-01-28","latitude":"@algo", "longitude":"@algo","hint":"#5454545","circuit_id":1}



/**
* POST /circuits
* @param Request $request
* @return JsonResponse
*/
 function addCircuit() {
	global $db, $request;
    //si status = 1 requiere un update antes de hacer el insert
    $circuit = json_decode($request->getBody());
	$sql = "INSERT  INTO circuits (name, status, description) VALUES (:name, :status, :description)";
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam("name", $circuit->name);
        $stmt->bindParam("status", $circuit->status);
				$stmt->bindParam("description", $circuit->description);
        $stmt->execute();
        $circuit->id = $db->lastInsertId();
        echo json_encode($circuit);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
//{"name":"nn","status":1,"description":"prueba"}

/**
* POST /users
* @param Request $request
* @return JsonResponse
*/
function addUser() {
 global $db, $request;
	 //si status = 1 requiere un update antes de hacer el insert
	 $user = json_decode($request->getBody());
 $sql = "INSERT  INTO users (name, lastname, birthDate, email, password, color, gender, type) VALUES (:name, :lastname, :birthDate, :email, MD5(:password),:color, :gender, :type)";
	 try {

			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("name", $user->name);
			 $stmt->bindParam("lastname", $user->lastname);
			 $stmt->bindParam("birthDate", $user->birthDate);
			 $stmt->bindParam("email", $user->email);
			 $stmt->bindParam("password", $user->password);
			 $stmt->bindParam("color", $user->color);
			 $stmt->bindParam("gender", $user->gender);
			 $stmt->bindParam("type", $user->type);
			 $stmt->execute();
			 $user->id = $db->lastInsertId();
			 echo json_encode($user);
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}
//{"name":"nn", "lastname":"nn","birthDate":"1984-01-28","email":"@algo","password":"@password","color":"#5454545","gender":"hombre","type":"user"}



/**
* POST /questions
* @param Request $request
* @return JsonResponse
*/
function addQuestion() {
 global $db, $request;
	 //si status = 1 requiere un update antes de hacer el insert
	 $question = json_decode($request->getBody());
 	 $sql = "INSERT INTO questions (question, answer, node_id) VALUES (:question, :answer, :node_id)";
	 try {
			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("question", $question->question);
			 $stmt->bindParam("answer", $question->answer);
			 $stmt->bindParam("node_id", $question->node_id);
			 $stmt->execute();
			 $question->id = $db->lastInsertId();
			 echo json_encode($question);
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}
//{"question":"nn", "answer":"nn","node_id":2}


/**
* POST /inscriptions
* @param Request $request
* @return \Illuminate\Http\JsonResponse
*/
function addInscription() {
global $db, $request;
	$body = $request->getBody();
	$inscription = json_decode($body);
	$sql = "INSERT INTO inscriptions (circuit_id, user_id) VALUES (:circuit_id, :user_id)";
	try {
			$stmt = $db->prepare($sql);
			$stmt->bindParam("circuit_id", $inscription->circuit_id);
			$stmt->bindParam("user_id", $inscription->user_id);
			$stmt->execute();
			$inscription->id = $db->lastInsertId();
			echo json_encode($inscription);
	} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

//{"circuit_id":1, "user_id":1}
//{"circuit_id":1, "user_id":2}
//{"circuit_id":2, "user_id":2}


/**
* POST /nodesDiscovered
* @param Request $request
* @return \Illuminate\Http\JsonResponse
*/
function addNodeDiscovered() {
global $db, $request;
	$nodeDiscovered = json_decode($request->getBody());
	$sql = "INSERT  INTO discoveredNodes (node_id, user_id, question_id, status, statusDate1) VALUES (:node_id, :user_id, :question_id, :status, :statusDate1)";
	try {
			$stmt = $db->prepare($sql);
			$stmt->bindParam("node_id", $nodeDiscovered->node_id);
			$stmt->bindParam("user_id", $nodeDiscovered->user_id);
			$stmt->bindParam("question_id", $nodeDiscovered->question_id);
			$stmt->bindParam("status", $nodeDiscovered->status);
			$stmt->bindParam("statusDate1", $nodeDiscovered->statusDate1);
			$stmt->execute();
			$nodeDiscovered->id = $db->lastInsertId();
			echo json_encode($nodeDiscovered);
	} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}
//{"node_id":1, "user_id":1,"status": 0, "statusDate1":"2016-06-10 15:32:31"}

////////////////////////////////////////////////////////////////////////////////
//Implementación API básica de las entidades de la aplicación, PUT (Actualizar)

/**
* PUT /nodes/{id}
* @param integer $id, Request $request
* @return JsonResponse
*/
	 function updateNode($id) {
	  global $db, $request;
		 $body = $request->getBody();
     $node = json_decode($body);
  	 $sql = "UPDATE nodes SET name= :name, description= :description, code= :code, latitude= :latitude, longitude= :longitude, hint= :hint, circuit_id= :circuit_id WHERE id= :id";
	 	 try {
	 			 $stmt = $db->prepare($sql);
	 			 $stmt->bindParam("name", $node->name);
	 			 $stmt->bindParam("description", $node->description);
	 			 $stmt->bindParam("code", $node->code);
	 			 $stmt->bindParam("latitude", $node->latitude);
	 			 $stmt->bindParam("longitude", $node->longitude);
	 			 $stmt->bindParam("hint", $node->hint);
	 			 $stmt->bindParam("circuit_id", $node->circuit_id);
				 $stmt->bindParam("id", $id);
	 			 $stmt->execute();
	 			 echo json_encode($node);
	 	 } catch(PDOException $e) {
	 			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 	 }
	 }
	 //{"name":"nn", "description":"nn","code":"1984-01-28","latitude":"@algo","hint":"#5454545","circuit_id":2}


/**
* PUT /circuit/{id}
* @param integer $id, Request $request
* @return JsonResponse
*/
function updateCircuit($id) {
    global $db, $request;
    $body = $request->getBody();
    $circuit = json_decode($body);
    $sql = "UPDATE circuits SET name=:name, status=:status, description=:description WHERE id=:id ";
    try {
        $stmt = $db->prepare($sql);
				$stmt->bindParam("name", $circuit->name);
        $stmt->bindParam("status", $circuit->status);
				$stmt->bindParam("description", $circuit->description);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        echo json_encode($circuit);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
//{"name":"nn","status":1,"description":"prueba"}

/**
* PUT /users/{id}
* @param integer $id, Request $request
* @return JsonResponse
*/
function updateUser($id) {
 global $db, $request;
	 //si status = 1 requiere un update antes de hacer el insert
	 $body = $request->getBody();
	 $user = json_decode($body);
 	 $sql = "UPDATE users set name=:name, lastname=:lastname, birthDate=:birthDate, email=:email, password=:password, color=:color, gender=:gender, type=:type WHERE id=:id";
	 try {

			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("name", $user->name);
			 $stmt->bindParam("lastname", $user->lastname);
			 $stmt->bindParam("birthDate", $user->birthDate);
			 $stmt->bindParam("email", $user->email);
			 $stmt->bindParam("password", $user->password);
			 $stmt->bindParam("color", $user->color);
			 $stmt->bindParam("gender", $user->gender);
			 $stmt->bindParam("type", $user->type);
			 $stmt->bindParam("id", $id);
			 $stmt->execute();
			 echo json_encode($user);
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}
//{"name":"nn", "lastname":"nn","birthDate":"1984-01-28","email":"@algo","password":"@password","color":"#5454545","gender":"hombre","type":"user"}


/**
* PUT /questions/{id}
* @param integer $id, Request $request
* @return JsonResponse
*/
function updateQuestion($id) {
 global $db, $request;
   $body = $request->getBody();
	 $question = json_decode($body);
 	 $sql = "UPDATE questions SET question= :question, answer= :answer, node_id= :node_id WHERE id= :id";
	 try {
			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("question", $question->question);
			 $stmt->bindParam("answer", $question->answer);
			 $stmt->bindParam("node_id", $question->node_id);
			 $stmt->bindParam("id", $id);
			 $stmt->execute();
			 echo json_encode($question);
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}
//{"question":"nn", "answer":"nn","node_id":2}


/**
* PUT /inscriptions/{id}
* @param integer $id, Request $request
* @return JsonResponse
*/
function updateInscription($id) {
global $db, $request;
	$body = $request->getBody();
	$inscription = json_decode($body);
	$sql = "UPDATE inscriptions SET circuit_id=:circuit_id, user_id=:user_id WHERE id=:id";
	try {
			$stmt = $db->prepare($sql);
			$stmt->bindParam("circuit_id", $inscription->circuit_id);
			$stmt->bindParam("user_id", $inscription->user_id);
			$stmt->bindParam("id", $id);
			$stmt->execute();
			echo json_encode($inscription);
	} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

//{"circuit_id":1, "user_id":3}
//{"circuit_id":1, "user_id":2}
//{"circuit_id":2, "user_id":1}


/**
* PUT /nodesDiscovered/{id}
* @param integer $id, Request $request
* @return JsonResponse
*/
function updateNodeDiscovered($id) {
global $db, $request;
	$body = $request->getBody();
	$nodeDiscovered = json_decode($body);
$sql = "UPDATE discoveredNodes SET node_id=:node_id, user_id=:user_id, status=:status, statusDate1=:statusDate1, statusDate2=:statusDate2, statusDate3=:statusDate3 WHERE id=:id";
	try {
			$stmt = $db->prepare($sql);
			$stmt->bindParam("node_id", $nodeDiscovered->node_id);
			$stmt->bindParam("user_id", $nodeDiscovered->user_id);
			$stmt->bindParam("status", $nodeDiscovered->status);
			$stmt->bindParam("statusDate1", $nodeDiscovered->statusDate1);
			$stmt->bindParam("statusDate2", $nodeDiscovered->statusDate2);
			$stmt->bindParam("statusDate3", $nodeDiscovered->statusDate3);
			$stmt->bindParam("id", $id);
			$stmt->execute();
			echo json_encode($nodeDiscovered);
	} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}
//el null tiene que ir en minùsculas
//{"node_id":1, "user_id":1, "question_id":1, "status": 0, "statusDate1":"2016-06-10 15:32:31", "statusDate2":null, "statusDate3":null}



////////////////////////////////////////////////////////////////////////////////
//Implementación API básica de las entidades de la aplicación, DELETE

/**
* DELETE /nodes/{id}
* @param integer $id
* @return mixed
*/
function deleteNode($id){
 global $db;
	 $sql = "DELETE FROM nodes WHERE id=:id";
	 try {
			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("id", $id);
			 $stmt->execute();
			 echo ("object deleted");
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}

/**
* DELETE /circuits/{id}
* @param integer $id
* @return mixed
*/
function deleteCircuit($id){
 global $db;
	 $sql = "DELETE FROM circuits WHERE id=:id";
	 try {
			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("id", $id);
			 $stmt->execute();
			 echo ("object deleted");
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}


/**
* DELETE /users/{id}
* @param integer $id
* @return mixed
*/
function deleteUser($id){
 global $db;
	 $sql = "DELETE FROM users WHERE id=:id";
	 try {
			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("id", $id);
			 $stmt->execute();
			 echo ("object deleted");
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}


/**
* DELETE /questions/{id}
* @param integer $id
* @return mixed
*/
function deleteQuestion($id){
 global $db;
	 $sql = "DELETE FROM questions WHERE id=:id";
	 try {
			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("id", $id);
			 $stmt->execute();
			 $question = $stmt;
			 echo ("object deleted");
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}


/**
* DELETE /inscriptions/{id}
* @param integer $id
* @return mixed
*/
function deleteInscription($id){
 global $db;
	 $sql = "DELETE FROM inscriptions WHERE id=:id";
	 try {
			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("id", $id);
			 $stmt->execute();
			 echo ("object deleted");
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}

/**
* DELETE /nodesDiscovered/{id}
* @param integer $id
* @return mixed
*/
function deleteNodeDiscovered($id){
 global $db;
	 $sql = "DELETE FROM discoveredNodes WHERE id=:id";
	 try {
			 $stmt = $db->prepare($sql);
			 $stmt->bindParam("id", $id);
			 $stmt->execute();
			 echo ("object deleted");
	 } catch(PDOException $e) {
			 echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}

////////////////////////////////////////////////////////////////////////////////

$app->run();

?>
