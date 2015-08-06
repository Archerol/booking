<?php
date_default_timezone_set('Europe/Moscow');

error_reporting(E_ALL);

ini_set('display_errors', 1);

if (!defined('LOCAL_ROOT')) define('LOCAL_ROOT', '/var/www/booking');
if (!defined('SITE_ROOT'))  define('SITE_ROOT',  'http://archerol.net/');

include( LOCAL_ROOT . '/includes/functions.php' );

