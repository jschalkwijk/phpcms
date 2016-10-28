<?php 
	// define application constants
//vagrant
//	define ('IMG_PATH','/images/');
//	define ('FILE_PATH', '/files/');
//	define ('DB_HOST','localhost');
//	define ('DB_USER','jorn');
//	define ('DB_PASSWORD', 'root123');
//	define ('DB_NAME', 'cms');
//	define ('HOST','http://'.$_SERVER['HTTP_HOST']);
	// Mac
	 define ('IMG_PATH','/images/');
	 define ('FILE_PATH', '/files/');
	 define ('DB_HOST','localhost');
	 define ('DB_USER','root');
	 define ('DB_PASSWORD', 'root');
	 define ('DB_NAME', 'nerdcms_db');
	 define ('HOST','http://'.$_SERVER['HTTP_HOST']);
//	// Windows
//define ('IMG_PATH','/cms/images/');
//define ('FILE_PATH', '/cms/files/');
//define ('DB_HOST','localhost');
//define ('DB_USER','root');
//define ('DB_PASSWORD', '');
//define ('DB_NAME', 'nerdcms_db');
//define ('HOST','http://'.$_SERVER['HTTP_HOST'].'/cms');

// Composer autoloader;
require_once(__DIR__. '/admin/vendor/autoload.php');


?>