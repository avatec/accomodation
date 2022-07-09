<?php
use Core\Error;

require_once $app_path . "classes/cms.class.php";
Kernel::loadClass("classes/common.class.php");
Kernel::loadClass("classes/db.class.php");
Kernel::loadClass("classes/language.class.php");
Kernel::loadClass("classes/language_admin.class.php");
Kernel::loadClass("classes/paginate.class.php");
Kernel::loadClass("classes/cache.class.php");
Kernel::loadClass("classes/request.class.php");
Kernel::loadClass("classes/routing.class.php");
Kernel::loadClass("classes/form.class.php");
Kernel::loadClass("classes/mail.class.php");
Kernel::loadClass("classes/thumbs.class.php");

require_once __DIR__ . '/../core/assets.php';
require_once __DIR__ . '/../core/backend/navigation.php';

if(!isset($config['basic'])) {
	Kernel::loadClass("classes/smsgateway.class.php");
}

global $request;
$request = new Request();

global $route;
$route = new Route( false );

Kernel::registerComponent( null , "Bez modułu" , null);

require_once $app_path . "include/config/language.php";

$route->get_path();

if( $route->asCatalog == true) {
	$route->catalog = "development/cms2/";
	$app_url = $app_url . $route->catalog;
}

Kernel::loadClass("modules/system/_autoloader.php");

$config = array_merge($config, $system->configGet());

Kernel::loadClass("modules/admins/_autoloader.php");

Kernel::loadClass("modules/objects/objects.class.php");
$objects = new Objects();

Kernel::loadClass("modules/objects/photos.class.php");
$photos = new ObjectsPhotos();

Kernel::loadClass("modules/objects/photosroom.class.php");
$photosroom = new ObjectsPhotosRoom();

Kernel::loadClass("modules/objects/video.class.php");
$video = new ObjectsVideos();

Kernel::loadClass("modules/objects/rooms.class.php");
$rooms = new ObjectsRooms();

Kernel::loadClass("modules/objects/prices.class.php");
$prices = new ObjectsPrices();

Kernel::loadClass("modules/objects/comments.class.php");
$comments = new ObjectsComments();

Kernel::loadClass("modules/objects/states.class.php");
$states = new ObjectsStates();

Kernel::loadClass("modules/objects/cities.class.php");
$cities = new ObjectsCities();

Kernel::loadClass("modules/objects/country.class.php");
$country = new ObjectsCountrys();

Kernel::loadClass("modules/objects/types.class.php");
$types = new ObjectsTypes();

Kernel::loadClass("modules/objects/location.class.php");
$location = new ObjectsLocation();

Kernel::loadClass("modules/objects/distance.class.php");
$distance = new ObjectsDistance();

Kernel::loadClass("modules/objects/equipment.class.php");
$equipment = new ObjectsEquipment();

Kernel::loadClass("modules/objects/improvement.class.php");
$improvement = new ObjectsImprovement();

Kernel::loadClass("modules/special/_autoloader.php");

Kernel::loadClass("modules/news/_autoloader.php");
Kernel::loadClass("modules/slider/_autoloader.php");
Kernel::loadClass("modules/partner/_autoloader.php");
Kernel::loadClass("modules/advertising/_autoloader.php");
Kernel::loadClass("modules/newsletter/newsletter.class.php");
Kernel::loadClass("modules/newsletter/messages.class.php");
$newsletter = new Newsletter();
$messages = new NewsletterMessages();

if(isset($config['premium']) || isset($config['exclusive'])) {
	Kernel::loadClass("modules/business/_autoloader.php");

	// Moduły wersji exclusive
	if(isset($config['exclusive'])) {
		Kernel::loadClass("modules/booking/_autoloader.php");
	}
}

Kernel::loadClass("modules/content/_autoloader.php");


Kernel::loadClass("plugins/ReCaptcha/autoload.php");
Kernel::generateToken(25);

if( file_exists( $app_path . "modules/payment/" . $config['payments_module'] . ".class.php") == true) {
	Kernel::loadClass("modules/payment/payment.class.php");
	$payment = new Payment();

	include $app_path . "modules/payment/" . $config['payments_module'] . ".class.php";
	switch( $config['payments_module'] )
	{
		case "dotpay":
			$paymod = new $config['payments_module']( $config['dotpay_id'], $config['dotpay_pin'], $config['dotpay_ip']);
		break;

		case "tpay":
			//$paymod = new $config['payments_module']( $config['tpay_merchant_id'], $config['tpay_secret'], $config['tpay_ip']);
		break;

		case "p24":
			$paymod = new $config['payments_module']( $config['dotpay_id'], $config['dotpay_pin'], $config['dotpay_ip']);
		break;
	}
} else {
	Core\Error::show("ERROR - Module not found" ,  "File " . $app_path . "modules/payments/" . $config['payments_module'] . ".class.php" . " not found");
}
