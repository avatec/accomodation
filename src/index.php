<?php
/**
 * Avatec Accomodation
 *
 * @author		Grzegorz Miskiewicz <biuro@avatec.pl>
 * @license		Property license
 *
 * Ten produkt jest licencjonowany
 * Możesz modyfikować te pliki jednak nie możesz usuwać oryginalnych komentarzy
 * w szczególności informacji o autorze tego oprogramowania<
 *
 * W przypadku indywidualnego modyfikowania tego pliku autor nie ponosi
 * odpowiedzialności za wszelkie błędy i/lub wady tego oprogramowania.
 */

 if( phpversion() < "8.0" ) {
	 die("We're sorry, but this software requires php version 8.0 or higher");
 }

 session_start();

 require_once  __DIR__ . "/vendor/autoload.php";

 global $app_path;
 $app_path = realpath(dirname(__FILE__)) . '/';

 global $app_url;
 $app_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/';

 global $app_request_url;
 $app_request_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

 global $logs;
 $logs = $app_path . "logs/";

if( file_exists( $app_path . 'include/bootstrap.php') == false ) {
	die('Fatal Error: include/bootstrap.php file is missing');
}

include_once __DIR__ . '/include/bootstrap.php';

if( $route->isAdmin == true ) {
    require_once __DIR__ . '/admin.php';
    exit;
}

if( $route->isAjax == true ) {
	if (!empty($debug)) {
        $whoops = new \Whoops\Run();
        $whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler());
        $whoops->register();
    }
    require_once __DIR__ . '/ajax.php';
    exit;
}

if( $route->isApi == true ) {
	if (!empty($debug)) {
        $whoops = new \Whoops\Run();
        $whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler());
        $whoops->register();
    }
    require_once __DIR__ . '/api.php';
    exit;
}

if( $route->isError == true ) {
    require_once __DIR__ . '/error.php';
    exit;
}

if( $route->isUpload == true ) {
    require_once __DIR__ . '/upload.php';
    exit;
}

if( $route->isCron == true && !empty( $route->path ) ) {
    if(file_exists( $app_path . $route->path . ".php")) {
		define("APP" , true);
		require_once $app_path . $route->path . ".php";
		exit;
	}
    die('Cron command not found');
}

require_once __DIR__ . '/page.php';