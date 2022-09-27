<?php
Kernel::access("objects;");
Kernel::module("objects");

switch( $command ) {
	
	case "list":
		Kernel::$ModuleName = "Przeglądanie - miasta";
		Kernel::template("cities/list.smarty");
		$smarty->assign("list" , $cities->get());
	break;
	
	case "add":
		Kernel::$CkEditor = true;
		Kernel::$ModuleName = "Dodawanie nowego miasta";
		Kernel::template("cities/add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			if( $cities->add() == true ) {
				Kernel::redirect($app_url . "admin/objects/cities/list/");
			}
		}
	break;
	
	case "edit":
		Kernel::$CkEditor = true;
		Kernel::$ModuleName = "Edycja miasta";
		Kernel::template("cities/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $cities->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if( $cities->save( $request->get['id'] ) == true ) {
				Kernel::redirect($app_url . "admin/objects/cities/list/");
			}
		}
	break;
	
	case "delete":
		$cities->delete( $request->get['id'] );
		Kernel::redirect($app_url . "admin/objects/cities/list/");
	break;
}