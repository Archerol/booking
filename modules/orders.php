<?php
loadConfig('db');
loadModule('database');


function Orders_db() {
	return Database_connect(ORDERS_DB);
}

function Orders_transactions_db() {
	return Database_connect(TRANSACTIONS_DB);
}

function Orders_mc() {
	return MCache_connect(ORDERS_MC);
}

function Orders_createOrder($title, $description, $price, $user) {

	if (empty($user) || $user['type'] != 'customer') {
		return getError('create_order_wrong_permission');
	}

	if (empty($title)) {
		return getError('create_order_empty_title');
	}

	if (empty($description)) {
		return getError('create_order_empty_description');
	}

	if (mb_strlen($title, 'utf-8') > getConfig('orders')['title_max_length']) {
		return getError('create_order_long_title');
	}

	if (mb_strlen($description, 'utf-8') > getConfig('orders')['descr_max_length']) {
		return getError('create_order_long_description');
	}

	if (!filter_var($price, FILTER_VALIDATE_INT)) {
		return getError('create_order_wrong_price');;
	}


	$title = htmlspecialchars($title, ENT_QUOTES, 'utf-8');
	$description = htmlspecialchars($description, ENT_QUOTES, 'utf-8');

	$q = "INSERT INTO Orders
				(title, description, price, customer_id)
				VALUES (?s, ?s, ?i, ?i)";

	$result = Database_query(Orders_db(), $q, $title, $description, $price, $user['id']);
	$id = Database_insertID();

	if (!$id) {
		return getError('database_error');
	}

	
	return $id;
}


function Orders_getOrder($order_id) {
	$q = "SELECT 
					id, 
					title, 
					description, 
					price, 
					customer_id, 
					performer_id, 
					status
				FROM Orders
				WHERE id = ?i";
	$result = Database_query(Orders_db(), $q, $order_id);
	return Database_selectRow($result);
}




/**
 * This method get OrderList, which may be of two types:
 * free orders or orders history of current user
 *
 * @param string('created'|'done') $status  Status of required orders
 *
 * @param int $offset  Range offset
 *
 * @param int $limit   Count of orders for current selection
 *
 * @param int &$count  Full count of selection for paging
 *
 * @return array       Orders selection
 */
function Orders_getOrderList($status, $offset, $limit, &$count = NULL) {

	if (!in_array($status, Orders_getOrderStatuses())) {
		return getError('wrong_order_status');
	}


	// set where condition:
	// free orders or orders history
	if ($status == 'created') 
	{
		$where = "WHERE status = 'created'";
		$joinColumn = 'customer_id';
	} 
	else // orders history for user
	{
		// check user auth
		if (empty(User_currentUser())) {
			return getError('order_history_need_auth');
		}

		// get users types
		if (User_currentUser()['type'] == 'customer') {
			$currentUserCol = 'customer_id';
			$joinColumn = 'performer_id';
		} else {
			$currentUserCol = 'performer_id';
			$joinColumn = 'customer_id';
		}

		$where = " WHERE status = 'done' AND $currentUserCol = ".intval(User_currentUser()['id']);
	}

	$q = "SELECT COUNT(*) as count
				FROM Orders 
				$where";
	$result = Database_query(Orders_db(), $q, $status);
	$count = Database_selectCell($result);


	// get current range
	$q = "SELECT Orders.id, title, description, price, customer_id, performer_id
				FROM Orders
				INNER JOIN 
				(
					SELECT id 
					FROM Orders 
					$where
					ORDER BY id DESC
					LIMIT ?i, ?i
				) as ids
				ON Orders.id = ids.id
				ORDER BY id DESC";

	$result = Database_query(Orders_db(), $q, $offset, $limit);
	$orders = Database_select($result);

	$orderList = Orders_joinOrderList($orders, $joinColumn);


	return $orderList;
}



/**
 * This method joining OrderList with Users table
 * by one of the two fields: 'performer_id' or 'customer_id'
 *
 * @param array $orderList orders selection
 *
 * @param string('performer_id'|'customer_id') $userColumn joining column
 *
 * @return array joined OrderList
 */
function Orders_joinOrderList($orderList, $userColumn) {

	$ids = array_unique(array_column($orderList, $userColumn));

	$users = array_column(User_getUserByIds($ids), 'login', 'id');

	foreach ($orderList as $key => $order) {
		if (!array_key_exists($order[$userColumn], $users)) {
			unset($orderList[$key]);
		} else {
			$orderList[$key]['user'] = $users[$order[$userColumn]];
		}
	}

	return $orderList;
}



/**
 * Function for performig order
 * This function use database transaction for two tables
 */
function Orders_performOrder($user, $order_id) {

	if ($user['type'] != 'performer') {
		return getError('wrong_user_type');
	}

	$order = Orders_getOrder($order_id);

	if (!empty($order['performer_id'])) {
		return getError('order_has_performer');
	}

	if ($order['status'] != 'created') {
		return getError('wrong_order_status');
	}

	if (!array_key_exists('comission', getConfig('orders'))) {
		return getError('unexpected_comission');
	}


	$comission = getConfig('orders')['comission'];

	Database_beginTransaction(Orders_db());
	Database_beginTransaction(Orders_transactions_db());

	$q = "UPDATE Orders 
				SET performer_id = ?i,
						status = 'done'
				WHERE id = ?i";
	$result = Database_query(Orders_db(), $q, $user['id'], $order_id);

	if (Database_affectedRows() == 0) {
		Database_rollbackTransaction(Orders_db());
		Database_rollbackTransaction(Orders_transactions_db());

		return getError('database_error');
	}

	$payment = $order['price']*(1-$comission);
	$id = Orders_makeTransaction($user['id'], $order_id, $payment);

	Database_commitTransaction(Orders_db());
	Database_commitTransaction(Orders_transactions_db());

	return $id;
}


/**
 * Money transaction
 */
function Orders_makeTransaction($user_id, $order_id, $value) {

	$q = "INSERT INTO Transactions
				(order_id, user_id, value)
				VALUES 
				(?i, ?i, ?d)";
	$result = Database_query(Orders_db(), $q, $order_id, $user_id, $value);	
	$id = Database_insertID();

	$q = "UPDATE Users
				SET account = account + ?d
				WHERE id = ?i";
	$result = Database_query(Orders_db(), $q, $value, $user_id);	
	$count = Database_affectedRows();

	if (!$id || $count == 0) {
		Database_rollbackTransaction(Orders_db());
		Database_rollbackTransaction(Orders_transactions_db());
		return getError('database_error');
	}

	return $id;
}




function Orders_getOrderStatuses() {
	return ['created', 'done'];
}

