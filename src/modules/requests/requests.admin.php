<?php
use Framework\Kernel, Framework\Form;

Kernel::access("requests;");
Kernel::module("jobs");

switch( $command ) {
	
	case "list":
		Kernel::$ModuleName = "Przeglądanie ofert pracy";
		Kernel::template("list.smarty");
		$smarty->assign("list" , $jobs->get());

	break;
	
	case "add":
		Kernel::$ModuleName = "Dodawanie nowej oferty pracy";
		Kernel::template("add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			$result = $jobs->add();
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Dodano nową ofertę pracy");
				Kernel::redirect($app_url . "admin/jobs/list/");
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas tworzenia dodawania oferty pracy:<br/>" . (isset(Jobs::$Error) ? (implode("<br/>" , Jobs::$Error)) : Db::error()));
			}
		}
	break;
	
	case "edit":
		Kernel::$ModuleName = "Edycja pracownika";
		Kernel::template("add-edit.smarty");
		if(empty($request->post)) { Form::$post = $jobs->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			$result = $jobs->save( $request->get['id'] );
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Pomyślnie zapisano zmiany");
				Kernel::redirect($app_url . "admin/jobs/list/");
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas zapisywania zmian w ofercie pracy:<br/>" . Db::error());
			}
		}
	break;
	
	case "delete":
		$result = $jobs->delete( $request->get['id'] );
		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto ofertę pracy");
			Kernel::redirect($app_url . "admin/jobs/list/");
		} else {
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas usuwania oferty pracy. Najprawdopodobniej wybrana pozycja nie istnieje<br/>" . Db::error());
		}
	break;
}