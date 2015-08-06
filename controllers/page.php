<?php

function PageController_index() {
	loadModule('orders');

	$offset = 0;
	$limit = getConfig('orders')['order_list_limit'];

	$orderList = Orders_getOrderList('created', $offset, $limit, $ordersCount);

	showTemplate('index', [
		'orderList' => $orderList,
		'ordersCount' => $ordersCount,
		'offset' => $offset,
		'limit' => $limit,
	]);
}


function PageController_createOrder() {
	showTemplate('createOrder');
}

