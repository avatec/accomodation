<?php
Kernel::module("system");
Kernel::schema("panel");

switch( $command ) {
	case "objects":
		User::isUserLogged("OWNER");
		Kernel::redirect($app_url . "panel/objects/list/");
	break;

	case "account":
		User::isUserLogged(['OWNER','USER']);
		Kernel::template("users/account.smarty");
		Kernel::setJs("register.js" , "system");
		if(empty(Form::$post)) { 
			Form::$post = User::$user; 
			Form::$post['rules'] = json_decode( Form::$post['rules'], true );
		}

		Kernel::addMeta(
			'Panel klienta - moje konto',
			'Panel klienta - moje konto',
			'',
			false, false
		);
		
		if(!empty($request->post['module'])) {
			if(!empty($request->post['delete_account'])) {
				// Całkowite usunięcie konta
				
				$id = $request->post['uid'];
				$name = User::$user['name'];
				$email = User::$user['email'];
				
				$objects->delete_by_uid( $id );
				$user->delete( $id );
				
				$text = str_replace("[name]" , $name , Email::getByName('user-account-delete'));
				
				Mail::$address 	= $email;
				Mail::$name 	= $name;
				Mail::$subject 	= "Potwierdzenie usunięcia konta";
				Mail::$text 	= $text .  
				"Wiadomość wysłana dnia " . date('Y-m-d') . " o godz. " . date('H:i:s') . '<br/>' .
				"z adresu IP: " . Kernel::getIp() . '<br/>';
				
				$Result = Mail::send();
				if($Result !== true) {
					Kernel::setMessage("ERROR" , Mail::$error);
				}
				
				Kernel::redirect( $app_url . "panel/logout");
				
				// Usunięcie wszystkich zależności konta
				// specialorder, users
				// Usunięcie konta
				// Wysłanie potwierdzenia
				
			} else {
				$result = $user->saveProfile();
				if($result == true) {
					Kernel::redirect($app_url . "panel/account");
				}
			}
		}
	break;

	case "change-password":
		User::isUserLogged(['OWNER','USER']);
		if(User::$user['id'] <= 0) {
			Kernel::redirect($app_url . "panel/login/");
		}

		Kernel::template("users/change-password.smarty");
		if(!empty($request->post['module'])) {
			$result = $user->userChangePassword();
			if($result == true) {
				Kernel::redirect($app_url . "panel/account/");
			}
		}

		Kernel::addMeta(
			'Zmiana hasła do konta' . (isset($config['service_meta_title']) ? ' - ' . $config['service_meta_title'] : ''),
			null,
			null,
			'noindex',
			'nofollow'
		);
	break;

	case "login":
		if(User::$user['id'] > 0) {
			Kernel::redirect($app_url . "panel/account/");
		}

		if(!empty($config['facebook_app_id'])) {
			if( phpversion() <= 5.6) {
				include $app_path . "plugins/Facebook/api/autoload.php";
			}
			if( phpversion() >= 7 ) {
				include $app_path . "plugins/Facebook/api7/autoload.php";
			}

			$fb = new Facebook\Facebook([
				'app_id' => $config['facebook_app_id'],
				'app_secret' => $config['facebook_secret'],
				'default_graph_version' => 'v2.6',
			]);

			$helper = $fb->getRedirectLoginHelper();
			$permissions = ['public_profile','email'];
			$fb_login_url = $helper->getLoginUrl( $app_url . 'oauth.php' , $permissions);
			$smarty->assign("fb_login_url" , $fb_login_url);
		}

		Kernel::addMeta(
			'Panel klienta - logowanie',
			'Panel klienta - logowanie',
			'',
			true, true
		);

		Kernel::template("users/login.smarty");
		if(!empty($request->post['module'])) {
			$result = $user->userLogin();
			if($result == true) {
				Kernel::redirect($app_url . "panel/objects/");
			}
		}
	break;

	case "logout":
		Kernel::template("users/logout.smarty");

		Kernel::addMeta(
			'Panel klienta - zostałeś(aś) wylogowany(a)',
			'Panel klienta - zostałeś(aś) wylogowany(a)',
			'',
			true, true
		);

		if(User::$user['id'] > 0) {
			$user->userLogout();
			Kernel::template("users/logout.smarty");
		} else {
			Kernel::redirect($app_url . "panel/login/");
		}


	break;

	case "password-reset":
		Kernel::template("users/password-reset.smarty");
		Kernel::addMeta(
			'Resetowanie hasła do konta' . (isset($config['service_meta_title']) ? ' - ' . $config['service_meta_title'] : ''),
			null,
			null,
			'noindex',
			'nofollow'
		);

		if(!empty($request->post['module'])) {
			$result = $user->generateNewPassword( $request->post['email'] );
			if($result == true) {
				Kernel::redirect($app_url . "panel/password-reset-finish/");
			}
		}
	break;

	case "password-reset-finish":
		Kernel::template("users/password-reset-finish.smarty");
		Kernel::addMeta(
			'Hasło zostało zmienione' . (isset($config['service_meta_title']) ? ' - ' . $config['service_meta_title'] : ''),
			null,
			null,
			'noindex',
			'nofollow'
		);

	break;

	case "register":
		Kernel::template("users/register.smarty");
		Kernel::setJs("register.js" , "system");

		Kernel::addMeta(
			'Panel klienta - rejestracja nowego konta',
			'Panel klienta - rejestracja nowego konta',
			'',
			true, true
		);

		if(!empty($request->post['module'])) {
			$result = $user->add(false);
			if($result == true) {
				Kernel::redirect($app_url . "panel/register-finish/");
			}
		}
	break;

	case "register-finish":
		Kernel::template("users/register-finish.smarty");

		Kernel::addMeta(
			'Panel klienta - rejestracja nowego konta',
			'Panel klienta - rejestracja nowego konta',
			'',
			true, true
		);
	break;

	case "activate":
		Kernel::template("users/activate.smarty");
		Kernel::addMeta(
			'Aktywacja konta' . (isset($config['service_meta_title']) ? ' - ' . $config['service_meta_title'] : ''),
			null,
			null,
			'noindex',
			'nofollow'
		);
		$smarty->assign("result" , $user->activate( $id, true ));
	break;
	
	case "booking":
		Kernel::module("booking");
		Kernel::template("panel/list.smarty");
		Kernel::addMeta(
			'Rezerwacje Twoich obiektów' . (isset($config['service_meta_title']) ? ' - ' . $config['service_meta_title'] : ''),
			null,
			null,
			'noindex',
			'nofollow'
		);
		
		$smarty->assign("list" , $booking->getOwners());
	break;
	
	case "settlement":
		Kernel::module("booking");
		Kernel::setCss("jquery.datetimepicker.css" , null);
		Kernel::setJs("jquery.datetimepicker.min.js" , null);
		Kernel::template("panel/settlement.smarty");
		Kernel::addMeta(
			'Rozliczenia wpłaconych zaliczek ' . (isset($config['service_meta_title']) ? ' - ' . $config['service_meta_title'] : ''),
			null,
			null,
			'noindex',
			'nofollow'
		);
		
		$result = $booking->getByUser( User::$user['id'] );
		$smarty->assign("list" , $result['list']);
		$smarty->assign("data" , $result['data']);
	break;
}