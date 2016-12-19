<?php

    define ('DB_HOST','localhost');
    define ('DB_USER','root');
    define ('DB_PASSWORD', '');
    define ('DB_NAME', '');
    define ('IMG','');
    define ('FILES', $_SERVER['HTTP_HOST'].'');
    define('JS','http://'.$_SERVER['HTTP_HOST'].'');
    define ('ADMIN', 'http://'.$_SERVER['HTTP_HOST'].'');
    define ('HOME','http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']));
    define ('HOST','http://'.$_SERVER['HTTP_HOST']);

    spl_autoload_register(function ($class) {
        if(file_exists('CMS/Models/Encryption/Crypto.php') && file_exists('CMS/Models/Encryption/autoload.php')){
            // then use the encryption autoloader.
            require_once 'CMS/Models/Encryption/autoload.php';
        }
    });

    require_once(__DIR__. '/vendor/autoload.php');

?>