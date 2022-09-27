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

 if( phpversion() < "5.6" ) {
	 die("We're sorry, but this software requires php version 5.6 or higher");
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
    require_once __DIR__ . '/ajax.php';
    exit;
}

if( $route->isApi == true ) {
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

/**
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1');
header('X-Frame-Options: Deny');

date_default_timezone_set('Europe/Warsaw');
$rustart = getrusage();





if(file_exists( $app_path . "include/config/main.php") == true) {
	require_once $app_path . "include/config/main.php";
} else {
	header('Location: ' . $app_url . 'install/');
	exit;
}

global $app_admin_url;
$app_admin_url = $app_url . $admin_folder . '/';

require_once $app_path . "vendor/autoload.php";
require_once $app_path . "classes/autoloader.php";

require_once $app_path . "classes/functions.php";
require_once $app_path . "classes/error.class.php";






require_once $app_path . "include/config/payments.php";

if( (isset($debug)) && ($debug == true)) {
	error_reporting(E_ALL);
	ini_set("display_errors" , "on");
}

require_once $app_path . "vendor/autoload.php";
require_once $app_path . "classes/autoloader.php";
require_once $app_path . "include/config/smarty.php";
require_once $app_path . "include/config/language.php";

global $route;
$route = new Route();

if( $route->isAdmin == true ) {
	include $app_path . "admin.php";
} elseif( $route->isApi == true ) {
	include $app_path . "api.php";
	exit;
} elseif( $route->isAjax == true) {
	include $app_path . "ajax.php";
    exit;
} elseif( $route->isError == true) {
	include $app_path . "error.php";
    exit;
} elseif( $route->isCron == true) {
	if(file_exists( $app_path . $route->path . ".php")) {
		define("APP" , true);
		include $app_path . $route->path . ".php";
		exit;
	}
    die('Cron command not found');
} else {
    Kernel::addPath(array(
		'name' => '<i class="fas fa-home"></i> Strona główna',
		'url' => $app_url,
		'main' => false
	));

	include $app_path . "page.php";
}

Db::destruct();

function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}

if( $debug == true && isset($runtime) ) {
	$ru = getrusage();
	echo "<p class=\"well text-center\">This process used " . rutime($ru, $rustart, "utime") .
    " ms for its computations. ". "It spent " . rutime($ru, $rustart, "stime") .
    " ms in system calls</p>";
}
**/
