<?php

/**
 * Keys of config for using different connections in modules
 */

if (!defined('USER_DB'))         define('USER_DB', 'main');
if (!defined('ORDERS_DB'))       define('ORDERS_DB', 'main');
if (!defined('TRANSACTIONS_DB')) define('TRANSACTIONS_DB', 'main');


return [
	'main' => [
		'hostname' => 'localhost',
		'username' => 'booking',
		'password' => 'password',
		'database' => 'booking',
		'encoding' => 'utf8',
		'connect'  => NULL,
	],
];
