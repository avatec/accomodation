<?php
Kernel::access("config;");
Kernel::module("system");
Kernel::$ModuleName = "Zarządzanie opcjami wyróżnień";

switch($command) {
	case "list":
		Kernel::template("promotion/list.smarty");
		$smarty->assign("list" , $promotion->get());
	break;
	
	case "add":
		Kernel::template("promotion/add-edit.smarty");
		if(!empty($request->post['module'])) {
			if( $promotion->add() == true ) {
				Kernel::redirect($app_url . "admin/system/promotion/list/");
			}
		}
	break;
	
	case "edit":
		Kernel::template("promotion/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $promotion->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if( $promotion->save( $request->get['id'] ) == true ) {
				Kernel::redirect($app_url . "admin/system/promotion/list/");
			}
		}
	break;
	
	case "delete":
		$promotion->delete( $request->get['id'] );
		Kernel::redirect($app_url . "admin/system/promotion/list/");
	break;
}