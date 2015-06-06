<?php

class Db {
  
    public static $connection_data;
    
    public static $connection_id;
    
    public static $query;
    
    public static $query_id;
    
    public static $last_error;
    
    public static $last_error_num;
    
    public static function initValues() {
        Db::$connection_data['host'] = DB_HOST;
        Db::$connection_data['user'] = DB_USER;
        Db::$connection_data['pass'] = DB_PASS;
        Db::$connection_data['dbname'] = DB_NAME;
     }
     
     
         
         
     }
   

?>