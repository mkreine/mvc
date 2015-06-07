<?php

class Router extends Base {

    public static function route($url) {
	if (empty($url)) {
	    return false;
	}
	
	$controller = isset($url['controller']) ? $url['controller'] . 'Controller' : '';
	$action = isset($url['action']) ? $url['action'] . 'Action' : '';

	if (empty($controller) || empty($action)) {
	    throw new Exception("Malformed url!");
	}

	if (file_exists(Base::getControllersPath() . $controller  . Base::getPHPExt())) {
	    require_once (Base::getControllersPath() . $controller  . Base::getPHPExt());
	    
	    unset($url['controller']);
	    unset($url['action']);

	    $keys = array_keys($url);

	    if (count($keys) > 0) {
		$params = array();
		foreach ($keys as $key) {
		    if (array_key_exists($key, $url)) {
			$params[$key] = $url[$key];
		    }
		}
	    }
	    
	    if (method_exists($controller, $action)) {
		if (!empty($params))
			call_user_func_array("$controller::$action", $params);
		else
			call_user_func("$controller::$action");
	    }
	    else {
		throw new Exception("Cannot call function $full_action! No such method in controller $full_controller!");
	    }
	}
	else {
	    throw new Exception("Cannot require controller $controller! No such file could be found!");
	}
    }

}