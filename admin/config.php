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
// Mac
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
//	$path = str_replace('_', DIRECTORY_SEPARATOR, $class );
//	// loading non namespaced files.
//	if (file_exists('model/'.$path.'.class.php')) {
//		include_once 'model/'.$path.'.class.php';
//	} else {
//		// loading namespaces
//		$parts = explode('\\', $class);
//		unset($parts[0]);
//		unset($parts[1]);
//		$file = implode("/",$parts).'.class.php';
//		if (file_exists($file)) {;
//			require_once $file;
//		}
// 	}

	if(file_exists('CMS/model/Encryption/Crypto.php') && file_exists('CMS/model/Encryption/autoload.php')){
		// then use the encryption autoloader.
		require_once 'CMS/model/Encryption/autoload.php';
	}
});

// Composer autoloader;
### !!!!Waarom werkt mijn persoonlijke autoload in de composer.json file niet??? !!!!!
### omdat ik twee verschillende index files heb voor de home en admin area
### werkt de autloader niet goed. moet ik alleen de admin folder een composer dir hebben?
## en die in de front nd verwijderen? of een index file hebben?


// deze moet nog even uit ivm de encryptie library;
//require '../vendor/autoload.php';
require_once(__DIR__. '/vendor/autoload.php'); 

function login_authenticate() {
	require_once('blocks/secure_login/authorize.php');
}

?>