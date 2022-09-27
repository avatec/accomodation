<?php
Kernel::access("objects;");
Kernel::module("objects");

switch( $command ) {
	
	case "list":
		if(!empty($request->get['uid'])) {
			Kernel::$ModuleName = "Obiekty użytkownika: <u>" . User::getField('login' , $request->get['uid']) . "</u>";
		} else {
			Kernel::$ModuleName = "Przeglądanie - dodane obiekty";	
		}
		
		Kernel::template("objects/list.smarty");
		$smarty->assign("list" , $objects->search(null, true));
	break;
	
	case "add":
		Kernel::$GoogleMaps = true;
		Kernel::$CheckBox = true;
		Kernel::$CkEditor = true;
		Kernel::$ModuleName = "Dodawanie nowego obiektu";
		Kernel::setJs("admin/map.js" , "objects");
		//Kernel::setJs("add-edit.js", "objects");
		Kernel::template("objects/add-edit.smarty");
		Form::$post = $request->post;
		$smarty->assign("improvement" , $improvement->get());
		$smarty->assign("distance" , $distance->get());
		
		if(!empty($request->post['module'])) {
			if( $objects->add() == true ) {
				Kernel::redirect($app_url . "admin/objects/list/");
			}
		}
	break;
	
	case "edit":
		Kernel::$GoogleMaps = true;
		Kernel::$CheckBox = true;
		Kernel::$CkEditor = true;
		Kernel::$ModuleName = "Edycja obiektu";
		Kernel::setJs("admin/map.js" , "objects");
		//Kernel::setJs("add-edit.js", "objects");
		Kernel::template("objects/add-edit.smarty");
		
		$smarty->assign("improvement" , $improvement->get());
		$smarty->assign("distance" , $distance->get());
		if(empty($request->post)) { Form::$post = $objects->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if( $objects->save( $request->get['id'], true ) == true ) {
				Kernel::redirect($app_url . "admin/objects/list/");
			}
		}
	break;
	
	case "delete":
		$objects->delete( $request->get['id'] );
		Kernel::redirect($app_url . "admin/objects/list/");
	break;
	
	case "set-status":
		$objects->setStatus( $request->get['id'], $request->get['status'] );
		Kernel::redirect($app_url . "admin/objects/list/#row-" . $request->get['row']);
	break;
}	


?>