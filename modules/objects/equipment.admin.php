<?php
Kernel::access("objects;");
Kernel::module("objects");

switch( $command ) {
	
	case "list":
		Kernel::$ModuleName = "Przeglądanie - wyposażenie";
		Kernel::template("equipment/list.smarty");
		$smarty->assign("list" , $equipment->get(null, true));

	break;
	
	case "add":
		Kernel::$ModuleName = "Dodawanie nowego wyposażenia";
		Kernel::template("equipment/add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			if( $equipment->add() == true ) {
				Kernel::redirect($app_url . "admin/objects/equipment/list/");
			}
		}
	break;
	
	case "edit":
		Kernel::$ModuleName = "Edycja typu obiektu";
		Kernel::template("equipment/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $equipment->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if( $equipment->save( $request->get['id'] ) == true ) {
				Kernel::redirect($app_url . "admin/objects/equipment/list/");
			}
		}
	break;
	
	case "delete":
		$equipment->delete( $request->get['id'] );
		Kernel::redirect($app_url . "admin/objects/equipment/list/");
	break;
}	


?>