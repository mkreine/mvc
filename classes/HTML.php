<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class HTML {
    
    public static function input_text($name = '') {
        $return = "<input type=\"text\"";
        
        if (!empty($name))
            $return .= " name=\"$name\"";
        
        $return .= " />";
        
        echo $return;
    }
    
    public static function input_file($name = '') {
        $return = "<input type=\"file\"";
        
        if (!empty($name))
            $return .= " name=\"$name\"";
        
        $return .= " />";
        echo $return;
    }
    
    public static function begin_form($action = '', $method = '', $enctype = '', $name = '', $target = '') {
        
        $form = "<form ";
        
        if (!empty($name))
            $form .= "name = \"$name\" ";
        
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
}