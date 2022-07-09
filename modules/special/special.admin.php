<?php
Kernel::access("special;");
Kernel::module("special");

switch( $command ) {
	
	case "list":
		Kernel::$ModuleName = "Przeglądanie - oferty specjalne";
		Kernel::template("special/list.smarty");
		$smarty->assign("list" , $special->get());
	break;
	
	case "add":
		Kernel::$CkEditor = true;
		Kernel::$ModuleName = "Dodawanie nowego typu obiektu";
		Kernel::template("special/add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			if( $special->add() == true ) {
				Kernel::redirect($app_url . "admin/special/list/");
			}
		}
	break;
	
	case "edit":
		Kernel::$CkEditor = true;
		Kernel::$ModuleName = "Edycja typu obiektu";
		Kernel::template("special/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $special->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if( $special->save( $request->get['id'] ) == true ) {
				Kernel::redirect($app_url . "admin/special/list/");
			}
		}
	break;
	
	case "delete":
		$special->delete( $request->get['id'] );
		Kernel::redirect($app_url . "admin/special/list/");
	break;
	
	case "object-list":
		Kernel::$ModuleName = "Obiekty przypisane do ofert specjalnych";
		Kernel::$CheckBox = true;
		Kernel::template("objects/list.smarty");
		
		$smarty->assign("list" , $special->getOrdered( $request->get['object_id'] ));
		
		if(!empty($request->post['module'])) {
			$special->updateOrdered( $request->post['object_id'], $request->post['special_id']);
			Kernel::redirect($app_url . "admin/special/object-add/?object_id=" . $request->post['object_id']);
		}
	break;
	
	case "object-edit":
		Kernel::$ModuleName = "Edycja obiektów > oferty specjalne";
		Kernel::$CheckBox = true;
		Kernel::template("objects/add-edit.smarty");
		
		$smarty->assign("list" , $special->getOrdered( $request->get['object_id'] ));
		if(!empty($request->get['id'])) {
			Form::$post = $special->getOrderedSingle( $request->get['id'] );
		}
		
		if(!empty($request->post['module'])) {
			$special->updateOrdered( $request->post['object_id'], $request->post['special_id']);
			Kernel::redirect($app_url . "admin/special/object-list/?object_id=" . $request->post['object_id']);
		}
	break;
	
	case "object-delete":
		$special->deleteObject( $request->get['id'], $request->get['object_id'] );
		Kernel::redirect($app_url . "admin/special/object-list/?object_id=" . $request->get['object_id']);
	break;
}