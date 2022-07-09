<?php
/**
  * Kontroler PA odpowiadający za zarządzanie szablonami mailingu w newsleterze
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
		Kernel::template("messages/list.smarty");
		$smarty->assign("list" , $messages->get());
	break;
	
	case "add":
		Kernel::$CkEditor = true;
		Kernel::$ModuleName = "Tworzenie nowego mailingu";
		Kernel::template("messages/compose.smarty");
		
		if(!empty($request->post['module'])) {
			if($messages->add() == true) {
				Kernel::redirect( $app_url . "admin/newsletter/messages/list/");
			}
		}
	break;
	
	case "edit":
		Kernel::$CkEditor = true;
		Kernel::$ModuleName = "Tworzenie nowego mailingu";
		Kernel::template("messages/compose.smarty");
		
		if(empty($request->post)) { Form::$post = $messages->get($request->get['id'], true); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if(!empty($request->get['id'])) { 
				if($messages->save($request->get['id']) == true) {
					Kernel::redirect( $app_url . "admin/newsletter/messages/list/");
				}
			}
		}
	break;
	
	case "delete":
		$result = $messages->delete( $request->get['id'] );
		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto pozycję");
			Kernel::redirect($app_url . "admin/newsletter/messages/list/");
		} else {
			$error = Db::error();
			if(!empty($error)) {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas usuwania pozycji. Najprawdopodobniej wybrana pozycja nie istnieje<br/>" . $error);
			}
		}
	break;
	
	case "send":
		Kernel::$CkEditor = true;
		Kernel::$ModuleName = "Wysyłanie mailingu z szablonu";
		Kernel::template("messages/send.smarty");
		Kernel::setJs("send.js" , "newsletter");
		Kernel::setCss("email-preview.css", 'newsletter');
		$smarty->assign("msg_preview" , $a = $messages->getMessage($request->get['id']));
		
		if(!empty($request->post)) { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if($messages->send($request->get['id']) == true) {
				Kernel::redirect( $app_url . "admin/newsletter/messages/outbox/");
			}
		}
	break;
	
	case "outbox":
		Kernel::$ModuleName = "Skrzynka nadawcza";
		Kernel::template("messages/outbox.smarty");
		$smarty->assign("list" , $messages->getOutbox());
	break;
	
	case "sended":
		Kernel::$ModuleName = "Wiadomości wysłane";
		Kernel::template("messages/sended.smarty");
		$smarty->assign("list" , $messages->getSended());
	break;
}	


?>