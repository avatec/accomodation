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

use Core\Error;
use Core\Assets as Assets;
use Modules\Admins as Admins;
use Modules\Admins\Tokens as AdminsTokens;

Assets::backend();

global $app_admin_url;
$app_admin_url = $app_url . $admin_folder . '/';

if(!empty(Admins::$auth['token'])) {
	if( AdminsTokens::update( Admins::$auth['token'] ) == false ) {
		$admins->logout();
	}
}


LA::change("pl");
LA::load('cms' , true);

if($route->num == 3) {
	$folder = $route->path_array['1'];
	$file = $folder;
}

if($route->num == 4) {
	$folder = $route->path_array['1'];
	$file = $route->path_array['2'];
}

global $command;
$command = end($route->path_array);

if( $admins->login_is_blocked() == true ) {
	if($command !== 'login.html') {
		Kernel::redirect( $app_admin_url . 'login.html');
	}

	$command = "login.html";
}

/**
 *	Autoloader dla nowych modułów
 * 	Obsługujący wszystkie moduły dzielone
 */

	$mod_dir = scandir( $app_path . 'modules/' );
	if(!empty( $mod_dir )) {
		foreach( $mod_dir as $mod_file ) {
			if( System::file_exists( $app_path . 'modules/' . $mod_file . '/backend/autoloader.php') == true ) {
				require_once $app_path . "modules/" . $mod_file . "/backend/autoloader.php";
			}
		}
	}

if((!isset($folder)) && (!isset($file))) {
	if(!empty($request->get['changeLang'])) {
		Language::change( $request->get['changeLang'] );
		Kernel::redirect( $app_url . "admin/" . (isset($request->get['redirect']) ? $request->get['redirect'] : "start.html") );
	}
	switch($command) {
		case "start.html":
			Kernel::$ModuleName = "Pulpit";
			Kernel::template("start.smarty");
			Kernel::module("system");
			$smarty->assign("avatec_news" , Kernel::getAvatecNews());
			$smarty->assign("change_log" , Kernel::changeLog(2));
		break;

		case "changelog.html":
			Kernel::$ModuleName = "Lista zmian";
			Kernel::module("system");
			Kernel::template("changelog.smarty");
			$smarty->assign("list" , Kernel::changeLog());
		break;

		case "reset.html":
			if(!empty($request->post['module'])) {
				if( $admins->reset_password() == true ) {
					Kernel::redirect( $app_admin_url . 'login.html#forgot_pwd' );
				}
			}
		break;

		case "login.html":
			$smarty->assign("is_blocked" , $admins->login_is_blocked());
			if(!empty($request->post['module'])) {
				if($admins->login() == true ) {
					Kernel::redirect( $app_admin_url . "start.html");
				}
			}
		break;

		case "change-password.html":
			Kernel::module("system");
			Kernel::template("change-password.smarty");
			Kernel::setJs("generate.js", "system" , true);
			if(!empty($request->post['module'])) {
				if( $admins->change_password_login() == true ) {
					Kernel::redirect( $app_admin_url . "login.html");
				}
			}
		break;

		case "logout.html":
			$admins->logout();
			Kernel::redirect( $app_admin_url . "login.html");
		break;
	}
} else {
	if(empty(Admins::$auth['id'])) {
		Kernel::redirect( $app_admin_url . "login.html");
	}

	/** Nowy typ modułów - rozdzielone pomiędzy backend i frontend **/
	if( System::file_exists( $app_path . "modules/" . $folder . "/backend/" . $file . ".backend.php" ) == true ) {
		include_once $app_path . "modules/" . $folder . "/backend/" . $file. ".backend.php";
		$new_module_type = true;
	} else {
		include_once $app_path . "modules/" . $folder . "/" . $file . ".admin.php";
	}
}

if(empty(Kernel::$tpl['file'])) {
	$smarty->assign("warning_tpl" , true);
	$smarty->assign("template_file" , $app_path . "templates/admin/start.smarty");
} else {
	if( empty( $new_module_type )) {
		if(file_exists($app_path . (isset($folder) ? "modules/" . $folder : ''). "/templates/admin/" . Kernel::$tpl['file']) == true) {
			$smarty->assign("template_file" , $app_path . (isset($folder) ? "modules/" . $folder : ''). "/templates/admin/" . Kernel::$tpl['file']);
		} else {
			Error::show(
				"APP FATAL ERROR: Template file not found" ,
				"App can't find template file at <b>" . $app_path . (isset($folder) ? "modules/" . $folder : ''). "/templates/admin/" . Kernel::$tpl['file'] . "</b>.<br/>Verify if this file exists and check accessable of this folder and file."
			);
		}
	} else {
		// Nowy typ modułów
		if( System::file_exists( $app_path . "modules/" . $folder . "/backend/views/" . Kernel::$tpl['file'] ) == true ) {
			$smarty->assign("template_file" , $app_path . (isset($folder) ? "modules/" . $folder : ''). "/backend/views/" . Kernel::$tpl['file']);
		}
	}
}

// Read messages
$smarty->assign("messages" , array(
	"notice" => Kernel::getMessage("NOTICE"),
	"error" => Kernel::getMessage("ERROR"),
	"warning" => Kernel::getMessage("WARNING"),
	"info" => Kernel::getMessage("INFO")
));

$smarty->assign("assets" , Assets::get());
$smarty->assign("javascript" , Kernel::getJs()); // deprecated @ 1.5
$smarty->assign("css" , Kernel::getCss()); // deprecated @ 1.5
$smarty->assign("access" , (!empty( User::$admin ) ? User::$admin['access'] : null));
$smarty->assign("stats" , Stats::get());
$smarty->assign("version" , Kernel::getVersion());
$smarty->assign("app_admin_url" , $app_admin_url);

$smarty->registerClass('Admins' , '\Modules\Admins');

if(empty(Admins::$auth['id'])) {
	$smarty->display($app_path . "templates/admin/login.smarty");
} else {
	$smarty->assign("access" , Modules\Admins::$auth['access']);
	$smarty->display($app_path . "templates/admin/index.smarty");
}

Kernel::clearMessages();
Paginate::clear();
