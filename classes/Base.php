<?php

class Base {
    
    private $vars;

    public function __construct()
    {
	if (!is_array($this->vars))
	{
	    $this->vars = array();
	}
    }

    public function __set($var, $name) {
	if (!isset($this->vars[$var])) {
	    $this->vars[$var] = $name;
	}
	else {
	    throw new Exception("Cannot set $var to $name");
	}
    }

    public function __get($var) {
	if (isset($this->vars[$var])) {
	    return $this->vars[$var];
	}

	else {
	    return NULL;
	}
    }



    public static function render($path, $params = array()) {
	if (empty($path)) {
	    throw new Exception("Cannot load empty view");
	}
	
	$pos = strpos($path, '/');
	if ($pos === false) {
	    if (file_exists(VIEW_PATH . $path . PHP_EXT)) {
		    include VIEW_PATH . $path . PHP_EXT;
	    }
	    else {
		throw new Exception("Cannot load layout $path.php");
	    }
	}

	else {
	    $base_path = VIEW_PATH;
	    $views = explode("/", $path);
	    
	    for ($i = 0;  $i < count($views); $i++) {
		if (is_dir($base_path . $views[$i] . '/')) {
		    $base_path .= $views[$i] . '/';
		}

		else {
		    include $base_path . $views[$i] . PHP_EXT;
		    break;
		}
	    }

	}
    }

}
?>