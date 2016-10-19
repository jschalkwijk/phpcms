<?php 
	// define application constants

	define ('IMG_PATH','/cms/images/');
	define ('FILE_PATH', '/cms/files/');
	define ('DB_HOST','localhost');
	define ('DB_USER','root');
	define ('DB_PASSWORD', '');
	define ('DB_NAME', 'nerdcms_db');
	define ('HOST','http://'.$_SERVER['HTTP_HOST'].'/cms');

// Composer autoloader;
require_once(__DIR__. '/admin/vendor/autoload.php');


?>