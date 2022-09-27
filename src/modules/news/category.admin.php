<?php
Kernel::access("news;");
Kernel::module("news");

Kernel::$ModuleName = "Kategorie aktualności";

switch( $command ) {
	case "list":
		Kernel::template("category/list.smarty");
		$smarty->assign("list" , $NewsCategory->get());

	break;
	
	case "add":
		Kernel::$CkEditor = true;
		Kernel::template("category/add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			if( $NewsCategory->add() == true ) {
				Kernel::redirect($app_url . "admin/news/category/list/");
			}
		}
	break;
	
	case "edit":
		Kernel::$CkEditor = true;
		Kernel::template("category/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $NewsCategory->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if( $NewsCategory->save( $request->get['id'] ) == true ) {
				Kernel::redirect($app_url . "admin/news/category/list/");
			}
		}
	break;
	
	case "delete":
		$NewsCategory->delete( $request->get['id'] );
		Kernel::redirect($app_url . "admin/news/category/list/");
	break;
}