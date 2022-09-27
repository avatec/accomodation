<?php
	Kernel::access("business;");
	Kernel::module("business");
	
	switch( $command ) {
		case "list":
			Kernel::$ModuleName = "Przeglądanie not korygujących";
			Kernel::template("notes/list.smarty");
			$smarty->assign("list" , $b_notes->get());
		break;
		
		case "add":
			Kernel::$ModuleName = "Tworzenie nowego kontrahenta";
			Kernel::template("notes/add-edit.smarty");
			Form::$post = $request->post;
			
			if(!empty($request->post['module'])) {
				if( $b_notes->add() == true ) {
					Kernel::redirect($app_url . "admin/business/notes/list/");
				}
			}
		break;
		
		case "edit":
			Kernel::$ModuleName = "Edycja kontrahenta";
			Kernel::template("notes/add-edit.smarty");
			if(empty($request->post)) { Form::$post = $b_notes->get($request->get['id']); } else { Form::$post = $request->post; }
			
			if(!empty($request->post['module'])) {
				if( $b_notes->save( $request->get['id'] ) == true ) {
					Kernel::redirect($app_url . "admin/business/notes/list/");
				}
			}
		break;
		
		case "delete":
			$b_notes->delete( $request->get['id'] );
			Kernel::redirect($app_url . "admin/business/notes/list/");
		break;
		
		case "download":
			$data = $b_notes->get( $request->get['id'] );
			NotesPDF::generate( $data );
		break;
	}
	
?>