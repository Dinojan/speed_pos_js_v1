<?php 
  $sql_details = array(
    'host' => 'localhost',
     'db' => 'speed_pos_js_v1',
    'user' => 'root',
    'pass' => '', 
    'port' => '3306'
        );
    define('FILE_READ_MODE', 0644);
	define('FILE_WRITE_MODE', 0666);
	define('DIR_READ_MODE', 0755);
	define('DIR_WRITE_MODE', 0755);

    define('ROOT', __DIR__);
	define('ADMINDIRNAME', '_admin');
	define('DIR_INCLUDE', ROOT.'/_inc/');
    define('DIR_HELPER', ROOT.'/_inc/helper/');
    define('DIR_MODEL', DIR_INCLUDE.'/model/');
    define('DIR_LIBRARY', DIR_INCLUDE.'/lib/');
    define('DIR_ASSET', ROOT.'/assets/');
	define('DIR_VENDOR', DIR_INCLUDE.'/vendor/');	

    define('SUBDIRECTORY', 'nit/speed_pos_js_v1/');

    define('SYNCHRONIZATION', false);
	define('SYNCSERVERURL', '');

    define('RTL', false);

    define('ALLOWED_ONLY_IPS', array());
	define('DENIED_IPS', array());


    
	/*
	 * --------------------------------------------------------------------
	 * INVOICE PREFIXES
	 * --------------------------------------------------------------------
	 */
	$invoice_init_prefix = array(
		'purchase' => 'B',
		'due_paid' => 'F',
		'expense' => 'E',
		'withdraw' => 'W',
		'deposit' => 'E',
	);
