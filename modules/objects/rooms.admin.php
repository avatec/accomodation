<?php
Kernel::access("objects;");
Kernel::module("objects");

switch( $command ) {
	
	case "list":
		Kernel::$ModuleName = "Przeglądanie - pokoje";
		Kernel::template("rooms/list.smarty");
		$smarty->assign("list" , $rooms->getByObject( $request->get['object_id'] ));

	break;
	
	case "add":
		Kernel::$CheckBox = true;
		Kernel::$CkEditor = true;
		Kernel::$ModuleName = "Dodawanie nowego pokoju do obiektu";
		Kernel::template("rooms/add-edit.smarty");
		
		$smarty->assign("equipment" , $equipment->get());
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			$result = $rooms->add();
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Dodano nową pozycję");
				Kernel::redirect($app_url . "admin/objects/rooms/list/?object_id=" . $request->get['object_id']);
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas tworzenia dodawania pozycji:<br/>" . implode("<br/>",ObjectsRooms::$Error));
			}
		}
	break;
	
	case "edit":
		Kernel::$CheckBox = true;
		Kernel::$CkEditor = true;
		Kernel::$ModuleName = "Edycja pokoju";
		Kernel::template("rooms/add-edit.smarty");
		
		$smarty->assign("equipment" , $equipment->get());
		if(empty($request->post)) { Form::$post = $rooms->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			$result = $rooms->save( $request->get['id'] );
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Pomyślnie zapisano zmiany");
				Kernel::redirect($app_url . "admin/objects/rooms/list/?object_id=" . $request->get['object_id']);
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas zapisywania zmian:<br/>" . implode("<br/>",ObjectsRooms::$Error));
			}
		}
	break;
	
	case "delete":
		$result = ObjectsRooms::delete( $request->get['id'] );
		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto pozycję");
			Kernel::redirect($app_url . "admin/objects/rooms/list/?object_id=" . $request->get['object_id']);
		} else {
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas usuwania pozycji. Najprawdopodobniej wybrana pozycja nie istnieje<br/>" . implode("<br/>",ObjectsRooms::$Error));
		}
	break;
}	


?>