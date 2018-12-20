<?php

require 'vendor/autoload.php';

require_once 'configuration.php';

require_once 'controller/class.TodoController.php';
require_once 'db/objects/class.database.php';
require_once 'db/objects/class.todo.php';

// Load Twig
$loader = new Twig_Loader_Filesystem('view/');
$twig = new Twig_Environment($loader);


// Bonus-questions: are there security problems with the way controllers are
// loaded and the way actions are run?
$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'todo';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($controllerName) {
	case 'todo':
		$controller = new TodoController($twig, $_GET, $_POST);
		break;
	default:
		throw new Exception("Unknown controller: $controllerName");
}

if (method_exists($controller, $action . 'Action')) {
	echo $controller->{$action . 'Action'}();
} else {
	throw new Exception("Unknown action '$action', in controller '$controllerName'");
}

?>
