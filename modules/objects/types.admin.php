<?php
Kernel::access("objects;");
Kernel::module("objects");

switch( $command ) {
	
	case "list":
		Kernel::$ModuleName = "Przeglądanie - typy obiektów";
		Kernel::template("types/list.smarty");
		$smarty->assign("list" , $types->get());

	break;
	
	case "add":
		Kernel::$ModuleName = "Dodawanie nowego typu obiektu";
		Kernel::template("types/add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			if( $types->add() == true ) {
				Kernel::redirect($app_url . "admin/objects/types/list/");
			}
		}
	break;
	
	case "edit":
		Kernel::$ModuleName = "Edycja typu obiektu";
		Kernel::template("types/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $types->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if( $types->save( $request->get['id'] ) == true ) {
				Kernel::redirect($app_url . "admin/objects/types/list/");
			}
		}
	break;
	
	case "delete":
		$types->delete( $request->get['id'] );
		Kernel::redirect($app_url . "admin/objects/types/list/");
	break;
}