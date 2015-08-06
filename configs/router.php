<?php

/**
 * Config of routes.
 * Supports nesting.
 * Can gets values from URI with regexp filtering (In examples)
 */

return [
	'/'             => 'page/PageController_index',
	'/create-order' => 'page/PageController_createOrder',

	'/ajax' => [
		'/login'        => 'ajax/AjaxController_login',           //ajax/login
		'/registration' => 'ajax/AjaxController_registration',    //ajax/registration
		'/logout'       => 'ajax/AjaxController_logout',

		'/getUserMoney' => 'ajax/AjaxController_getUserMoney',

		'/orderList'    => 'ajax/AjaxController_orderList',
		'/createOrder'  => 'ajax/AjaxController_createOrder',
		'/performOrder' => 'ajax/AjaxController_performOrder',

		'/{q}' => 'ajax/AjaxController_notFound', // Ajax 404
	],

	'/{q}' => 'error/ErrorController_notFound', // Other requests are handled here

	// Examples:
	//'/test' => 'page/PageController_index',
	//'/test/{type:(edit|delete)}/{digit:\d+}' => 'page/PageController_index',
	//'/orders/{name}/' => 'page/PageController_orderList',
];

