<?php


/**
 * Create database connect.
 * Settings gets from config/db.php, from array with key = $name
 * Function creates connect only first time and remembers it.
 * In next time function returns connection which already created.
 */
function Database_connect($name)
{
	// use link for adding connection into global $Config
	$settings = &loadConfig('db')['db'][$name];

	// get connect if it already exists
	if (!is_null($settings['connect'])) {
		return $settings['connect'];
	}

	$dbConnect = mysqli_connect(
		$settings['hostname'], 
		$settings['username'],
		$settings['password'],
		$settings['database']
	);

	if (!$dbConnect) {
		exit("Database connect error: " . mysqli_connect_error());
	}

	Database_setCharset($dbConnect, $settings['encoding']);

	// remember connection
	$settings['connect'] = $dbConnect;
	
	return $dbConnect;
}


function Database_setCharset($dbConnect, $charset) {
	mysqli_set_charset($dbConnect, $charset);
}

/**
 * The function uses prepared statements.
 * Replacement params in mysqli format
 * Makes binding and query.
 * 
 *
 * @param mysqli_connect $dbConnect  Connection to database
 *
 * @param string $query  SQL-query to database 
 * Query can use ?i, ?d, ?s, ?b, ?a types for params
 *
 * @return mysqli_result  Database result
 */
function Database_query($dbConnect, $query)
{
	// getting variables for sql-query from function's arguments
	// 2 - skip first two params of function 
	$args = array_slice(func_get_args(), 2); 

	// getting types of variables in sql-query
	// ?i - integer
	// ?d - double
	// ?s - string
	// ?b - blob
	// ?a - array of strings
	$matches_count = preg_match_all('/\?([idsba])/', $query, $matches);

	if (sizeof($matches) < 2) {
		return false;
	}

	// getting params for binding
	$params = [NULL, '']; // [ $stmt, $types ]
	$types = '';
	foreach ($matches[1] as $i => $type) {
		if ($type != 'a') {
			$types .= $type;
			$params[] = &$args[$i];
		} else {
			// array transform
			if (!array_key_exists($i, $args)) {
				return false;
			}

			if (!is_array($args[$i])) {
				$args[$i] = [$args[$i]];
			} 

			foreach ($args[$i] as $k => &$v) {
				$params[] = &$v;
			}

			$length = sizeof($args[$i]);
			$types .= str_repeat('s', $length);
			$replace = substr(str_repeat('?,', $length), 0, -1);
			if ($replace === false) {
				$replace = "''";
			}
			$query = preg_replace('/\?a/', $replace, $query, 1);
		}
	}

	// prepare statement
	// array already transformed 
	$query = preg_replace('/\?([idsb])/', '?', $query);
	if (!$stmt = mysqli_prepare($dbConnect, $query)) {
		trigger_error(mysqli_error($dbConnect));
		return false;
	}

	// first two params
	$params[0] = $stmt; 
	$params[1] = $types;

	// mysqli binding
	if ($types !== '') {
		call_user_func_array("mysqli_stmt_bind_param", $params);
	}

	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);
	
	Database_affectedRows(mysqli_affected_rows($dbConnect));
	Database_insertID(mysqli_insert_id($dbConnect));

	mysqli_stmt_close($stmt);

	return $result;
}


// Transaction functions 

function Database_beginTransaction($dbConnect) {
	mysqli_begin_transaction($dbConnect);
}

function Database_commitTransaction($dbConnect) {
	mysqli_commit($dbConnect);
}

function Database_rollbackTransaction($dbConnect) {
	mysqli_rollback($dbConnect);
}

/**
 * Fetch mysqli_result into Two-dimensional associative array 
 * 
 * @param mysqli_resul $result  Mysqli result from query
 *
 * @param boolean $return_assoc  If if false function returns vector
 *
 * @param string $key  Return selection where key of row is one of the fields
 *
 * @return array  Two-dimensional associative array 
 */
function Database_select($result, $return_assoc = true, $key = '') {
	if (mysqli_num_rows($result) == 0) {
		return [];
	}

	$method = ($return_assoc ? 'mysqli_fetch_assoc' : 'mysqli_fetch_row');

	$data = [];
	
	if (empty($key)) 
	{
		while ($row = $method($result)) {
			$data[] = $row;
		}
	} 
	else 
	{
		$row = $method($result);
		if (!array_key_exists($key, $row)) {
			return false;
		}
		$data[$key] = $row;

		while ($row = $method($result)) {
			$data[$key] = $row;
		}
	}

	
	mysqli_free_result($result);

	return $data;
}

/**
 * Fetch from mysqli_result first row as associative array
 */
function Database_selectRow($result, $return_assoc = true) {
	if (!$result || mysqli_num_rows($result) == 0) {
		return NULL;
	}

	$method = ($return_assoc ? 'mysqli_fetch_assoc' : 'mysqli_fetch_row');

	$data = $method($result);

	mysqli_free_result($result);

	return $data;
}

/**
 * Fetch from mysqli_result first cell
 */
function Database_selectCell($result) {
	if (!$result || mysqli_num_rows($result) == 0) {
		return NULL;
	}

	$data = mysqli_fetch_row($result);
	return $data[0];
}

/**
 * Fetch from mysqli_result first column
 */
function Database_selectCol($result) 
{     
	if (!$result || mysqli_num_rows($result) == 0) {
		return NULL;
	}

	$data = [];

	while ($row = mysqli_fetch_row($result)) {
		$data[] = $row;
	}

	return array_column($data, 0);
}

/**
 * Returns affected rows from last query
 */
function Database_affectedRows($set = null) {
	static $rows;

	if (!empty($set)) {
		$rows = $set;
		return true;
	}

	return (!empty($rows) ? $rows : false);
}

/**
 * Returns inserted ID from last query
 */
function Database_insertID($set = null) {
	static $id;
	
	if (!empty($set)) {
		$id = $set;
		return true;
	}

	return (!empty($id) ? $id : false);
}

