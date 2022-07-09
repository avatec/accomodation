<?php
session_start();
date_default_timezone_set('Europe/Warsaw');

$debug = true;

global $app_path;
$app_path = realpath(dirname(__FILE__)) . '/';

global $app_url;
$app_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/' . 'install/';

global $app_request_url;
$app_request_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

global $logs;
$logs = $app_path . "logs/";

if( phpversion() < "5.4" ) {
	die("We're sorry, but this software requires php version 5.4 or higher");
}

if( (isset($debug)) && ($debug == true)) {
	error_reporting(E_ALL);
	ini_set("display_errors" , "on");
}

include "../vendor/autoload.php";
include "../classes/cms.class.php";
include "../classes/request.class.php";
include "../classes/form.class.php";
include "classes/install.class.php";

$request = new Request();

$smarty	= new Smarty();
$smarty->compile_dir = $app_path . 'cache/';
$smarty->plugins_dir = array(
	$app_path . '/vendor/smarty/smarty/libs/plugins/'
);

$smarty->assign("app_path" , $app_path);
$smarty->assign("app_url" , $app_url);
$smarty->assign("app_request_url" , $app_request_url);

$ext = new ReflectionExtension('mysqli');
$mysql = $ext->getVersion();

if( function_exists('apache_get_modules') == true ) {
	$mod_rewrite = in_array('mod_rewrite', apache_get_modules());
} else {
	$mod_rewrite = false;
}

$smarty->assign("extensions" , [
	'php_version' => phpversion(),
	'curl' => extension_loaded('curl'),
	'gd' => extension_loaded('gd'),
	'imagick' => extension_loaded('imagick'),
	'mod_rewrite' => $mod_rewrite,
	'mysql' => $mysql
]);

if(!empty($request->post['module'])) {
	if(empty($request->post['license_name'])) {
		$Error[] = "musisz wprowadzić imię i nazwisko z licencji";
	}
	if(empty($request->post['license_email'])) {
		$Error[] = "musisz wprowadzić adres e-mail z licencji";
	}
	if(empty($request->post['license_key'])) {
		$Error[] = "musisz wprowadzić numer licencji";
	}
	if(empty($request->post['db_host'])) {
		$db['host'] = "localhost";
	}
	if(empty($request->post['db_port'])) {
		$db_port = 3306;
	} else {
		$db_port = $request->post['db_port'];
	}
	if(empty($request->post['db_name'])) {
		$Error[] = "musisz wprowadzić nazwę bazy danych";
	}
	if(empty($request->post['db_user'])) {
		$Error[] = "musisz wprowadzić nazwę użytkownika bazy danych";
	}
	if(empty($request->post['db_pass'])) {
		$Error[] = "musisz wprowadzić hasło użytkownika bazy danych";
	}

	if(!empty($Error)) {
		$smarty->assign("Error" , $Error);
	} else {
		Install::$data = [
			"db_host" => $request->post['db_host'],
			"db_port" => $db_port,
			"db_name" => $request->post['db_name'],
			"db_user" => $request->post['db_user'],
			"db_pass" => $request->post['db_pass'],
			"license" => [
				'name' => $request->post['license_name'],
				'email' => $request->post['license_email'],
				"key" => $request->post['license_key']
			]
		];
		$result = Install::verify();
		if($result == true) {
			$smarty->assign("Ok" , true);
		} else {
			if(!empty(Install::$Error)) {
				$smarty->assign("Error" , self::$Error);
			} else {
				$smarty->assign("Error" , "Podczas operacji w bazie danych wystąpił błąd. Prześlij do nas swój plik <a href=\"/logs/install.log\">install.log</a>.");
			}
		}

	}
}

$smarty->display($app_path . "templates/index.smarty");
