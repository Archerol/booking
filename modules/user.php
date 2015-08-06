<?php
loadConfig('db');
loadModule('database');

function User_db() {
	return Database_connect(USER_DB);
}

/**
 * This function works like Singleton 
 * and allows to get current authorized user in all places of the code
 */
function User_currentUser($setUser = null) 
{
	static $user;

	if (!empty($setUser)) {
		$user = $setUser;
		return true;
	}

	return $user;
}

/**
 * Check Cookies and get authorized user if he exists
 */
function User_getUserFromCookie() {
	if (array_key_exists('sid', $_COOKIE) && array_key_exists('login', $_COOKIE))
	{
 		$user = User_isUserLogged($_COOKIE['login'], $_COOKIE['sid']);
 		if (!empty($user)) {
 			return $user;
 		}
	}

	User_logout();
	return null;
}


function User_getUserById($id) 
{
	$q = "SELECT id, login, type, account, hsid, password
				FROM Users	
				WHERE id = ?i";

	$result = Database_query(User_db(), $q, $id); 
	return Database_selectRow($result);
}

/**
 * Get list of users based on array of theirs IDs.
 * Used for joining with order list
 */
function User_getUserByIds($ids) 
{
	$q = "SELECT id, login, type
				FROM Users	
				WHERE id IN (?a)";

	$result = Database_query(User_db(), $q, $ids); 
	return Database_select($result);
}


function User_getUserByLogin($login) 
{
	$q = "SELECT id, login, type, hsid, account, password
				FROM Users 
				WHERE login = ?s";

	$result = Database_query(User_db(), $q, $login); 
	return Database_selectRow($result);
}


function User_isExistsLogin($login) 
{
	$q = "SELECT COUNT(*) FROM Users WHERE login = ?s";
	$result = Database_query(User_db(), $q, $login); 
	if (Database_selectCell($result) > 0)
		return true;
	else
		return false;
}

/**
 * Create and authorizes user with cookies
 * Cookie save login for identification
 * and hash of ($id + $hashed_password) for authentication
 *
 * @param string('customer'|'performer') $type  Type of user
 *
 * @param int $lifetime  Session lifetime in seconds
 *
 */
function User_register($login, $password, $confirm, $type, $lifetime) 
{
	if (!User_isValidLogin($login))
		return getError('wrong_login');

	if (User_isExistsLogin($login))
		return getError('login_already_exists');

	if ($password != $confirm) {
		var_dump(getError('wrong_confirm_password'));
		return getError('wrong_confirm_password');
	}

	if (!in_array($type, User_getUserTypes())) 
		return getError('unknown_user_type');

	$parts = explode("@", $login); 
	$username = $parts[0];

	$time = time();
	$hpass = User_hashPassword($password);
	
	$q = "INSERT INTO Users 
				SET type = ?s,
						login	= ?s,
						password = ?s";
	$result = Database_query(User_db(), $q, $type, $login, $hpass);
	$id = Database_insertID();

	if (!$id) {
		return getError('database_error');
	}

	$sid = User_createSID($id, $hpass);
	$hsid = User_hashSID($sid);

	if (!User_setSID($hsid, $id)) {
		return getError('set_sid_error');
	}

	User_setCookie($login, $sid, $lifetime);
	return $id;
}


function User_login($login, $pass, $lifetime) 
{
	if (!User_isValidLogin($login))
		return getError('wrong_login');

	if (!User_isExistsLogin($login))
		return getError('login_not_exists');

	$user = User_getUserByLogin($login);

	if (!password_verify($pass, $user['password'])) {
		return getError('wrong_password');
	}

	$sid = User_createSID($user['id'], $user['password']);
	User_setCookie($user['login'], $sid, $lifetime);

	User_currentUser($user);

	return $user['id'];
}

/**
 * Check user's cookie and returns user if he's valid
 */
function User_isUserLogged($login, $sid) 
{
	$user = User_getUserByLogin($login);

	if (!password_verify($sid, $user['hsid'])) {
		return false;
	}

	return $user;
}


function User_setSID($hsid, $id)
{
	$q = "UPDATE Users
				SET hsid = ?s
				WHERE id = ?i";

	$result = Database_query(User_db(), $q, $hsid, $id);

	if (Database_affectedRows() > 0) {
		return true;
	} else {
		return false;
	}
}


function User_setCookie($login, $sid, $lifetime)
{
	setcookie("login", $login, time() + $lifetime, '/');
	setcookie("sid", $sid, time() + $lifetime, '/');
}


function User_logout() 
{
	setcookie("login", 0, time() - 3600, '/');
	setcookie("sid", 0, time() - 3600, '/');
}


function User_isValidLogin($login) {
	if (!preg_match('/^[\w\.@-]{1,50}$/iu', $login)) {
		return false;
	}

	return true;
}


function User_getUserTypes() {
	return ['customer','performer'];
}


function User_createSID($id, $password) {
	return hash("sha256", $id . $password);
}


function User_hashSID($sid) { 
	return password_hash($sid, PASSWORD_DEFAULT);
}


function User_hashPassword($password) {
	return password_hash($password, PASSWORD_DEFAULT);
}
