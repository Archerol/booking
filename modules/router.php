<?php

function Router_run($config) {
	$URI = Router_getURI();
	$action = Router_parseURI($config, $URI);
	Router_handleAction($action['file'], $action['function'], $action['params']);
}

function Router_getURI() {
	$URI = explode('?', $_SERVER["REQUEST_URI"]);
	return urldecode(rtrim($URI[0], '/'));
}

/**
 * Parse URI and recursively parfind action in config/router.php
 *
 * Then get params from URI and send them into action
 *
 * @param array $routes  Config with routes
 * 
 */
function Router_parseURI($routes, $requestedURI, $root = '') {

	foreach ($routes as $route => $action) {

		$route = rtrim($root . $route, '/');

		// check array branch
		if (is_array($action)) {
			$params = Router_parseURI($action, $requestedURI, $route);
			if (!empty($params)) {
				return $params;
			}
			continue;
		} 

		// transorm router into a regular expression
		if (strpos($route, '{') !== false) {

			/* two preg_replace can be used instead callback function */
			// $route = preg_replace('#{([^:}]+)}#', '{$1:.+}', $route);
			// $route = preg_replace('#{(\w+):([^}]+)}#', '(?<$1>$2)', $route);

			$route = preg_replace_callback('#{(\w+):?([^}]+)?}#', function($matches) {
					$mask = (isset($matches[2])?$matches[2]:'.+'); // set default mask
					return "(?<$matches[1]>$mask)";
				}, $route
			);
		}

		// matching
		if (preg_match('#^'.$route.'$#', $requestedURI)) {
			
			// get params from url-string
			$params = [];
			if (strpos($route, '(') !== false) {

				preg_match_all('#^'.$route.'$#', $requestedURI, $matches, PREG_SET_ORDER);

				foreach ($matches[0] as $key => $value) {
					if (!is_numeric($key)) {
						$params[$key] = $value;
					}
				}
			}

			// get controller and function
			$pos = strrpos($action, '/');
			$file = substr($action, 0, $pos);
			$fun = substr($action, $pos + 1);

			return [
				'file' => $file,
				'function' => $fun,
				'params' => $params,
			];
		}
	}

	return false;
}


/**
 * Load controller and run function based on parsed URI 
 * and config from config/router.php 
 * If it's POST-ajax-request, send POST into function
 */
function Router_handleAction($file, $function, $params) {
	
	require_once(LOCAL_ROOT . "/controllers/$file.php");

	// call_user_func_array($function, $params);

	if (empty($params) && $_SERVER['REQUEST_METHOD'] == 'POST') {
		$params = $_POST;
	}

	$function($params);
}


