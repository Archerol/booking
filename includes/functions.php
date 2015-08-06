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


/**
 * Show template.
 * 
 * @param string $template  Name of template
 *
 * @param array $vars  Variabels sended into template
 *
 * @param boolead $return  If true returns template instead showing. (Used in ajax)
 */
function showTemplate($template, $vars = [], $return = false) {
	foreach ($vars as $key => $value) {
		${$key} = $value;
	}

	if (!$return) 
	{
		include(LOCAL_ROOT . "/templates/$template.php");
	} 
	else 
	{
		ob_start();
		include(LOCAL_ROOT . "/templates/$template.php");
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}
}

