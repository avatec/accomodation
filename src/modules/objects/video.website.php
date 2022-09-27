<?php
Kernel::module("objects");
Kernel::schema("panel");

switch( $command ) {
	case "list":
		User::isUserLogged("OWNER");
		Kernel::$Alertify = true;
		Kernel::template("video/list.smarty");
		Kernel::setCss("video-list.css" , "objects");
		$smarty->assign("list" , $video->getByObject($request->get['object_id']));
		if(!empty($request->post['module'])) {
			if($video->add() == true) {
				Kernel::redirect( $app_url . "panel/objects/video/list/?object_id=" . $request->get['object_id'] );
			}
		}
		
		Kernel::addMeta( "Zdjęcia obiektu - "  . $config['service_meta_title'] , "", "", false, false);
	break;
	
	case "delete":
		User::isUserLogged("OWNER");
		if(empty($request->get['id'])) {
			trigger_error("photos.website.php :: param id is missing");
		}
		
		$Result = ObjectsVideos::delete($request->get['id']);
		if($Result == true) {
			Kernel::redirect($app_url . "panel/objects/video/list/?object_id=" . $request->get['object_id']);
		}
	break;
	
	
}
?>