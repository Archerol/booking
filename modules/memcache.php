<?php

function MCache_connect($name)
{
	// use link for adding connection into global $Config
	$settings = &loadConfig('memcache')['memcache'][$name];

	// get connect if it already exists
	if (!is_null($settings['connect'])) {
		return $settings['connect'];
	}

	$mcConnect = memcache_pconnect($settings['hostname'], $settings['port']);

	if (!$mcConnect) {
		exit("Memcache connect error.");
	}

	// remember connection
	$settings['connect'] = $mcConnect;
	
	return $mcConnect;
}


function MCache_get($mcConnect, $key) {
	return memcache_get($mcConnect, $key);
}


function MCache_set($mcConnect, $key, $value, $lifetime = 300)
{
	return memcache_set($mcConnect, $key, $value, false, $lifetime);
}


function MCache_increment($mcConnect, $key, $value)
{
	return memcache_increment($mcConnect, $key, $value);
}


function MCache_decrement($mcConnect, $key, $value)
{
	return memcache_decrement($mcConnect, $key, $value);
}



