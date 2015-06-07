<?php

#error_reporting(E_ALL);
#ini_set('display_errors', '1');

require 'classes/Base.php';

Base::init();

session_start();

spl_autoload_register(array('Base', 'load_classes'));

DB_MySQL::connect();

Router::route($_GET);

