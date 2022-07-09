<?php
Kernel::access('advertising;');
Kernel::module("advertising");
Kernel::$ModuleName = "Reklamy w serwisie";

switch( $command ) {
	case "list":
		Kernel::template("list.smarty");
		$smarty->assign("list" , $ads->get());
	break;

	case "create":
		Kernel::setJs("add-edit.min.js" , "advertising");
		Kernel::template("add-edit.smarty");
		Form::$post = $request->post;
		if(!empty($request->post['module'])) {
			if( $ads->add() == true ) {
				Kernel::redirect($app_url . "admin/advertising/list/");
			}
		}
	break;

	case "edit":
		Kernel::setJs("add-edit.min.js" , "advertising");
		Kernel::template("add-edit.smarty");
		if(empty($request->post)) { Form::$post = $ads->get($request->get['id']); } else { Form::$post = $request->post; }

		if(!empty($request->post['module'])) {
			if( $ads->save($request->get['id']) == true ) {
				Kernel::redirect($app_url . "admin/advertising/list/");
			}
		}
	break;

	case "delete":
		$ads->delete($_GET['id']);
		Kernel::redirect($app_url . "admin/advertising/list/");
	break;
}
