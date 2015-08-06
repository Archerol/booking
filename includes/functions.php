<?php

function getConfig($name) {
	$config = loadConfig($name);
	return $config[$name];
}

/**
 * Load config with $name.
 * Use link for adding changes into global $Config.
 * Load config only first time, after just returns it.
 */
function &loadConfig($name) {
	static $Config;

	if (is_array($Config) && array_key_exists($name, $Config)) {
		return $Config;
	}

	$Config[$name] = require_once(LOCAL_ROOT . "/configs/$name.php");
	return $Config;
}


function loadModule($module) {
	require_once(LOCAL_ROOT . "/modules/$module.php");
}


