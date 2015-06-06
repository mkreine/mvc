<?php

class DB_MySQL extends Db {

/**
 * Проверяет, существует ли база данных. В случае если указан пустой параметр, возвращает исключение. В противном случае
 * вернет true в случае существования базы, и false в противном случае.
 * 
 * @param string $dbname Проверяемая база данных
 * @return mixed
 * @throws Exception
 */    
public static function dbExists($dbname) {
    
    if (empty($dbname)) 
        throw new Exception("Cannot operate on an empty value");
    
    DB_MySQL::query('SHOW DATABASES LIKE "' . $dbname . '"');
    $rows = DB_MySQL::getRowCount();
    return ($rows > 0) ? true : false;
}  

/**
 * Выбирает базу данных для работы. Если база данных, которую пытаются выбрать для работы, не существует на сервере, функция возвращается исключение.
 * В противном случае возвращается результат выборки базы данных.
 * 
 * @param string $dbname База данных, которую необходимо выбрать для работы с ней
 * @return mixed
 * @throws Exception
 */
public static function selectDB($dbname = '') {
    
    if (empty($dbname))
        $dbname = Db::$connection_data['dbname'];
    
    if (!Db::dbExists($dbname))
        throw new Exception("Database $dbname does not exist");
    
    return mysql_select_db($dbname);
}

/**
 * Получает все таблицы из заданной базы данных. Если базы данных, указанной в параметре, не существует, возвращается исключение. 
 * 
 * @param string $dbname База данных, из которой необходимо выбрать все таблицы
 * @return array
 * @throws Exception
 */
public static function getTables($dbname = '')
{   
    if(empty($dbname))
        $dbname = Db::$connection_data['dbname'];
    
    if (!DB_MySQL::dbExists($dbname))
        throw new Exception("Database $dbname does not exist");
    
    $str = 'SHOW TABLES FROM ' . $dbname;
    $q = DB_MySQL::query($str);
    $var = 'Tables_in_'.$dbname;
    $tables = array();
    
    while ($d = DB_MySQL::fetch()) {
        $tables[] = $d[$var];
    }
    
    return $tables;
    
    
   
}

/**
 * Функция-обёртка. Возвращает кол-во затронутых строк в результате запроса к БД.
 * 
 * @return integer
 */
public static function getRowCount() {
    
    return mysql_num_rows(Db::$query_id);
}

/**
 * Осуществляет связь с сервером баз данных MySQL. Если связь не удалась, возвращается исключение. В противном случае возвращается
 * ресурсный объект, предназначенный для дальнейшей работы.
 * @return type
 * @throws Exception
 */
public static function connect() {
         Db::initValues();
         Db::$connection_id = mysql_connect(Db::$connection_data['host'], Db::$connection_data['user'], Db::$connection_data['pass']);
         if (!is_resource(Db::$connection_id))
              throw new Exception("Cannot connect to MySQL");
          else {
              mysql_select_db(Db::$connection_data['dbname']);
              return Db::$connection_id;
          }
          
}

public static function query($text) {
    if (empty($text)) {
        $text = Db::$query;
    }
    
    
    Db::$query_id = mysql_query($text);
    Db::$last_error = mysql_error();
    Db::$last_error_num = mysql_errno();
    
    if (empty(Db::$last_error))
        return Db::$query_id;
    else
        return false;
}

public static function fetch() {
    if (!is_resource(Db::$query_id))
        throw new Exception("Cannot analyze non-resource variable");
    
    return mysql_fetch_array(Db::$query_id); 
    
}

public static function fetchFields() {
   
    if (!is_resource(Db::$query_id))
        throw new Exception("Cannot fetch fields on this request");
    
    $fields_num = mysql_num_fields(Db::$query_id);
    $result_array = array();
    $i = 0;
    
    while ($i < $fields_num) {
        $field = mysql_fetch_field(Db::$query_id, $i);
        
        $result_array[$i]['blob']       =       $field->blob;
        $result_array[$i]['max_length'] =       $field->max_length;
        $result_array[$i]['multiple_key']   =   $field->multiple_key;
        $result_array[$i]['name']       =       $field->name;
        $result_array[$i]['not_null']   =       $field->not_null;
        $result_array[$i]['numeric']    =       $field->numeric;
        $result_array[$i]['primary_key'] =      $field->primary_key;
        $result_array[$i]['table']      =       $field->table;
        $result_array[$i]['type']       =       $field->type;
        $result_array[$i]['unique_key'] =       $field->unique_key;
        $result_array[$i]['unsigned']   =       $field->unsigned;
        $result_array[$i]['zerofill']   =       $field->zerofill;
        
        $i++;
    }
    
    return $result_array;
   
}

}