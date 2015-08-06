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

/**
 * Function for getting error code based on error name
 */
function getError($key) {
	$errors = getConfig('errors');

	if (array_key_exists($key, $errors)) {
		return $errors[$key];
	} else {
		return $errors['unknown_error'];
	}
}

/**
 * Function for getting translated message from lang
 */
function getMessage($messageKey) {
	$lang = loadLanguage();
	if (array_key_exists($messageKey, $lang)) {
		return $lang[$messageKey];
	} else {
		return $messageKey;
	}
}

function loadLanguage($setLocale = null) {
	static $locale;

	if (!empty($setLocale)) {
		$locale = $setLocale;
	}

	if (empty($locale)) {
		return false;
	}

	return getConfig('lang/'.$locale);
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

