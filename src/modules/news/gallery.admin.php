<?php
Kernel::access('news');
Kernel::module("news");
Kernel::$ModuleName = "Galeria zdjęć do aktualności";

$returnUrl = $app_url . "admin/news/gallery/list/?news_id=" . $request->get['news_id'] . "&category=" . $request->get['category'];

switch( $command ) {
	
	case "list":
		Kernel::template("gallery/list.smarty");
		$smarty->assign("list" , $news_gallery->getUsingNewsId($request->get['news_id']));
		
		if(!empty($request->post['delete'])) {
			$news_gallery->delete_all( $request->post['delete'] );
			Kernel::redirect($returnUrl);
		}

		if(!empty($request->post['save-priority'])) {
			$news_gallery->priorityUpdate();
			Kernel::redirect($returnUrl);
		}
	break;
	
	case "add":
		Kernel::template("gallery/add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			if( $news_gallery->add() == true ) {
				Kernel::redirect($returnUrl);
			}
		}
	break;
	
	case "edit":
		Kernel::template("gallery/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $news_gallery->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if( $news_gallery->save( $request->get['id'] ) == true ) {
				Kernel::redirect($returnUrl);
			}
		}
	break;
	
	case "delete":
		$result = $news_gallery->delete( $request->get['id'], $request->get['file'] );
		Kernel::redirect($returnUrl);
	break;
}	