<?php
Kernel::access("slider;");
Kernel::module("slider");

switch( $command ) {
	
	case "list":
		Kernel::$ModuleName = "Slider";
		Kernel::template("list.smarty");
		$smarty->assign("list" , $slider->get());

	break;
	
	case "add":
		Kernel::setJs("add-edit.js" , "slider");
		Kernel::$ModuleName = "Dodawanie nowego slajdu";
		Kernel::template("add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			if( $slider->add() == true ) {
				Kernel::redirect($app_url . "admin/slider/list/");
			}
		}
	break;
	
	case "edit":
		Kernel::setJs("add-edit.js" , "slider");
		Kernel::$ModuleName = "Edycja zdjęcia";
		Kernel::template("add-edit.smarty");
		if(empty($request->post)) { Form::$post = $slider->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if ( $slider->save( $request->get['id'] ) == true ) {
				Kernel::redirect($app_url . "admin/slider/list/");
			}
		}
	break;
	
	case "delete":
		$slider->delete( $request->get['id'], $request->get['file'] );
		Kernel::redirect($app_url . "admin/slider/list/");
	break;
}