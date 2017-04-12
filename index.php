<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

// Create and configure Slim app
$config = ['settings' => [
    'addContentLengthHeader' => false,
]];

function dbConnect () {
	return new PDO('mysql:host=localhost;dbname=todo', 'root', '');
};

$app = new \Slim\App;

$app->get('/todos', function (Request $request, Response $response) {
	try {
	$dbh = dbConnect();
		$result = $dbh->query('SELECT * from todos');
	} catch (PDOException $e) {
	    print "Erreur !: " . $e->getMessage() . "<br/>";
	    die();
	}
	var_dump($result);
	die();
	$response->getBody()->write($result);
	return $response;
});

$app->post('/todos', function (Request $request, Response $response) {
	$newTodo = "Hello World!";

	try {
		$dbh = dbConnect();
		$result = $dbh->exec('INSERT INTO todos (todo) VALUES ("' . $newTodo . '");');
	} catch (PDOException $e) {
	    print "Erreur !: " . $e->getMessage() . "<br/>";
	    die();
	}
	var_dump($result);
	$response->getBody()->write($result);
	return $response;
});

$app->delete('/todos/{id}', function (Request $request, Response $response) {
	$id = $request->getAttribute('id');
	try {
		$dbh = dbConnect();
		$result = $dbh->exec('DELETE FROM todos WHERE id = ' . $id);
	} catch (PDOException $e) {
	    print "Erreur !: " . $e->getMessage() . "<br/>";
	    die();
	}
	var_dump($result);
	die();
	$response->getBody()->write($result);
	return $response;
});

// Define app routes
$app->get('/hello/{name}', function ($request, $response, $args) {
    return $response->write("Hello " . $args['name']);
});

// Run app
$app->run();

