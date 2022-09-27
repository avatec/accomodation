<?php
use \Core\Assets;

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

Assets::frontend();

/**
 *	Autoloader dla nowych modułów
 * 	Obsługujący wszystkie moduły dzielone
 */

	$mod_dir = scandir( $app_path . 'modules/' );
	if(!empty( $mod_dir )) {
		foreach( $mod_dir as $mod_file ) {
			if( System::file_exists( $app_path . 'modules/' . $mod_file . '/frontend/autoloader.php') == true ) {
				require_once $app_path . "modules/" . $mod_file . "/frontend/autoloader.php";
			}
		}
	}

User::getUserData();

if($_SERVER['REQUEST_URI'] == "/") {
	Kernel::schema("main");
} else {
	Kernel::addPath(array(
		'name' => '<i class="fas fa-home"></i> Strona główna',
		'url' => $app_url,
		'main' => false
	));
}

include_once __DIR__ . "/include/content.php";

// Get META
$smarty->assign("assets" , Assets::get());
$smarty->assign("stats" , Stats::get());
$smarty->assign("meta" , Kernel::getMeta());
$smarty->assign("tpl" , $tpl = Kernel::getTpl());

if( ( empty($tpl['schema']) && empty($tpl['file']) ) || (http_response_code() == 404 )) {
	$smarty->display($app_path . "templates/website/error.smarty");
	http_response_code(404);
	exit;
}

$smarty->assign("config" , array_merge($config, $system->configGet()));
$smarty->assign("expires" , date('D, d M Y H:i:s', strtotime("+14 days")) . ' GMT');
$smarty->assign("now" , date('Y-m-d'));
$smarty->assign("javascript" , Kernel::getJs());
$smarty->assign("css" , Kernel::getCss());
$smarty->assign("messages" , $a = array(
	"notice" => Kernel::getMessage("NOTICE"),
	"error" => Kernel::getMessage("ERROR"),
	"warning" => Kernel::getMessage("WARNING"),
	"info" => Kernel::getMessage("INFO")
));

$smarty->display($app_path . "templates/website/index.smarty");

Kernel::clearMessages();
Paginate::clear();
