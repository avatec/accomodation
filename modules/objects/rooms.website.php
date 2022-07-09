<?php
Kernel::module("objects");
Kernel::schema("panel");

switch( $command ) {
	case "list":
		User::isUserLogged("OWNER");
		Kernel::$Alertify = true;
		Kernel::template("rooms/list.smarty");
		$smarty->assign("list" , $rooms->getByObject($request->get['object_id']));
		Kernel::addMeta( "Zarządzanie pokojami obiektu - "  . $config['service_meta_title'] , "", "", false, false);
	break;
	
	case "add":
		User::isUserLogged("OWNER");
		Kernel::$CkEditor = true;
		Kernel::template("rooms/add-edit.smarty");
		Form::$post = $request->post;
		Kernel::addMeta( "Dodawanie pokoi do obiektu - "  . $config['service_meta_title'] , "", "", false, false);
		$smarty->assign("equipment" , $equipment->get());
		if(!empty($request->post['module'])) {
			if($rooms->add() == true) {
				Kernel::redirect( $app_url . "panel/objects/rooms/list/?object_id=" . $request->get['object_id']);
			}
		}
	break;
	
	case "edit":
		User::isUserLogged("OWNER");
		Kernel::$CkEditor = true;
		Kernel::template("rooms/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $rooms->get($request->get['id']); } else { Form::$post = $request->post; }
		Kernel::addMeta( "Edycja pokoju do obiektu - "  . $config['service_meta_title'] , "", "", false, false);
		$smarty->assign("equipment" , $equipment->get());
		
		if(!empty($request->post['module'])) {
			if($rooms->save( $request->get['id'] ) == true) {
				Kernel::redirect( $app_url . "panel/objects/rooms/list/?object_id=" . $request->get['object_id']);
			}
		}
	break;
	
	case "delete":
		User::isUserLogged("OWNER");
		$result = ObjectsRooms::delete( $request->get['id'] );
		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto pozycję");
			Kernel::redirect($app_url . "panel/objects/rooms/list/?object_id=" . $request->get['object_id']);
		} else {
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas usuwania pozycji. Najprawdopodobniej wybrana pozycja nie istnieje<br/>" . implode("<br/>",ObjectsRooms::$Error));
		}
	break;
}
?>