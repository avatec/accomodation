<?php
Kernel::access("objects;");
Kernel::module("objects");
Kernel::$ModuleName = "Filmy wideo";

switch( $command ) {
	case "list":
		Kernel::template("video/list.smarty");
		Kernel::setCss("video-list.css" , "objects");
		$smarty->assign("list" , $video->getByObject($request->get['object_id']));
		if(!empty($request->post['module'])) {
			if($video->add() == true) {
				Kernel::redirect( $app_url . "admin/objects/video/list/?object_id=" . $request->get['object_id'] );
			}
		}
	break;
	
	case "delete":
		if(empty($request->get['id'])) {
			trigger_error("photos.website.php :: param id is missing");
		}
		
		$Result = ObjectsVideos::delete($request->get['id']);
		if($Result == true) {
			Kernel::redirect($app_url . "admin/objects/video/list/?object_id=" . $request->get['object_id']);
		}
	break;
	
	
}
?>