<?php

/**
 * Класс предназначен для внешнего вывода. Методы класса дают возможность выводить различные HTML-элементы, не думая о том,
 * как правильно написать код для их вывода.
 * 
 * @package HTML
 */
class HTML {
    
    /**
     * Выводит текстовое поле.
     * 
     * @param string $name Имя поля. Выводится, если не пусто.
     * @param string $type Если не пусто, то текст, любое другое значение - password
     * @param integer $size Ширина поля. Выводится, если не пусто.
     * @param integer $maxlength Максимальное кол-во символов в поле. Выводится если не пусто.
     * 
     * @return void
     */
    public static function input_text($name = '', $type = '', $size = 0, $maxlength = 0, $value = '') {
        
        if (!empty($type))
            $return = "<input type=\"text\"";
        else 
            $return = "<input type=\"password\"";
        
        
        if (!empty($name))
            $return .= " name=\"$name\"";
        
        if (!empty($size))
            $return .= " size=$size";
        
        if (!empty($maxlength))
            $return .= " maxlength=$maxlength";
        
        if (!empty($value))
            $return .= " value=\"$value\"";
        
        $return .= " />";
        
        echo $return;
    }
    
    /**
     * Выводит кнопку выбора файла для последующей его загрузки на сервер.
     * 
     * @param string $name Если не пусто, то имя поля
     * 
     * @return void
     */
    public static function input_file($name = '') {
        $return = "<input type=\"file\"";
        
        if (!empty($name))
            $return .= " name=\"$name\"";
        
        $return .= " />";
        echo $return;
    }
    
    /**
     * Выводит начальный тег формы.
     * 
     * @param string $action Если не пусто - экшн формы
     * @param string $method Если не пусто - метод отправки данных формы
     * @param string $enctype Enctype - нужно, если отправляются файлы на сервер. Используется, если не пусто
     * @param string $name Имя формы
     * @param string $target Обработчик формы возвращает данные в виде HTML-документа. Этот параметр определяет, куда будет загружен итоговый документ
     * 
     * @return void
     */
    public static function begin_form($action = '', $method = '', $enctype = '', $name = '', $target = '') {
        
        $form = "<form";
        
        if (!empty($name))
            $form .= " name = \"$name\" ";
        
        if (!empty($action))
            $form .= "action = \"$action\" ";
        
        if (!empty($enctype))
            $form .= "enctype=\"$enctype\" ";
        
        if (!empty($method))
            $form .= "method=\"$method\" ";
        
        if (!empty($target))
            $form .= "target = \"$target\"";
        
        $form .= ">";
        
        echo $form;
       
    }
    
  public static function input_textarea($cols = 0, $rows = 0, $name = '') {
      
      $return = "<textarea";
      
      if (!empty($cols))
          $return .= " cols=$cols ";
      
      if (!empty($rows))
          $return .= " rows=$rows ";
      
      if (!empty($name))
          $return .= " name=$name ";
      
      echo $return;
      
  }
    
}

?>