<?php
Kernel::access("text;");
Kernel::module("system");

switch($command) {
	case "list":
		Kernel::$ModuleName = "Zarządzanie treściami statycznymi";
		Kernel::template("text/list.smarty");
		
		$smarty->assign("list" , $text->get());
	break;
	
	case "add":
		Kernel::$CkEditor = true;
		Kernel::$ModuleName = "Tworzenie nowej treści statycznej";
		Kernel::template("text/add-edit.smarty");
		
		if(!empty($request->post['module'])) {
			$result = $text->add();
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Utworzono nową treść statyczną");
				Kernel::redirect($app_url . "admin/system/text/list/");
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas tworzenia treści statycznej:<br/>" . Db::error());
			}
		}
	break;
	
	case "edit":
		Kernel::$CkEditor = true;
		Kernel::$ModuleName = "Edycja treści statycznej";
		Kernel::template("text/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $text->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			$result = $text->save( $request->get['id'] );
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Pomyślnie zapisano zmiany");
				Kernel::redirect($app_url . "admin/system/text/list/");
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas zapisywania zmian treści statycznej:<br/>" . Db::error());
			}
		}
	break;
	
	case "delete":
		$result = $text->delete( $request->get['id'] );
		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto treść statyczną");
		} else {
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas usuwania treści statycznej. Najprawdopodobniej wybrana pozycja nie istnieje<br/>" . Db::error());
		}
		Kernel::redirect($app_url . "admin/system/text/list/");
	break;
}