<?php
session_start();

define('BASE_PATH', __DIR__);
define('BASE_URL', '/burguerwagen');

require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/config/database.php';

spl_autoload_register(function($className) {
    $paths = [
        BASE_PATH . '/core/' . $className . '.php',
        BASE_PATH . '/controllers/' . $className . '.php',
        BASE_PATH . '/models/' . $className . '.php',
    ];
    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Example URL:
// index.php?controller=product&action=index
$controllerName = isset($_GET['controller']) ? ucfirst($_GET['controller']) . 'Controller' : 'HomeController';
$actionName     = isset($_GET['action']) ? $_GET['action'] : 'index';

if (!class_exists($controllerName)) {
    die("Controller '$controllerName' not found.");
}

$controller = new $controllerName();

if (!method_exists($controller, $actionName)) {
    die("Action '$actionName' not found in controller '$controllerName'.");
}

$controller->$actionName();
