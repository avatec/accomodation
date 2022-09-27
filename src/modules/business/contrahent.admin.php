<?php
	
	if( (strstr( User::$admin['access'], "contrahent;" ) != true) AND ( User::$admin['type'] !== "ADMIN") ) {
		Kernel::setMessage("ERROR" , "Dostęp zabroniony dla Twojego konta");
		Kernel::redirect( $app_url . "admin/start.html" );
	} 
	
	Kernel::module("business");
	
	switch( $command ) {
		case "list":
			Kernel::$ModuleName = "Przeglądanie listy kontrahentów";
			Kernel::template("contrahent/list.smarty");
			$smarty->assign("list" , $b_contrahent->get());

		break;
		
		case "add":
			Kernel::$ModuleName = "Tworzenie nowego kontrahenta";
			Kernel::template("contrahent/add-edit.smarty");
			Form::$post = $request->post;
			
			if(!empty($request->post['module'])) {
				$result = $b_contrahent->add();
				if(!empty($result)) {
					Kernel::setMessage("NOTICE" , "Dodano nowego kontrahenta");
					Kernel::redirect($app_url . "admin/business/contrahent/list/");
				} else {
					Kernel::setMessage("ERROR" , "Wystąpił błąd podczas dodawania nowego kontrahenta:<br/>" . (!empty(BusinessContrahents::$Error) ? implode("<br/>" , BusinessContrahents::$Error) : ""));
				}
				
			}
		break;
		
		case "edit":
			Kernel::$ModuleName = "Edycja kontrahenta";
			Kernel::template("contrahent/add-edit.smarty");
			if(empty($request->post)) { Form::$post = $b_contrahent->get($request->get['id']); } else { Form::$post = $request->post; }
			
			if(!empty($request->post['module'])) {
				$result = $b_contrahent->save( $request->get['id'] );
				if(!empty($result)) {
					Kernel::setMessage("NOTICE" , "Pomyślnie zapisano zmiany");
					Kernel::redirect($app_url . "admin/business/contrahent/list/");
				} else {
					Kernel::setMessage("ERROR" , "Wystąpił błąd podczas zapisywania zmian:<br/>" .  (!empty(BusinessContrahents::$Error) ? implode("<br/>" , BusinessContrahents::$Error) : ""));
				}
			}
		break;
		
		case "delete":
			$result = $b_contrahent->delete( $request->get['id'] );
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Pomyślnie usunięto kontrahenta");
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas usuwania kontrahenta. Najprawdopodobniej wybrana pozycja nie istnieje<br/>" . Db::error());
			}
			Kernel::redirect($app_url . "admin/business/contrahent/list/");
		break;
	}
	
?>