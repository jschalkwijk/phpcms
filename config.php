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
		if (file_exists('model/'.$path.'.class.php')) {
			include_once 'model/'.$path.'.class.php';
		} else if (file_exists(__DIR__.'/vendor/autoload.php')) {
			require __DIR__.'/vendor/autoload.php';
		} else {
			echo 'Class not found in Models or Vendor folder!';
		}
	});


?>