<?php
// Windows application constants
// define ('DB_HOST','localhost');
// define ('DB_USER','root');
// define ('DB_PASSWORD', '');
// define ('DB_NAME', 'nerdcms_db');
// define ('IMG','/cms/admin/images/');
// define ('FILES', $_SERVER['HTTP_HOST'].'/cms/admin/files/');
// define('JS','http://'.$_SERVER['HTTP_HOST'].'/cms/admin/js/');
// define ('ADMIN', 'http://'.$_SERVER['HTTP_HOST'].'/cms/admin/');
// define ('HOME','http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/cms');
// define ('HOST','http://'.$_SERVER['HTTP_HOST'].'/cms');
//Vagrant
//define ('DB_HOST','localhost');
//define ('DB_USER','jorn');
//define ('DB_PASSWORD', 'root123');
////define ('DB_NAME', 'cms');
//define ('DB_NAME', 'nerdcms_db');
//define ('IMG','/admin/images/');
//define ('FILES', $_SERVER['HTTP_HOST'].'/admin/files/');
//define('JS','http://'.$_SERVER['HTTP_HOST'].'/admin/js/');
//define ('ADMIN', 'http://'.$_SERVER['HTTP_HOST'].'/admin/');
//define ('HOME','http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']));
//define ('HOST','http://'.$_SERVER['HTTP_HOST']);
//// Mac
 define ('DB_HOST','localhost');
 define ('DB_USER','root');
 define ('DB_PASSWORD', 'root');
 define ('DB_NAME', 'nerdcms_db');
 define ('IMG','/admin/images/');
 define ('FILES', $_SERVER['HTTP_HOST'].'/admin/files/');
 define('JS','http://'.$_SERVER['HTTP_HOST'].'/admin/js/');
 define ('ADMIN', 'http://'.$_SERVER['HTTP_HOST'].'/admin/');
 define ('HOME','http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']));
 define ('HOST','http://'.$_SERVER['HTTP_HOST']);

spl_autoload_register(function ($class) {
	if(file_exists('CMS/Models/Encryption/Crypto.php') && file_exists('CMS/Models/Encryption/autoload.php')){
		// then use the encryption autoloader.
		require_once 'CMS/Models/Encryption/autoload.php';
	}
});

require_once(__DIR__. '/vendor/autoload.php');

//function login_authenticate() {
//	require_once('blocks/secure_login/authorize.php');
//}

?>