<?php
Kernel::access("objects;");
Kernel::module("objects");

switch( $command ) {
	
	case "list":
		Kernel::$ModuleName = "Przeglądanie - zdjęcia pokoju";
		Kernel::template("photosroom/list.smarty");
		Kernel::setJs("dropzone.js");
		Kernel::setJs("upload_ObjectsPhotosRoom.js");
		Kernel::setJs("https://code.jquery.com/ui/1.11.4/jquery-ui.min.js", true);
		Kernel::setJs("draggable_priority2.js" , "objects");
		Kernel::setCss("photo-list.css" , "objects");
		
		$smarty->assign("list" , $photosroom->getByRoom($request->get['room_id']));
	break;
	
	case "add":
		Kernel::$ModuleName = "Dodawanie nowego zdjęcia";
		Kernel::template("photosroom/add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			$result = $photosroom->upload(true);
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Dodano nową pozycję");
				Kernel::redirect($app_url . "admin/objects/photosroom/list/?room_id=" . $request->get['room_id']);
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas tworzenia dodawania pozycji:<br/>" . ObjectsPhotosRoom::$Error);
			}
		}
	break;
	
	case "edit":
		Kernel::$ModuleName = "Edycja zdjęcia pokoju";
		Kernel::template("photosroom/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $photosroom->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			$result = $photosroom->save( $request->get['id'] );
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Pomyślnie zapisano zmiany");
				Kernel::redirect($app_url . "admin/objects/photosroom/list/?room_id=" . $request->get['room_id']);
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas zapisywania zmian:<br/>" . ObjectsPhotosRoom::$Error);
			}
		}
	break;
	
	case "delete":
		if(empty($request->get['room_id'])) {
			trigger_error("photos.admin.php :: param room_id is missing");
		}
		if(empty($request->get['file'])) {
			trigger_error("photos.admin.php :: param file is missing");
		}
		if(empty($request->get['id'])) {
			trigger_error("photos.admin.php :: param id is missing");
		}
		
		$Result = ObjectsPhotosRoom::delete($request->get['id'], $request->get['file']);
		if($Result == true) {
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto pozycję");
			Kernel::redirect($app_url . "admin/objects/photosroom/list/?room_id=" . $request->get['room_id']);
		} else {
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas usuwania pozycji. Najprawdopodobniej wybrana pozycja nie istnieje<br/>" . ObjectsPhotos::$Error);
		}
	break;
	
	case "make-main":
		$photosroom->makeMain($request->get['id']);
		Kernel::redirect($app_url . "admin/objects/photosroom/list/?room_id=" . $request->get['room_id']);
	break;
}

?>