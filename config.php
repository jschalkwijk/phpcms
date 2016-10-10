<?php 
	// define application constants

	define ('IMG_PATH','/images/');
	define ('FILE_PATH', '/files/');
	define ('DB_HOST','localhost');
	define ('DB_USER','root');
	define ('DB_PASSWORD', 'root');
	define ('DB_NAME', 'nerdcms_db');
	define ('HOST','http://'.$_SERVER['HTTP_HOST']);

//class autoloader using PEAR naming convention
spl_autoload_register(function ($class) {
	$path = str_replace('_', DIRECTORY_SEPARATOR, $class );
	// loading non namespaced files.
	if (file_exists('model/'.$path.'.class.php')) {
		include_once 'model/'.$path.'.class.php';
	} else {
		// loading namespaces
		$parts = explode('\\', $class);
		unset($parts[0]);
		unset($parts[1]);
		$file = implode("/",$parts).'.class.php';
		if (file_exists($file)) {;
			require_once $file;
		}
	}
	if(file_exists('model/Encryption/Crypto.php') && file_exists('model/Encryption/autoload.php')){
		// then use the encryption autoloader.
		require_once 'model/Encryption/autoload.php';
	}
});

// Composer autoloader;
require 'vendor/autoload.php';


?>