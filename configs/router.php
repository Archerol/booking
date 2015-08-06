<?php

/**
 * Config of routes.
 * Supports nesting.
 * Can gets values from URI with regexp filtering (In examples)
 */

return [
	'/' => 'page/PageController_index',

	'/{q}' => 'error/ErrorController_notFound', // Other requests are handled here

	// Examples:
	//'/test' => 'page/PageController_index',
	//'/test/{type:(edit|delete)}/{digit:\d+}' => 'page/PageController_index',
	//'/orders/{name}/' => 'page/PageController_orderList',
];

