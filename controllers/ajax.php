<?php
/**
 * Handle error code from modules and send textual message if needed
 */
function AjaxController_sendResponse($errorCode, $onSuccess) {

	if ($errorCode < 0) {
		$status = 'error';
		$message = getMessage(getError($errorCode));
	} else {
		$status = 'success';
		$message = getMessage($onSuccess);
	}

	echo json_encode(['status' => $status, 'message' => $message]);
}


function AjaxController_login($args) {
	$errorCode = User_login($args['login'], $args['password'], getConfig('main')['session_lifetime']);

	AjaxController_sendResponse($errorCode, 'success_auth');
}


function AjaxController_registration($args) {
	$errorCode = User_register($args['login'], $args['password'], $args['confirm'], $args['type'], getConfig('main')['session_lifetime']);

	AjaxController_sendResponse($errorCode, 'success_registration');
}	


function AjaxController_logout() {
	User_logout();

	AjaxController_sendResponse(0, 'user_logout');
}


function AjaxController_getUserMoney() {
	if (empty(User_currentUser())) {
		echo json_encode(['status' => 'error', 'data' => getMessage('need_auth')]);
	} else {
		echo json_encode(['status' => 'data', 'data' => User_currentUser()['account']]);
	}
}



function AjaxController_notFound() {
	echo json_encode(['status' => 'error', 'message' => getMessage('ajax_invalid_request') ]);
}	
