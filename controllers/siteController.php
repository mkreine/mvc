<?php

class siteController extends Controller_Base {
    
    public static function testAction()
    {
	$vars['a'] = 2;
	Base::render('a/b/b', $vars);
        DB_MySQL::connect();
        print_r(DB_MySQL::getTables());
    }

    public static function helloAction()
    {
	echo 'helloAction';
    }
}