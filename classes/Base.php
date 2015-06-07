<?php

class Base {
    
    
    public static $class_path;
    
    public static $php_ext;
    
    public static $controllers_path;
    
    public static $views_path;
    
    public static $config_path;
    
    public static function init() {
         $root = $_SERVER['DOCUMENT_ROOT'];
        
        Base::setClassPath($root . 'classes/');
        Base::setControllersPath($root . 'controllers/');
        Base::setPHPExt('.php');
        Base::setViewsPath($root . 'views/');
        Base::setConfigPath($root . 'config/');
        
    }

    public static function setClassPath($path) {
        Base::$class_path = $path;
    }
    
    public static function getClassPath() {
        return Base::$class_path;
    }
    
    public static function setControllersPath($path) {
        Base::$controllers_path = $path;
    }
    
    public static function getControllersPath() {
        return Base::$controllers_path;
    }
    
    public static function setPHPExt($ext) {
        Base::$php_ext = '.php';
    }
    
    public static function getPHPExt() {
        return Base::$php_ext;
    }
    
    public static function setViewsPath($path) {
        Base::$views_path = $path;
    }
    
    public static function getViewsPath() {
        return Base::$views_path;
    }
    
    public static function setConfigPath($path) {
        Base::$config_path = $path;
    }
    
    public static function getConfigPath() {
        return Base::$config_path;
    }
    
    public static function read_config_file($config) {
        
        $path = Base::getConfigPath() . $config . '.ini';
        if (!file_exists($path))
            throw new Exception("Выбранного файла конфигурации $config.ini не существует");
        
        $parse = parse_ini_file($path);
        return $parse;
    }

    public static function render($path, $params = array()) {
	if (empty($path)) {
	    throw new Exception("Cannot load empty view");
	}
	
	$pos = strpos($path, '/');
	if ($pos === false) {
	    if (file_exists(Base::getViewsPath() . $path . Base::getPHPExt())) {
		    include Base::getViewsPath() . $path . Base::getPHPExt();
	    }
	    else {
		throw new Exception("Cannot load layout $path.php");
	    }
	}

	else {
	    $base_path = Base::getViewsPath();
	    $views = explode("/", $path);
	    
	    for ($i = 0;  $i < count($views); $i++) {
		if (is_dir($base_path . $views[$i] . '/')) {
		    $base_path .= $views[$i] . '/';
		}

		else {
		    include $base_path . $views[$i] . Base::getPHPExt();
		    break;
		}
	    }

	}
    }
    
    public static function load_classes($class) {
        
       
        
    
    $pos = strpos($class, 'Controller');
    if ($pos === false) {
        
        if (file_exists(Base::getClassPath() .'/' .  $class . '.php')) {
	require_once(Base::getClassPath(). '/' .  $class . '.php');
        }
        else {
	throw new Exception("Cannot load class $class.php");
        }    
    } 
    
    else {
        
        if (file_exists(Base::getControllersPath() .'/' .  $class . Base::getPHPExt())) {
	require_once(Base::getControllersPath(). '/' .  $class . Base::getPHPExt());
        }
        else {
	throw new Exception("Cannot load controller $class.php");
        }
    }

}

}
?>