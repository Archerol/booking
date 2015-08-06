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


function AjaxController_createOrder($args) {
	loadModule('orders');

	$errorCode = Orders_createOrder($args['title'], $args['description'], $args['price'], User_currentUser());
	
	AjaxController_sendResponse($errorCode, 'create_order_success');
}


function AjaxController_orderList($args) {
	loadModule('orders');

	$offset = intval($args['offset']);
	$limit = intval($args['limit']);

	$data = Orders_getOrderList($args['status'], $offset, $limit, $ordersCount);

	if (is_numeric($data) && $data < 0) 
	{
		$message = getMessage(getConfig('errors')[$data]);
		echo json_encode(['status' => 'error', 'message' => $message]);
	} 
	else 
	{
		$data = showTemplate('orderList', [
			'orderList' => $data,
			'ordersCount' => $ordersCount,
			'offset' => $offset,
			'limit' => $limit,
		], true);

		echo json_encode(['status' => 'data', 'data' => $data]);
	}
}


function AjaxController_performOrder($args) {
	loadModule('orders');

	$errorCode = Orders_performOrder(User_currentUser(), $args['order_id']);

	AjaxController_sendResponse($errorCode, 'perform_order_success');
}






function AjaxController_notFound() {
	echo json_encode(['status' => 'error', 'message' => getMessage('ajax_invalid_request') ]);
}	
