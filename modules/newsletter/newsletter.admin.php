<?php
/**
  * Kontroler PA odpowiadający za zarządzanie adresami e-mail w newsleterze
  * 
  * @author: Grzegorz Miskiewicz <biuro@avatec.pl>
  * @package: Avatec Framework
  *
  * Ten produkt jest licencjonowany
  * Możesz modyfikować te pliki jednak nie możesz usuwać oryginalnych komentarzy
  * w szczególności informacji o autorze tego oprogramowania
  * 
  * W przypadku indywidualnego modyfikowania tego pliku autor nie ponosi
  * odpowiedzialności za wszelkie błędy i/lub wady tego oprogramowania.
 */
Kernel::module("newsletter");
Kernel::access("newsletter;");
	
switch( $command ) {
	
	case "list":
		Kernel::$ModuleName = "Lista subskrybentów";
		Kernel::template("newsletter/list.smarty");
		$smarty->assign("list" , $newsletter->get());
		
		if(!empty($request->post['delete'])) {
			foreach($request->post['delete'] as $id=>$file) {
				$result = $newsletter->delete( $id, $file );
				if($result == false) {
					Kernel::setMessage("ERROR" , "Wystąpił błąd podczas usuwania zdjęcia " . $file . ". Najprawdopodobniej wybrana pozycja nie istnieje<br/>");
				}
			}
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto wybrane zdjęcie");
			Kernel::redirect($app_url . "admin/newsletter/list/?item=" . $request->get['item']);
		}

		if(!empty($request->post['save-priority'])) {
			$newsletter->priorityUpdate();
			Kernel::redirect($app_url . "admin/newsletter/list/?item=" . $request->get['item']);
		}
	break;
	
	case "add":
		Kernel::$ModuleName = "Dodawanie nowego subskrybenta";
		Kernel::template("newsletter/add-edit.smarty");
		//$smarty->assign("category" , $category->get());
		//$smarty->assign("cities" , $cities->get());
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			if( $newsletter->add() == true ) {
				Kernel::redirect($app_url . "admin/newsletter/list/");
			}
		}
	break;
	
	case "edit":
		Kernel::$ModuleName = "Edycja zdjęcia";
		Kernel::template("newsletter/add-edit.smarty");
		//$smarty->assign("category" , $category->get());
		//$smarty->assign("cities" , $cities->get());
		if(empty($request->post)) { Form::$post = $newsletter->get($request->get['id'], true); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if( $newsletter->save( $request->get['id'] ) == true ) {
				Kernel::redirect($app_url . "admin/newsletter/list/");
			}
		}
	break;
	
	case "delete":
		$result = $newsletter->delete( $request->get['id'] );
		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto zdjęcie");
			Kernel::redirect($app_url . "admin/newsletter/list/");
		} else {
			$error = Db::error();
			if(!empty($error)) {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas usuwania subskrybenta. Najprawdopodobniej wybrana pozycja nie istnieje<br/>" . $error);
			}
		}
	break;
	
	case "export":
		$newsletter->export();
	break;
}	

	
?>