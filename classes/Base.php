<?php

class Base {
    
    /**
     * Переменная, в которой хранится путь к классам системы
     * 
     * @var string
     * @access public
     * @static
     */
    public static $class_path;
    
    /**
     * Расширение php-файла. Можно поменять, к примеру, на .phtml или любое другое
     * 
     * @var string
     * @access public
     * @static
     */
    public static $php_ext;
    
    /**
     * В этой переменной хранится путь к контроллерам системы
     * 
     * @var string
     * @access public
     * @static
     */
    public static $controllers_path;
    
    /**
     * Путь к вьюхам
     * 
     * @var string
     * @access public
     * @static
     */
    public static $views_path;
    
    public static $short_views_path;
    
    /**
     * Путь к конфигурационным файлам
     * 
     * @var string
     * @access public
     * @static
     */
    public static $config_path;
    
    /**
     * Инициализационная функция базового класса
     * 
     * @access public
     * @return void
     */
    public static function init() {
         $root = $_SERVER['DOCUMENT_ROOT'];
        
        Base::setClassPath($root . 'classes/');
        Base::setControllersPath($root . 'controllers/');
        Base::setPHPExt('.php');
        Base::setViewsPath($root . 'views/');
        Base::setConfigPath($root . 'config/');
        Base::$short_views_path = 'views';
        
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
    
    /**
     * Чтение конфигурационных файлов
     * 
     * @param string $config
     * @return array
     * @throws Exception
     */
    public static function read_config_file($config) {
        
        $path = Base::getConfigPath() . $config . '.ini';
        if (!file_exists($path))
            throw new Exception("Выбранного файла конфигурации $config.ini не существует");
        
        $parse = parse_ini_file($path);
        return $parse;
    }
    
  
    
    /**
     * Автозагрузчик классов
     * 
     * @param string $class Класс к загрузке
     * @throws Exception
     */
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