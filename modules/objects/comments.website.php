<?php
Kernel::module("objects");
Kernel::schema("panel");

switch( $command ) {
	case "list":
		User::isUserLogged("OWNER");
		Kernel::$Alertify = true;
		Kernel::template("comments/list.smarty");
		$smarty->assign("list" , $c = $comments->getComments( $request->get['object_id'] ));
		$smarty->assign("count" , count($c));
		Kernel::addMeta( "Opinie użytkowników - lista - "  . $config['service_meta_title'] , "", "", false, false);
	break;
	
	case "add":
		Kernel::template("comments/add-edit.smarty");
		Form::$post = $request->post;
		Kernel::addMeta( "Dodawanie komentarza - "  . $config['service_meta_title'] , "", "", false, false);
		if(!empty($request->post['module'])) {
			$result = $comments->add();
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Dodano nową pozycję");
				Kernel::redirect($app_url . "panel/objects/comments/list/?object_id=" . $request->get['object_id'] );
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas tworzenia dodawania pozycji:<br/>" . ObjectsComments::$Error);
			}
		}
	break;
	
	case "edit":
		User::isUserLogged("OWNER");
		Kernel::template("comments/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $comments->get($request->get['id']); } else { Form::$post = $request->post; }
		Kernel::addMeta( "Edycja komentarza - "  . $config['service_meta_title'] , "", "", false, false);
		if(!empty($request->post['module'])) {
			$result = $comments->save( $request->get['id'] );
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Pomyślnie zapisano zmiany");
				Kernel::redirect($app_url . "panel/objects/comments/list/?object_id=" . $request->get['object_id'] );
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas zapisywania zmian:<br/>" . ObjectsComments::$Error);
			}
		}
	break;
	
	case "accept":
		User::isUserLogged("OWNER");
		ObjectsComments::setStatus($request->get['id'], $request->get['object_id'], "ACCEPT");
		Kernel::setMessage("NOTICE" , "Komentarz został zaakceptowany i będzie widoczny na podstronie Twojej oferty");
		Kernel::redirect($app_url . "panel/objects/comments/list/?object_id=" . $request->get['object_id']);
	break;
	
	case "mark-to-delete":
		User::isUserLogged("OWNER");
		ObjectsComments::setStatus($request->get['id'], $request->get['object_id'], "MARK-TO-DELETE");
		Kernel::setMessage("NOTICE" , "Oznaczono komentarz do usunięcia pomyślnie");
		Kernel::redirect($app_url . "panel/objects/comments/list/?object_id=" . $request->get['object_id']);
	break;
	
	case "delete":
		User::isUserLogged("OWNER");
		$result = ObjectsComments::delete( $request->get['id'] );
		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto pozycję");
			Kernel::redirect($app_url . "panel/objects/comments/list/?object_id=" . $request->get['object_id'] );
		} else {
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas usuwania pozycji. Najprawdopodobniej wybrana pozycja nie istnieje<br/>" . ObjectsComments::$Error);
		}
	break;
}