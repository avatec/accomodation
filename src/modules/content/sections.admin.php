<?php
Kernel::access("content;");
Kernel::module("content");
Kernel::$ModuleName = "Sekcje menu";

switch( $command ) {
	
	case "list":
		Kernel::template("sections/list.smarty");
		$smarty->assign("list" , $sections->get());
	break;
	
	case "add":
		Kernel::template("sections/add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			if( $sections->add() == true ) {
				Kernel::redirect($app_url . "admin/content/sections/list/");
			}
		}
	break;
	
	case "edit":
		Kernel::template("sections/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $sections->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if( $sections->save( $request->get['id'] ) == true ) {
				Kernel::redirect($app_url . "admin/content/sections/list/");
			}
		}
	break;
	
	case "delete":
		$sections->delete( $request->get['id'] );
		Kernel::redirect($app_url . "admin/content/sections/list/");
	break;
}