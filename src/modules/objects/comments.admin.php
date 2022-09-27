<?php
Kernel::access("objects;");
Kernel::module("objects");

switch( $command ) {
	
	case "list":
		Kernel::$ModuleName = "Przeglądanie - komentarze";
		Kernel::template("comments/list.smarty");
		$smarty->assign("list" , $comments->get());
	break;
	
	/**
	case "add":
		Kernel::$ModuleName = "Dodawanie nowego komentarza";
		Kernel::template("comments/add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			$result = $comments->add();
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Dodano nową pozycję");
				Kernel::redirect($app_url . "admin/objects/comments/list/");
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas tworzenia dodawania pozycji:<br/>" . ObjectsStates::$Error);
			}
		}
	break;
	**/
	
	case "edit":
		Kernel::$ModuleName = "Edycja komentarza";
		Kernel::template("comments/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $comments->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if( $comments->save( $request->get['id'], true ) == true ) {
				Kernel::redirect($app_url . "admin/objects/comments/list/");
			}
		}
	break;
	
	case "delete":
		ObjectsComments::delete( $request->get['id'], $request->get['object_id'] );
		Kernel::redirect($app_url . "admin/objects/comments/list/");
	break;
	
	case "accept":
		if( ObjectsComments::setStatus( $request->get['id'], $request->get['object_id'] , "ACTIVE" ) == true) {
			Kernel::setMessage("NOTICE" , "Komentarz został zaakceptowany i będzie widoczny na podstronie oferty");
		}
		Kernel::redirect($app_url . "admin/objects/comments/list/");
	break;
	
	case "mark-to-delete":
		ObjectsComments::setStatus($request->get['id'], $request->get['object_id'], "MARK-TO-DELETE");
		Kernel::setMessage("NOTICE" , "Oznaczono komentarz do usunięcia pomyślnie");
		Kernel::redirect($app_url . "admin/objects/comments/list/");
	break;
}