<?php
use Core\Error;

spl_autoload_register(function ($class) {
    $rootPath = realpath(__DIR__ . "/../") . '/';
    $class = str_replace("\\", "/", $class);
    $class = strtolower($class);

    if ($class == 'modules/pages/frontend/pages') {
        return false;
    }

    if (file_exists($rootPath . $class . '.php')) {
        file_put_contents($rootPath . 'logs/bootstrap.log', $rootPath . $class . '.php' . PHP_EOL, FILE_APPEND);
        include_once $rootPath . $class . '.php';
        return;
    }

    if (file_exists($rootPath . $class . '/autoloader.php')) {
        file_put_contents($rootPath . 'logs/bootstrap.log', $rootPath . $class . '/autoloader.php' . PHP_EOL, FILE_APPEND);
        include_once $rootPath . $class . '/autoloader.php';
        return;
    }

    if (file_exists($rootPath . $class . '/' . $class . '.class.php')) {
        include_once $rootPath . $class . '/' . $class . '.class.php';
        return;
    }

    // includowanie submodułów, interfaceów i innych
    // modules/module/frontend/ModuleSubmoduleInterface.php
    $exp = explode("/", $class);
    if (!empty($exp) && count($exp) == 5) {
        if (!empty($exp[3]) && !empty($exp[4])) {
            $submoduleClass = ucfirst(strtolower($exp[3])) . ucfirst(strtolower($exp[4])) . '.php';
            unset($exp[3]);
            unset($exp[4]);
        }

        if (!empty($submoduleClass)) {
            $path = $rootPath . implode("/", $exp) . "/" . $submoduleClass;
            if (file_exists($path)) {
                include_once $path;
            }
        }
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
