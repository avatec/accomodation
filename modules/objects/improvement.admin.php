<?php
Kernel::access("objects;");
Kernel::module("objects");

switch( $command ) {
	
	case "list":
		Kernel::$ModuleName = "Przeglądanie - udogodnienia";
		Kernel::template("improvement/list.smarty");
		$smarty->assign("list" , $improvement->get(null, true));

	break;
	
	case "add":
		Kernel::$ModuleName = "Dodawanie nowego udogodnienia";
		Kernel::template("improvement/add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			if( $improvement->add() == true ) {
				Kernel::redirect($app_url . "admin/objects/improvement/list/");
			}
		}
	break;
	
	case "edit":
		Kernel::$ModuleName = "Edycja typu obiektu";
		Kernel::template("improvement/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $improvement->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if( $improvement->save( $request->get['id'] ) == true ) {
				Kernel::redirect($app_url . "admin/objects/improvement/list/");
			}
		}
	break;
	
	case "delete":
		$improvement->delete( $request->get['id'] );
		Kernel::redirect($app_url . "admin/objects/improvement/list/");
	break;
}	


?>