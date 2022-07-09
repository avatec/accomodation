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
 
session_start();

global $app_path;
$app_path = realpath(dirname(__FILE__)) . '/';

global $app_url;
$app_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/';

require_once $app_path . "include/config/main.php";
require_once $app_path . "classes/functions.php";
require_once $app_path . "classes/cms.class.php";
require_once $app_path . "classes/db.class.php";
require_once $app_path . "classes/language.class.php";
require_once $app_path . "modules/system/system.class.php";
require_once $app_path . "modules/system/users.class.php";

$kernel = new Kernel();
$system = new System();
$user = new User();

global $config;
$config = array_merge($config, $system->configGet());

if( phpversion() <= 5.6) {
	include $app_path . "plugins/Facebook/api/autoload.php";
}
if( phpversion() >= 7 ) {
	include $app_path . "plugins/Facebook/api7/autoload.php";
}

$fb = new Facebook\Facebook([
	'app_id' => $config['facebook_app_id'],
	'app_secret' => $config['facebook_secret'],
	'default_graph_version' => 'v2.10',
]);

$helper = $fb->getRedirectLoginHelper();

try {
	if(!empty($_SESSION['fb_access_token'])) {
		$accessToken = $_SESSION['fb_access_token'];
	} else {
		$accessToken = $helper->getAccessToken();
	}
} catch(Facebook\Exceptions\FacebookResponseException $e) {
	echo 'Graph returned an error: ' . $e->getMessage();
	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
	exit;
}

if (!isset($accessToken)) {
	if ($helper->getError()) {
		header('HTTP/1.0 401 Unauthorized');
		echo "Error: " . $helper->getError() . "\n";
		echo "Error Code: " . $helper->getErrorCode() . "\n";
		echo "Error Reason: " . $helper->getErrorReason() . "\n";
		echo "Error Description: " . $helper->getErrorDescription() . "\n";
	} else {
    	header('HTTP/1.0 400 Bad Request');
		echo 'Bad request';
	}
	exit;
}


if(isset($accessToken)) {
	// The OAuth 2.0 client handler helps us manage access tokens
	$oAuth2Client = $fb->getOAuth2Client();

	// Get the access token metadata from /debug_token
	$tokenMetadata = $oAuth2Client->debugToken($accessToken);
	$_SESSION['fb_access_token'] = (string) $accessToken;

	if(isset($accessToken)) {
		$response = $fb->get('/me?fields=name,email,pass', $accessToken);
		$fbu = json_decode($response->getBody());
		if( $user->addUsingFacebook( ['name' => $fbu->name, 'email' => $fbu->email, 'id' => $fbu->id ]) == true ) {
			Kernel::setMessage("NOTICE" , "Logowanie zakończone pomyślnie");
			Kernel::redirect( $app_url . "panel/account/");
		} else {
			Kernel::setMessage("ERROR" , "Logowanie przez facebook nie udało się");
		}
	}
	Kernel::setMessage("ERROR" , "Logowanie przez facebook nie udało się");
	Kernel::redirect( $app_url . "panel/login/");
}
?>
