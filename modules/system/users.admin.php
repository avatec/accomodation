<?php
Kernel::module("system");
Kernel::access("config;");

switch($command) {
	case "account-for-object":
		if(!empty($request->get['object_id'])) {
			$user->add_quick( $request->get['object_id']);
			Kernel::redirect( $app_url . "admin/objects/list/" . (!empty($request->get['hash']) ? "#" . $request->get['hash'] : ""));
		}
	break;
	
	case "list":
		Kernel::$ModuleName = "Zarządzanie użytkownikami";
		Kernel::template("users/list.smarty");
		$smarty->assign("list" , $user->get());
	break;

	case "add":
		Kernel::$ModuleName = "Dodawanie nowego użytkownika";
		Kernel::template("users/add-edit.smarty");
		Kernel::setJs("generate.js", "system" , true);
		Kernel::setJs("register.js" , "system");

		if(!empty($request->post['module'])) {
			if( $user->add() == true ) {
				Kernel::redirect($app_url . "admin/system/users/list/");
			}
		}
	break;

	case "edit":
		Kernel::$ModuleName = "Edycja użytkownika";
		Kernel::template("users/add-edit.smarty");
		Kernel::setJs("register.js" , "system");

		if(empty($request->post)) { 
			Form::$post = $user->get($request->get['id']); 
		} else { 
			Form::$post = $request->post; 
		}

		if(!empty($request->post['module'])) {
			$result = $user->save( $request->get['id'], true );
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Zapisano zmiany na koncie użytkownika");
				Kernel::redirect($app_url . "admin/system/users/list/");
			} else {
				if(is_array(User::$Error)) {
					Kernel::setMessage("ERROR" , "<h3><b>Formularz zawiera błędy:</b></h3><h5 class='text text-error'>" . implode("<br/>" , User::$Error) . "</h5>");
				} else {
					Kernel::setMessage("ERROR" , "Wystąpił błąd podczas zapisywania zmian na koncie użytkownika:<br/>" . Db::error());
				}
			}
		}
	break;

	case "delete":
		$result = $user->delete( $request->get['id'] );
		Kernel::redirect($app_url . "admin/system/users/list/");
	break;

	case "activate":
		$user->activate( $request->get['id'] );
		Kernel::redirect($app_url . "admin/system/users/list/");
	break;

	case "deactivate":
		$user->deactivate( $request->get['id'] );
		Kernel::redirect($app_url . "admin/system/users/list/");
	break;

	case "change-password":
		Kernel::$ModuleName = "Zmiana hasła użytkownika";
		Kernel::module("system");
		Kernel::template("users/change-password.smarty");
		Kernel::setJs("generate.js", "system" , true);
		if(!empty($request->post['module'])) {
			$Result = $user->adminChangePassword( false );
			if($Result == true) {
				Kernel::setMessage("NOTICE" , "Hasło do wybranego konta zostało zmienione.");
				Kernel::redirect($app_url . "admin/system/users/list/");
			} else {
				if(is_array(User::$Error)) {
					Kernel::setMessage("ERROR" , "<h3><b>Formularz zawiera błędy:</b></h3><h5 class='text text-error'>" . implode("<br/>" , User::$Error) . "</h5>");
				} else {
					Kernel::setMessage("ERROR" , "Wystąpił błąd podczas zmiany hasła:<br/>" . Db::error());
				}
			}
		}
	break;

	case "access":
		Kernel::$ModuleName = "Uprawnienia dostępu do funkcji panelu";
		Kernel::module("system");
		Kernel::template("users/access.smarty");
		if(empty($request->post)) {
			$u = $user->get($request->get['id']);
			$smarty->assign("access_default" , $u['access']);
			$as = explode(";" , $u['access']);
			unset($u);
			$am = Kernel::$admin_menu;

			foreach($am as $k=>$i) {
				foreach($as as $kk=>$ii) {
					if($ii == $k) {
						$u['element'][$k] = $k;
					} else {
						if(empty($u['element'][$k])) {
							$u['element'][$k] = 'false';
						}
					}
				}
			}

			Form::$post = $u;

		} else {
			Form::$post = $request->post;
		}

		if(!empty($request->post['module'])) {
			$Result = $user->adminAccessSave( $request->get['id'] );
			if($Result == true) {
				Kernel::setMessage("NOTICE" , "Zmieniono uprawnienia dostępu dla konta.");
				Kernel::redirect($app_url . "admin/system/users/list/");
			} else {
				if(is_array(User::$Error)) {
					Kernel::setMessage("ERROR" , "<h3><b>Formularz zawiera błędy:</b></h3><h5 class='text text-error'>" . implode("<br/>" , User::$Error) . "</h5>");
				} else {
					Kernel::setMessage("ERROR" , "Wystąpił błąd podczas zmiany uprawnień dla konta:<br/>" . Db::error());
				}
			}
		}
	break;
}