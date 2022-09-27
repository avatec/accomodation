<?php
Kernel::access("objects;");
Kernel::module("objects");

switch( $command ) {
	
	case "list":
		Kernel::$ModuleName = "Przeglądanie - lokalizacje";
		Kernel::template("location/list.smarty");
		$smarty->assign("list" , $location->get());
	break;
	
	case "add":
		Kernel::$ModuleName = "Dodawanie nowej lokalizacji";
		Kernel::template("location/add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			if( $location->add() == true ) {
				Kernel::redirect($app_url . "admin/objects/location/list/");
			}
		}
	break;
	
	case "edit":
		Kernel::$ModuleName = "Edycja lokalizacji";
		Kernel::template("location/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $location->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if( $location->save( $request->get['id'] ) == true ) {
				Kernel::redirect($app_url . "admin/objects/location/list/");
			}
		}
	break;
	
	case "delete":
		$location->delete( $request->get['id'] );
		Kernel::redirect($app_url . "admin/objects/location/list/");
	break;
}	


?>