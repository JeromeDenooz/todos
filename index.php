<?php
//var_dump($_SERVER);
//exit();
header('Content-Type: application/json');
$route = explode("/", $_SERVER['PATH_INFO']);

switch ($_SERVER['REQUEST_METHOD']) {
	case 'POST' :
		switch ($route[1]) {
			case 'todos' :
				try {
					$response = addTodoDB($_POST['todo']);
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
		}
		break;
	case 'GET' :
		switch ($route[1]) {
			case 'todos' :
				try {
					$response = getTodoDB();
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				echo json_encode($response);
				break;
		}
		break;
	case 'PUT' :
		switch ($route[1]) {
			case 'todos' :
				try {
					parse_str(file_get_contents("php://input"),$post_vars);
					$response = updateTodoDB($route[2], $post_vars['status']);
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
				echo json_encode($response);
				break;
		}
		break;
	case 'DELETE' : 
		break;
};

function addTodoDB ($todo) {
	$db = new PDO('mysql:host=localhost;dbname=todo', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	$query = $db->prepare("INSERT INTO todos(label, status) VALUES(:label, :status);");
	$query->execute(array(
		'label' => $todo,
		'status' => 0
	));

	return $db->lastInsertId();
};

function getTodoDB() {
	$db = new PDO('mysql:host=localhost;dbname=todo', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	$query = $db->query("SELECT * FROM todos");
	$todo = array(array(
		'id' => NULL,
		'label' => "",
		'status' => NULL
	));
	$i=0;
	while ($data = $query->fetch()) {
		$todo[$i]['id'] = $data['id'];
		$todo[$i]['label'] = $data['label'];
		$todo[$i]['status'] = $data['status'];
		$i++;
	}
	return $todo;
};

function updateTodoDB($id, $status) {
	$db = new PDO("mysql:host=localhost;dbname=todo", "root", "", array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
	try {
		$query = $db->exec("UPDATE todos SET status=$status WHERE id=$id");
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
}

?>