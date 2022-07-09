<?php
Kernel::access("objects;");
Kernel::module("objects");

switch( $command ) {
	case "list":
		Kernel::$ModuleName = "Przeglądanie - kraje";
		Kernel::template("countrys/list.smarty");
		$smarty->assign("list" , $country->get());

	break;
	
	case "add":
		Kernel::$ModuleName = "Dodawanie nowego kraju";
		Kernel::template("countrys/add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			if( $country->add() == true ) {
				Kernel::redirect($app_url . "admin/objects/country/list/");
			}
		}
	break;
	
	case "edit":
		Kernel::$ModuleName = "Edycja województwa";
		Kernel::template("countrys/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $country->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if( $country->save( $request->get['id'] ) == true ) {
				Kernel::redirect($app_url . "admin/objects/country/list/");
			}
		}
	break;
	
	case "delete":
		$country->delete( $request->get['id'] );
		Kernel::redirect($app_url . "admin/objects/country/list/");
	break;
}