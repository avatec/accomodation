<?php
Kernel::module("objects");
Kernel::schema("panel");

switch( $command ) {
	case "list":
		User::isUserLogged("OWNER");
		Kernel::$Alertify = true;
		Kernel::template("photosroom/objects/list.smarty");
		Kernel::setJs("https://code.jquery.com/ui/1.11.4/jquery-ui.min.js", true);
		Kernel::setJs("draggable_priority2.js" , "objects");
		Kernel::setJs("dropzone.js");
		Kernel::setJs("photosroom/upload.js", "objects");
		Kernel::setCss("photo-list.css" , "objects");
		Kernel::setCss("prettyPhoto.css" , null);
		Kernel::setJs("jquery.prettyPhoto.js" , null);
		
		$smarty->assign("list" , $photosroom->getByRoom($request->get['room_id'], false));
		
		Kernel::addMeta( "Zdjęcia obiektu - "  . $config['service_meta_title'] , "", "", false, false);
	break;
	
	case "make-main":
		User::isUserLogged("OWNER");
		$photosroom->makeMain($request->get['id']);
		Kernel::redirect($app_url . "panel/rooms/photos/list/?room_id=" . $request->get['room_id'] . "&object_id=" . $request->get['object_id']);
	break;
	
	case "delete":
		User::isUserLogged("OWNER");
		if(empty($request->get['room_id'])) {
			trigger_error("photos.website.php :: param room_id is missing");
		}
		if(empty($request->get['file'])) {
			trigger_error("photos.website.php :: param file is missing");
		}
		if(empty($request->get['id'])) {
			trigger_error("photos.website.php :: param id is missing");
		}
		
		$Result = ObjectsPhotosRoom::delete($request->get['id'], $request->get['file']);
		if($Result == true) {
			Kernel::redirect($app_url . "panel/rooms/photos/list/?room_id=" . $request->get['room_id'] . "&object_id=" . $request->get['object_id']);
		}
	break;
	
	case "upload":
		User::isUserLogged("OWNER");		
		if(!empty($_FILES['file'])) {
			$Result = $photosroom->upload( true );
			//file_put_contents($app_path . "logs/objects_photos_upload.log" , "result: " . var_dump($Result, true) . PHP_EOL, FILE_APPEND);
			if($Result == false) {
				file_put_contents($app_path . "logs/objects_photos_upload.log" , "ERROR: " . implode(PHP_EOL, ObjectsPhotosRoom::$Error), FILE_APPEND);
			} else {
				//file_put_contents($app_path . "logs/objects_photos_upload.log" , "SUCCESS: File " . $Result . " was uploaded" . PHP_EOL, FILE_APPEND);
				die("OK");
			}
		}

		//file_put_contents($app_path . "logs/objects_photos_upload.log" , "RESULT: " . print_r($_FILES, true) . PHP_EOL, FILE_APPEND);
		exit;
	break;
}
?>