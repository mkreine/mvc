<?php

/**
 * Класс для работы с MySQL
 * 
 * @category DB
 * @package DB_MySQL
 */
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
public static function getTables($dbname = '') {   
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
 * Проверяет, существует ли таблица в данной базе данных. Возвращает true в случае существования таблицы, false в противном случае.
 * 
 * @param string $tablename Таблица, существование которой необходимо проверить
 * @param string $dbname База данных, в которой должна находиться искомая таблица
 * @return boolean
 */
public static function tableExists($tablename, $dbname = '') {
    
    $tablename = trim($tablename);
    $tables = DB_MySQL::getTables();
    
    return in_array($tablename, $tables);
}

/**
 * Функция выполняет запрос SELECT к базе данных. 
 * 
 * @param string $table Таблица, из которой брать данные. Если такой таблицы нет, возвращается исключение
 * @param mixed $fields Требуемые поля. Если передана пустая строка, будут браться все поля, в противном случае должен быть массив полей. Если это не так, будет передано исключение
 * @param mixed $where Условие для отбора. Если не пустая строка, то массив вида array('key' => 'value')
 * @param mixed $and Логика соединения AND или OR
 * @throws Exception
 * @return mixed
 */
public static function select($table, $fields = '', $where = '', $and = true) {
    
    if (!DB_MySQL::tableExists($table))
        throw new Exception("Таблицы $table не существует");
    
    if (empty($fields)) {
        $fields = '*';
        $selQuery = 'SELECT ' . $fields . ' FROM ' . $table;
    }
    
    else {
        if (!is_array($fields))
            throw new Exception("Поля должны быть переданы в виде массива");
        
        $str = implode(", ", $fields);
        $selQuery = 'SELECT ' . $str . ' FROM ' . $table;
    }
    
    if (!empty($where)) {
        
        $selQuery .= ' WHERE ';
        $size = count($where);
        
        $i = -1;
        foreach ($where as $key => $value) {
            $i++;
            $selQuery .= $key . ' = ' . $value;       
            
            if ($and)
                $selQuery .= ' AND ';
            else
                $selQuery .= ' OR ';
        }
        
        if ($and)
            $selQuery = substr($selQuery, 0, -5);
        else
            $selQuery = substr($selQuery, 0, -4);
    }
    
    //return $selQuery;
    return DB_MySQL::query($selQuery);
    
}

/**
 * Удаляет таблицу из базы данных. Если такой таблицы не существует, возвращается исключение. 
 * Функция возвращает результат работы функции Db::wasError(), которая определяет, была ли ошибка при выполнении последнего запроса
 * 
 * @param string $table Таблица, подлежащая удалению
 * @return boolean
 * @throws Exception
 */
public static function delete($table) {
    if (!DB_MySQL::tableExists($table))
        throw new Exception("Таблицы $table не существует");
    
    DB_MySQL::query("DROP TABLE ". $table);
    return Db::wasError();
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

/**
 * Функция выполняет запрос к серверу MySQL. Если передан пустой текст запроса, то он берется из переменной $query класса. 
 * В случае успешного запроса возвращается его ID или true/false, в зависимости от типа запроса. Если запрос не удался, возвращается false, а в 
 * соответствующих переменных будет доступен код и текст последней ошибки.
 * 
 * @param string $text Текст запроса
 * @return mixed
 */
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

/**
 * Функция-обертка. Возвращает результаты mysql_fetch_array. В случае неуспеха возвращается исключение.
 * 
 * @return mixed
 * @throws Exception
 */
public static function fetch() {
    if (!is_resource(Db::$query_id))
        throw new Exception("Cannot analyze non-resource variable");
    
    return mysql_fetch_array(Db::$query_id); 
    
}

/**
 * Возвращает подробную информацию о каждом поле запроса.
 * 
 * @return object
 * @throws Exception
 */
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

/**
 * Функция ищет первичный ключ в таблице $table. В случае успеха возвращает имя поля первичного ключа. В противном случае, или если первичного ключа
 * нет, возвращает false
 * 
 * @param string $table Таблица, в которой производим поиск
 * @return mixed
 * @throws Exception
 */
public static function findPK($table) {
    
    if (!DB_MySQL::tableExists($table))
        throw new Exception("Таблицы не существует!");
    
    DB_MySQL::select($table);
    $fields = DB_MySQL::fetchFields();
    $field_count = count($fields);
    
    for ($i = 0; $i <= $field_count; $i++) {
        if ($fields[$i]['primary_key'] == 1) {
            return $fields[$i]['name'];
            break;
        }
    }
    
    return false;
}

/**
 * Ищет запись в таблице по значению первичного ключа. Если указан параметр $returnValue, функция возвратит элемент массива с этим ключом, иначе говоря - 
 * определенное поле. В противном случае возвратится вся строка.
 * 
 * @param string $table Таблица, которая участвует в поиске
 * @param integer $pkValue Значение первичного ключа
 * @param string $returnValue Ключ элемента массива, который необходимо вернуть
 * 
 * @return mixed
 */
public static function findRowByPK($table, $pkValue, $returnValue = '') {
    
   $pk = DB_MySQL::findPK($table);
   DB_MySQL::select($table, '', array($pk => $pkValue));
   
   $result = DB_MySQL::fetch();
   
   if (!empty($returnValue)) {
       
       if (array_key_exists($returnValue, $result))
               return $result[$returnValue];
       else
                return $result;
   }
   
   else {
       return $result;
   }
   
}

}

?>