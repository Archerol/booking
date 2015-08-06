<?php

$errorsArray = [
	// Global errors
	'unknown_error'  => -1,
	'database_error' => -2,
	'ajax_invalid_request' => -3,

  // User, group 100
  'login_already_exists'   => -101,
	'wrong_login'            => -102,
	'login_not_exists'       => -103,
	'wrong_password'         => -104,
	'wrong_user_type'        => -105,
	'set_sid_error'          => -106,
	'wrong_confirm_password' => -107,
	'wrong_user_type'        => -108,

	// Orders, group 200
	'create_order_wrong_permission'  => -201,
	'create_order_wrong_price'       => -202,
	'wrong_order_status'             => -203,
	'order_history_need_auth'        => -204,
	'create_order_long_description'  => -205,
	'create_order_long_title'        => -206,
	'create_order_empty_title'       => -207,
	'create_order_empty_description' => -208,
	'create_order_need_auth'         => -209,
	'order_has_performer'            => -210,
	'unexpected_comission'           => -211,
];

$arr = $errorsArray + array_flip($errorsArray);

return $arr;

