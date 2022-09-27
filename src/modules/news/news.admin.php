<?php
Kernel::access("news;");
Kernel::module("news");
Kernel::$ModuleName = "Aktualności";

if(isset($request->get['category'])) {
	$returnUrl = $app_url . "admin/news/list-by-category/?category=" . $request->get['category'];
}

switch( $command ) {
	
	case "list":
		Kernel::template("news/list.smarty");
		$smarty->assign("list" , $news->get());
	break;
	
	case "list-by-category":
		Kernel::template("news/list.smarty");
		$smarty->assign("list" , $news->getByCategory( $request->get['category'] ));
	break;
	
	case "add":
		Kernel::$CkEditor = true;
		Kernel::template("news/add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			if( $news->add() == true ) {
				Kernel::redirect( $returnUrl );
			}
		}
	break;
	
	case "edit":
		Kernel::$CkEditor = true;
		Kernel::template("news/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $news->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if( $news->save( $request->get['id'] ) == true ) {
				Kernel::redirect( $returnUrl );
			}
		}
	break;
	
	case "delete":
		$news->delete( $request->get['id'] );
		Kernel::redirect( $returnUrl );
	break;
	
	case "set-main":
		$news->set_main( $request->get['id'], $request->get['category'] );
		Kernel::redirect( $returnUrl );
	break;
	
	case "unset-main":
		$news->unset_main( $request->get['id'], $request->get['category'] );
		Kernel::redirect( $returnUrl );
	break;
}	
?>