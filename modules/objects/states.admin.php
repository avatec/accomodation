<?php
Kernel::access("objects;");
Kernel::module("objects");

switch( $command ) {
	
	case "list":
		Kernel::$ModuleName = "Przeglądanie - województwa";
		Kernel::template("states/list.smarty");
		$smarty->assign("list" , $states->get());

	break;
	
	case "add":
		Kernel::$ModuleName = "Dodawanie nowego województwa";
		Kernel::template("states/add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			if( $states->add() == true ) {
				Kernel::redirect($app_url . "admin/objects/states/list/");
			}
		}
	break;
	
	case "edit":
		Kernel::$ModuleName = "Edycja województwa";
		Kernel::template("states/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $states->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if( $states->save( $request->get['id'] ) == true ) {
				Kernel::redirect($app_url . "admin/objects/states/list/");
			}
		}
	break;
	
	case "delete":
		$states->delete( $request->get['id'] );
		Kernel::redirect($app_url . "admin/objects/states/list/");
	break;
}	


?>