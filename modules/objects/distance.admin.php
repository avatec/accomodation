<?php
Kernel::access("objects;");
Kernel::module("objects");

switch( $command ) {
	
	case "list":
		Kernel::$ModuleName = "Przeglądanie - odległości od innych obiektów";
		Kernel::template("distance/list.smarty");
		$smarty->assign("list" , $distance->get(null, true));

	break;
	
	case "add":
		Kernel::$ModuleName = "Dodawanie nowego typu obiektu";
		Kernel::template("distance/add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			if( $distance->add() == true ) {
				Kernel::redirect($app_url . "admin/objects/distance/list/");
			}
		}
	break;
	
	case "edit":
		Kernel::$ModuleName = "Edycja typu obiektu";
		Kernel::template("distance/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $distance->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if( $distance->save( $request->get['id'] ) == true ) {
				Kernel::redirect($app_url . "admin/objects/distance/list/");
			}
		}
	break;
	
	case "delete":
		$distance->delete( $request->get['id'] );
		Kernel::redirect($app_url . "admin/objects/distance/list/");
	break;
}