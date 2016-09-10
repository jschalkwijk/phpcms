<?php 
	// define application constants
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
	
	//class autoloader using PEAR naming convention
	spl_autoload_register(function ($class) {
		$path = str_replace('_', DIRECTORY_SEPARATOR, $class );
		if (file_exists('model/'.$path.'.class.php')) {
			include_once 'model/'.$path.'.class.php';
		} else if(file_exists('model/Encryption/Crypto.php') && file_exists('model/Encryption/autoload.php')){
			// then use the encryption autoloader.
			require_once 'model/Encryption/autoload.php';
		} else {
			echo 'Class not found in admin/model!';
		}
	});

	function login_authenticate() {
		require_once('blocks/secure_login/authorize.php');
	}
	
?>