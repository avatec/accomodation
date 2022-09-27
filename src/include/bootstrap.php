<?php
use Core\Error;

spl_autoload_register(function($className) {

   $className = str_replace("\\" , "/" , $className);
   $className = strtolower( $className );

   if( file_exists( realpath(__DIR__ . "/../") . '/' . $className . '.php' )) {
       include_once realpath(__DIR__ . "/../") . '/' . $className . '.php';
   } else {
       trigger_error( realpath(__DIR__ . "/../") . '/' . $className . '.php file not found' );
   }
});

error_reporting( E_ALL );
ini_set('display_errors' , 1);

if(file_exists( $app_path . "include/config/main.php") == true) {
	require_once $app_path . "include/config/main.php";
} else {
	header('Location: ' . $app_url . 'install/');
	exit;
}

set_error_handler([ 'Core\Error' , 'CustomErrorHandler']);

if( !empty( $debug )) {
    error_reporting( -1 );
	ini_set("display_errors" , 0);
}

require_once __DIR__ . '/../classes/autoloader.php';
require_once __DIR__ . "/../include/config/payments.php";
require_once __DIR__ . "/../include/config/smarty.php";
