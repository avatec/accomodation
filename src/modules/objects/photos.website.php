<?php
Kernel::module("objects");
Kernel::schema("panel");

switch( $command ) {
	case "list":
		User::isUserLogged("OWNER");
		Kernel::$Alertify = true;
		Kernel::template("photos/objects/list.smarty");
		Kernel::setJs("https://code.jquery.com/ui/1.11.4/jquery-ui.min.js", true);
		Kernel::setJs("draggable_priority.js" , "objects");
		Kernel::setJs("dropzone.js");
		Kernel::setJs("upload.js");
		Kernel::setCss("photo-list.css" , "objects");
		Kernel::setCss("prettyPhoto.css" , null);
		Kernel::setJs("jquery.prettyPhoto.js" , null);
		
		$smarty->assign("list" , $photos->getByObject($request->get['object_id'], false));
		
		Kernel::addMeta( "Zdjęcia obiektu - "  . $config['service_meta_title'] , "", "", false, false);
	break;
	
	case "make-main":
		User::isUserLogged("OWNER");
		$photos->makeMain($request->get['id']);
		Kernel::redirect($app_url . "panel/objects/photos/list/?object_id=" . $request->get['object_id']);
	break;
	
	case "delete":
		User::isUserLogged("OWNER");
		if(empty($request->get['object_id'])) {
			trigger_error("photos.website.php :: param object_id is missing");
		}
		if(empty($request->get['file'])) {
			trigger_error("photos.website.php :: param file is missing");
		}
		if(empty($request->get['id'])) {
			trigger_error("photos.website.php :: param id is missing");
		}
		
		$Result = ObjectsPhotos::delete($request->get['id'], $request->get['file']);
		if($Result == true) {
			Kernel::redirect($app_url . "panel/objects/photos/list/?object_id=" . $request->get['object_id']);
		}
	break;
	
	case "upload":
		User::isUserLogged("OWNER");		
		if(!empty($_FILES['file'])) {
			$Result = $photos->upload( true );
			//file_put_contents($app_path . "logs/objects_photos_upload.log" , "result: " . var_dump($Result, true) . PHP_EOL, FILE_APPEND);
			if($Result == false) {
				file_put_contents($app_path . "logs/objects_photos_upload.log" , "ERROR: " . implode(PHP_EOL, ObjectsPhotos::$Error), FILE_APPEND);
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