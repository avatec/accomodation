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

function errorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        return;
    }

    switch ($errno) {
        case E_ERROR:
            if (Error::$json == true) {
                return ['error' => true, 'msg' => "E_ERROR: {$errno} $errstr"];
            }
            echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
            echo "  Fatal error on line $errline in file $errfile";
            echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
            echo "Aborting...<br />\n";
            exit(1);
            break;

        case E_WARNING:
            Error::show("WARNING - [$errno] $errstr", "on line $errline in file $errfile<br />\n");
            break;

        case E_NOTICE:
            Error::show("NOTICE [$errno] $errstr", "Error on line $errline in file $errfile<br/>\n");
            break;

        case E_USER_WARNING:
            Error::show("FRAMEWORK WARNING [#$errno]", "<b>" . $errstr . "</b><br/>Error on line $errline in file $errfile<br/>\n");
            break;

        default:
            Error::show("Unknown error type", "<b>Error #$errno</b><br/><br/>$errstr<br /><br/><em>Found in file ".__FILE__." on line ".__LINE__."</em>\n");
            break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}

//set_error_handler("errorHandler");
// $whoops = new \Whoops\Run();
// $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
// $whoops->register();

if( !empty( $debug )) {
    error_reporting( -1 );
	ini_set("display_errors" , 0);
}

require_once __DIR__ . '/../classes/autoloader.php';
require_once __DIR__ . "/../include/config/payments.php";
require_once __DIR__ . "/../include/config/smarty.php";
