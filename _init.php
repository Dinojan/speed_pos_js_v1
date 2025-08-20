<?php 
session_start();
$timezone = 'Asia/Colombo';
define('APPID', '89d023d1c60abcdb571bc2ba240ee881');
date_default_timezone_set($timezone);
if (!isset($_SERVER['DOCUMENT_ROOT'])) {
	if (isset($_SERVER['SCRIPT_FILENAME'])) {
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}

if (!isset($_SERVER['DOCUMENT_ROOT'])) {
	if (isset($_SERVER['PATH_TRANSLATED'])) {
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}

if (!isset($_SERVER['REQUEST_URI'])) {
	$_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);

	if (isset($_SERVER['QUERY_STRING'])) {
		$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
	}
}

if (!isset($_SERVER['HTTP_HOST'])) {
	$_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');
}

// Check If SSL or Not
if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
	$_SERVER['HTTPS'] = true;
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
	$_SERVER['HTTPS'] = true;
} else {
	$_SERVER['HTTPS'] = false;
}

// Load Config File
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
define('PROTOCOL', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on" ? 'https' : 'http');
$subdir = SUBDIRECTORY ? '/' . rtrim(SUBDIRECTORY, '/\\') : '';
define('ROOT_URL', PROTOCOL . '://' . rtrim($_SERVER['HTTP_HOST'], '/\\') . $subdir);

function autoload($class)
{
	$file = DIR_INCLUDE . 'lib/' . str_replace('\\', '/', strtolower($class)) . '.php';
	if (file_exists($file)) {
		include ($file);
		return true;
	} else {
		return false;
	}
}
spl_autoload_register('autoload');
spl_autoload_extensions('.php');

// Load Registry
$registry = new Registry();

// Session
if (!(PHP_SAPI === 'cli' or defined('STDIN'))) {
	$session = new Session();
	$registry->set('session', $session);
	
}

// Loader
$loader = new Loader($registry);
$registry->set('loader', $loader);

// DB CONFIG.
$dbhost = $sql_details['host'];
$dbname = $sql_details['db'];
$dbuser = $sql_details['user'];
$dbpass = $sql_details['pass'];
$dbport = $sql_details['port'];

$registry->set('dbname', $dbname);

require_once DIR_INCLUDE . '_system.php';

$app = new _system();
$appConfig = $app->_cofig();
$registry->set('app', $app);

// DB Connection
try {
	$db = new Database("mysql:host={$dbhost};port={$dbport};dbname={$dbname};charset=utf8", $dbuser, $dbpass);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	function db()
	{
		global $db;
		return $db;
	}
} catch (PDOException $e) {
	die('Connection error: ' . $e->getMessage());
}
$registry->set('db', $db);

// Request'
$request = new Request();
$registry->set('request', $request);

// Store
$store = new Store($registry);
$registry->set('store', $store);

// Helper
require_once DIR_HELPER . 'customer.php';
require_once DIR_HELPER . 'category.php';
require_once DIR_HELPER . 'common.php';
require_once DIR_HELPER . 'language.php';
require_once DIR_HELPER . 'network.php';
require_once DIR_HELPER . 'product.php';
require_once DIR_HELPER . 'order.php';
require_once DIR_HELPER . 'store.php';
require_once DIR_HELPER . 'supplier.php';
require_once DIR_HELPER . 'user_group.php';
require_once DIR_HELPER . 'user.php';
require_once DIR_HELPER . 'validator.php';


// Document
$document = new Document($registry);
$document->setBodyClass();
$registry->set('document', $document);

// User
$user = new User($registry);
$registry->set('user', $user);
function registry()
{
	global $registry;
	return $registry;
}

// Language
$active_lang = $user->getPreference('language', 'en');
$language = new Language($active_lang);
$registry->set('language', $language);
$language->load();