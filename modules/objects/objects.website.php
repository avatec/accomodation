<?php
Kernel::module("objects");
Kernel::schema("panel");

switch( $command ) {
	case "search":
		Kernel::schema("objects/search");
		Kernel::setJs("search.js" , "objects");
		$smarty->assign("equipment" , $equipment->get());
		$smarty->assign("improvement" , $improvement->get());
		$smarty->assign("distance" , $distance->get());

		$smarty->assign("results" , $objects->search( (!empty($id) ? $id : null ) ));

		if(!empty(Objects::$countedResults)) {
			$smarty->assign("counted_results" , Objects::$countedResults);
		} else {
			$smarty->assign("counted_results" , 0);
		}
		$smarty->assign("promoted" , $objects->getPromoted("MAIN",3));

		Kernel::addMeta(
			$config['service_meta_title'] . ' - wyszukiwarka',
			$config['service_meta_description'],
			$config['service_meta_keywords'],
			true, true
		);
	break;

	case "list-by-special":
		Kernel::schema("objects/special-list");
		Kernel::setJs("search.js" , "objects");
		$smarty->assign("equipment" , $equipment->get());
		$smarty->assign("improvement" , $improvement->get());
		$smarty->assign("distance" , $distance->get());

		$smarty->assign("results" , $objects->getSpecial( $id ));
		$smarty->assign("promoted" , $objects->getPromoted("MAIN",3));

		$row = $special->get( SpecialOffers::getID( $id ) );
		Kernel::addMeta(
			(!empty($row['meta_title']) ? $row['meta_title'] : $config['service_meta_title'] . ' - wyszukiwarka'),
			(!empty($row['meta_description']) ? $row['meta_description'] : $config['service_meta_description']),
			(!empty($row['meta_keywords']) ? $row['meta_keywords'] : $config['service_meta_keywords']),
			true, true
		);
	break;

	case "list-by-city":
		Kernel::schema("objects/list");
		Kernel::setJs("search.js" , "objects");

		$smarty->assign("equipment" , $equipment->get());
		$smarty->assign("improvement" , $improvement->get());
		$smarty->assign("distance" , $distance->get());

		$smarty->assign("results" , $result = $objects->getByCity( $id ));

		if(empty($result)) {
			header('HTTP/1.0 404 Not Found');
			//Kernel::redirect( $app_url . "error/404/", 404);
			//exit;
		}
		$meta = ObjectsCities::getMeta( $id );

		Kernel::addMeta(
			(!empty($meta['meta_title']) ? $meta['meta_title'] : $config['service_meta_title'] . ' - wyszukiwarka'),
			(!empty($meta['meta_description']) ? $meta['meta_description'] : $config['service_meta_description']),
			(!empty($meta['meta_keywords']) ? $meta['meta_keywords'] : $config['service_meta_keywords']),
			true, true
		);

		$smarty->assign("city_name" , ObjectsCities::getName( ObjectsCities::searchByName($id)));
		$smarty->assign("city_description" , ObjectsCities::getDescription( ObjectsCities::searchByName($id)));
		$smarty->assign("promoted" , $objects->getPromoted("MAIN",3));
	break;

	case "view":
		$o = $objects->getView( $id );
		if($o == false) {
			header('HTTP/1.0 404 Not Found');
			Kernel::redirect( $app_url . "error/404/");
			exit;
		}
		Kernel::$GoogleMaps = true;
		Kernel::template("objects/view.smarty");
		//Kernel::setCss("prettyPhoto.css" , null);
		//Kernel::setJs("jquery.prettyPhoto.js" , null);
		Kernel::setCss("gallery.css" , "objects");
		Kernel::setCss("magnificpopup.min.css" , null);
		Kernel::setJs("magnificpopup.min.js" , null);
		Kernel::setJs("rc.min.js");
		Kernel::setJs("view.js" , "objects");
		Kernel::setJs("comments.js" , "objects");
		Kernel::setJs("request-photos.js" , "objects");
		Kernel::setJs("send-message.js" , "objects");


		if(isset($config['exclusive'])) {
			//Kernel::setCss("booking-layer.css" , 'booking');
			//Kernel::setJs("booking-layer.js" , "booking");
		}
		$smarty->assign("view" , $o);
		$smarty->assign("photos" , $photos->getByObject( $id, false ));

		if(!isset($config['basic'])) {
			$smarty->assign("rooms" , $rooms->getByObject( $id ));
			if($config['announcement_video'] == "TRUE") {
				$smarty->assign("videos" , $video->getByObject( $id, false ));
			}
		}

		if($config['announcement_comments'] == "TRUE") {
			Kernel::setJs("add-comment.js" , "objects");
			$smarty->assign("comments" , $a = $comments->getComments( $id, true ));
		}

		Kernel::addMeta(
			( strlen($o['meta_title'])>0 ? $o['meta_title'] : $o['name'] . ' - ' . $o['city']),
			$o['meta_description'],
			$o['meta_keywords'],
			true, true
		);
	break;

/*
 | Lista obiektów - dostępność tylko dla właścicieli
 */
	case "list":
		User::isUserLogged("OWNER");
		Kernel::$Alertify = true;
		Kernel::template("objects/list.smarty");
		Kernel::setJs("panel-list.js", "objects");

		$smarty->assign("list" , $objects->getByUser( User::$user['id'] ));
		Kernel::addMeta( "Lista twoich obiektów - "  . $config['service_meta_title'] , "", "", false, false);
	break;

	case "add":
		User::isUserLogged("OWNER");
		Kernel::$GoogleMaps = true;
		Kernel::$CkEditor = true;
		Kernel::template("objects/add-edit.smarty");
		//Kernel::setJs("add-edit.js", "objects");
		$smarty->assign("improvement" , $improvement->get());
		$smarty->assign("distance" , $distance->get());

		Kernel::addMeta( "Dodawanie nowych obiektów - "  . $config['service_meta_title'] , "", "", false, false);

		if(!empty($request->post['module'])) {
			$result = $objects->add( false );
			if($result == true) {
				Kernel::redirect( $app_url . "panel/objects/list/");
			} else {
				if(!empty(Objects::$Error)) {
					$msg = implode("<br/>" , Objects::$Error);
				} else {
					$msg = "nieznany błąd";
					print_r(Db::error());
				}

				Kernel::setMessage("ERROR" , "<b>".Language::get("cms" , "msg_form_error").":</b><br/>" . $msg);
			}
		}
	break;

	case "edit":
		User::isUserLogged("OWNER");
		Kernel::$GoogleMaps = true;
		Kernel::$CkEditor = true;
		//Kernel::setJs("add-edit.js", "objects");
		Kernel::template("objects/add-edit.smarty");
		$smarty->assign("improvement" , $improvement->get());
		$smarty->assign("distance" , $distance->get());
		if(empty($request->post)) { Form::$post = $objects->get($request->get['id']); } else { Form::$post = $request->post; }

		Kernel::addMeta( "Edycja obiektu - "  . $config['service_meta_title'] , "", "", false, false);

		if(!empty($request->post['module'])) {
			$Result = $objects->save($request->get['id']);
			if($Result == true) {
				Kernel::setMessage("NOTICE" , Language::get('cms' , 'msg_save_success'));
				Kernel::redirect($app_url . "panel/objects/list/");
			} else {
				Kernel::setMessage("ERROR" , "<b>".Language::get("cms" , "msg_form_error").":</b><br/>" . implode("<br/>" , Objects::$Error));
			}
		}
	break;

	case "delete":
		User::isUserLogged("OWNER");
		if($objects->delete( $request->get['id'] ) == true) {
			Kernel::setMessage("NOTICE" , Language::get('cms' , 'msg_del_success'));
		} else {
			Kernel::setMessage("ERROR" , Language::get('cms' , 'msg_db_error')."<br/>" . Objects::$Error);
		}
		Kernel::redirect($app_url . "panel/objects/list/");
	break;

	case "order":
		User::isUserLogged("OWNER");
		Kernel::template("objects/order.smarty");
		Kernel::setCss("orders.min.css" , "objects");
		Kernel::setJs("orders.min.js" , "objects");
		$smarty->assign("promotion" , $a = $promotion->getByType( (isset($request->get['what']) ? $request->get['what'] : null) ));
		Kernel::addMeta( "Wyróżnienie obiektu - "  . $config['service_meta_title'] , "", "", false, false);
	break;
}

?>
