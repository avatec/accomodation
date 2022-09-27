<?php
Kernel::access("objects;");
Kernel::module("objects");

switch( $command ) {
	
	case "list":
		Kernel::$ModuleName = "Cennik pokoju";
		Kernel::setJs("filter.js" , "objects");
		Kernel::template("prices/list.smarty");
		$smarty->assign("list" , $prices->get());
		$smarty->assign("filter" , $prices->filter( $request->get['object_id'], $request->get['room_id'] ));

	break;
	
	case "add":
		Kernel::$ModuleName = "Dodawanie nowego pokoju do obiektu";
		Kernel::template("prices/add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			$result = $prices->add();
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Dodano nową pozycję");
				Kernel::redirect($app_url . "admin/objects/prices/list/?object_id=" . $request->get['object_id'] . "&room_id=" . $request->get['room_id']);
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas tworzenia dodawania pozycji:<br/>" . ObjectsPrices::$Error);
			}
		}
	break;
	
	case "edit":
		Kernel::$ModuleName = "Edycja pokoju";
		Kernel::template("prices/add-edit.smarty");
		if(empty($request->post)) { Form::$post = $prices->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			$result = $prices->save( $request->get['id'] );
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Pomyślnie zapisano zmiany");
				Kernel::redirect($app_url . "admin/objects/prices/list/?object_id=" . $request->get['object_id'] . "&room_id=" . $request->get['room_id']);
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas zapisywania zmian:<br/>" . ObjectsPrices::$Error);
			}
		}
	break;
	
	case "delete":
		$result = $prices->delete( $request->get['id'] );
		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto pozycję");
			Kernel::redirect($app_url . "admin/objects/prices/list/?object_id=" . $request->get['object_id'] . "&room_id=" . $request->get['room_id']);
		} else {
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas usuwania pozycji. Najprawdopodobniej wybrana pozycja nie istnieje<br/>" . ObjectsPrices::$Error);
		}
	break;
}	


?>