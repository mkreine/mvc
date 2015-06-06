<?php

#error_reporting(E_ALL);
#ini_set('display_errors', '1');

require 'init.php';

Router::route($_GET);

