<?php

define('BASE_PATH', $_SERVER['DOCUMENT_ROOT']);
define('CONTROLLERS_PATH', BASE_PATH . 'controllers/');
define('VIEW_PATH', BASE_PATH . 'views/');
define('CLASSES_PATH', BASE_PATH . 'classes/');
define('PHP_EXT', '.php');
define('HTML_EXT', '.html');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'drp72TuKr1sE52kMs');
define('DB_NAME', 'forum');



function __autoload($class) {
    
    $pos = strpos($class, 'Controller');
    if ($pos === false) {
        if (file_exists(CLASSES_PATH .'/' .  $class . '.php')) {
	require_once(CLASSES_PATH. '/' .  $class . '.php');
        }
        else {
	throw new Exception("Cannot load class $class.php");
        }    
    } 
    
    else {
        if (file_exists(CONTROLLERS_PATH .'/' .  $class . '.php')) {
	require_once(CONTROLLERS_PATH. '/' .  $class . '.php');
        }
        else {
	throw new Exception("Cannot load controller $class.php");
        }
    }
    
}

