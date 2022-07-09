<?php
Kernel::access("email;");
Kernel::module("system");

switch($command) {
	case "list":
		Kernel::$ModuleName = "Zarządzanie treściami wiadomości e-mail";
		Kernel::template("email/list.smarty");

		$smarty->assign("list" , $emails->get());
	break;

	case "add":
		Kernel::$CkEditor = true;
		Kernel::$ModuleName = "Tworzenie nowej treści wiadomości e-mail";
		Kernel::template("email/add-edit.smarty");

		if(!empty($request->post['module'])) {
			$result = $emails->add();
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Utworzono nową treść statyczną");
				Kernel::redirect($app_url . "admin/system/emails/list/");
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas tworzenia treści wiadomości e-mail:<br/>" . Db::error());
			}
		}
	break;

	case "edit":
		Kernel::$CkEditor = true;
		Kernel::$ModuleName = "Edycja treści wiadomości e-mail";
		Kernel::template("email/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $emails->get($request->get['id']); } else { Form::$post = $request->post; }

		if(!empty($request->post['module'])) {
			$result = $emails->save( $request->get['id'] );
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Pomyślnie zapisano zmiany");
				Kernel::redirect($app_url . "admin/system/emails/list/");
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas zapisywania zmian treści wiadomości e-mail:<br/>" . Db::error());
			}
		}
	break;

	case "delete":
		$result = $emails->delete( $request->get['id'] );
		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto treść statyczną");
		} else {
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas usuwania treści wiadomości e-mail. Najprawdopodobniej wybrana pozycja nie istnieje<br/>" . Db::error());
		}
		Kernel::redirect($app_url . "admin/system/emails/list/");
	break;
}
?>
