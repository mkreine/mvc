<?php

/**
 * Базовый класс работы с базами данных
 * 
 * @category Database
 * @package Db
 */
class Db extends Base{
  
    public static $connection_data;
    
    public static $connection_id;
    
    public static $query;
    
    public static $query_id;
    
    public static $last_error;
    
    public static $last_error_num;
    
    public static function initValues() {
        
        $db_values = Base::read_config_file('db');
        
        Db::$connection_data['host'] = $db_values['host'];
        Db::$connection_data['user'] = $db_values['user'];
        Db::$connection_data['pass'] = $db_values['pass'];
        Db::$connection_data['dbname'] = $db_values['dbname'];
     }
     
     public static function wasError() {
         return (empty(Db::$last_error)) ? true : false;
     }
     
     
         
         
     }
   

?>