<?php
Kernel::module("objects");
Kernel::schema("panel");

switch( $command ) {
	case "list":
		User::isUserLogged("OWNER");
		Kernel::$Alertify = true;
		Kernel::template("prices/list.smarty");
		Kernel::setJs("filter.js" , "objects");
		$smarty->assign("list" , $prices->get());
		$smarty->assign("filter" , $prices->filter( $request->get['object_id'], $request->get['room_id'] ));

	break;
	
	case "add":
		User::isUserLogged("OWNER");
		Kernel::template("prices/add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			$result = $prices->add();
			if($result == true) {
				Kernel::setMessage("NOTICE" , "Dodano nową pozycję");
				if( $config['announcement_moderate'] == "TRUE" ) {
					Objects::setStatus($request->post['object_id'], "FALSE");
				}
				Kernel::redirect($app_url . "panel/objects/prices/list/?object_id=" . $request->get['object_id'] . "&room_id=" . $request->get['room_id']);
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas tworzenia dodawania pozycji:<br/>" . ObjectsPrices::$Error);
			}
		}
	break;
	
	case "edit":
		User::isUserLogged("OWNER");
		Kernel::template("prices/rooms.smarty");
		if(empty($request->post)) { Form::$post = $prices->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			$result = $prices->save( $request->get['id'] );
			if($result == true) {
				Kernel::setMessage("NOTICE" , "Pomyślnie zapisano zmiany");
				if( $config['announcement_moderate'] == "TRUE" ) {
					Objects::setStatus($request->post['object_id'], "FALSE");
				}
				Kernel::redirect($app_url . "panel/objects/prices/list/?object_id=" . $request->get['object_id'] . "&room_id=" . $request->get['room_id']);
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas zapisywania zmian:<br/>" . ObjectsPrices::$Error);
			}
		}
	break;
	
	case "delete":
		User::isUserLogged("OWNER");
		$result = $prices->delete( $request->get['id'] );
		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto pozycję");
			Kernel::redirect($app_url . "panel/objects/prices/list/?object_id=" . $request->get['object_id'] . "&room_id=" . $request->get['room_id']);
		} else {
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas usuwania pozycji. Najprawdopodobniej wybrana pozycja nie istnieje<br/>" . ObjectsPrices::$Error);
		}
	break;
}
?>