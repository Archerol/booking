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

];

$arr = $errorsArray + array_flip($errorsArray);

return $arr;

