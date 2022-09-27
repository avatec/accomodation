<?php
Kernel::access("partner;");
Kernel::module("partner");

switch( $command ) {
	
	case "list":
		Kernel::$ModuleName = "Partnerzy";
		Kernel::template("list.smarty");
		$smarty->assign("list" , $partner->get());
	break;
	
	case "add":
		Kernel::$ModuleName = "Dodawanie nowego partnera";
		Kernel::template("add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			if( $partner->add() == true ) {
				Kernel::redirect($app_url . "admin/partner/list/");
			}
		}
	break;
	
	case "edit":
		Kernel::$ModuleName = "Edycja partnera";
		Kernel::template("add-edit.smarty");
		if(empty($request->post)) { Form::$post = $partner->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if( $partner->save( $request->get['id'] ) == true ) {
				Kernel::redirect($app_url . "admin/partner/list/");
			}
		}
	break;
	
	case "delete":
		$partner->delete( $request->get['id'], $request->get['file'] );
		Kernel::redirect($app_url . "admin/partner/list/");
	break;
}	

?>