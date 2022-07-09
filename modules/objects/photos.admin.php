<?php
Kernel::access("objects;");
Kernel::module("objects");

switch( $command ) {
	
	case "list":
		Kernel::$ModuleName = "Przeglądanie - zdjęcia obiektu";
		Kernel::template("photos/list.smarty");
		Kernel::setJs("dropzone.js");
		Kernel::setJs("https://code.jquery.com/ui/1.11.4/jquery-ui.min.js", true);
		Kernel::setJs("draggable_priority.js" , "objects");
		
		//Kernel::setJs("upload_ObjectsPhotos.js");
		Kernel::setCss("photo-list.css" , "objects");
		
		
		$smarty->assign("list" , $photos->getByObject($request->get['object_id']));
	break;
	
	case "add":
		Kernel::$ModuleName = "Dodawanie nowego zdjęcia";
		Kernel::template("photos/add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			$result = $photos->upload(true);
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Dodano nową pozycję");
				Kernel::redirect($app_url . "admin/objects/photos/list/?object_id=" . $request->get['object_id']);
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas tworzenia dodawania pozycji:<br/>" . ObjectsPhotos::$Error);
			}
		}
	break;
	
	case "edit":
		Kernel::$ModuleName = "Edycja zdjęcia obiektu";
		Kernel::template("photos/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $photos->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			$result = $photos->save( $request->get['id'] );
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Pomyślnie zapisano zmiany");
				Kernel::redirect($app_url . "admin/objects/photos/list/?object_id=" . $request->get['object_id']);
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas zapisywania zmian:<br/>" . ObjectsPhotos::$Error);
			}
		}
	break;
	
	case "delete":
		if(empty($request->get['object_id'])) {
			trigger_error("photos.admin.php :: param object_id is missing");
		}
		if(empty($request->get['file'])) {
			trigger_error("photos.admin.php :: param file is missing");
		}
		if(empty($request->get['id'])) {
			trigger_error("photos.admin.php :: param id is missing");
		}
		
		$Result = ObjectsPhotos::delete($request->get['id'], $request->get['file']);
		if($Result == true) {
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto pozycję");
			Kernel::redirect($app_url . "admin/objects/photos/list/?object_id=" . $request->get['object_id']);
		} else {
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas usuwania pozycji. Najprawdopodobniej wybrana pozycja nie istnieje<br/>" . ObjectsPhotos::$Error);
		}
	break;
	
	case "make-main":
		$photos->makeMain($request->get['id']);
		Kernel::redirect($app_url . "admin/objects/photos/list/?object_id=" . $request->get['object_id']);
	break;
}

?>