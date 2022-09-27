<?php
/**
  * Kontroler Text dla Avatec Framework
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
 
Kernel::access("text;");
Kernel::module("system");
Kernel::$ModuleName = "Treści SMS";

switch($command) {
	case "list":
		Kernel::template("sms/list.smarty");
		$smarty->assign("list" , $sms->get());
	break;
	
	case "add":
		Kernel::template("sms/add-edit.smarty");
		Kernel::$CkEditor = true;
		
		if(!empty($request->post['module'])) {
			if( $sms->add() == true ) {
				Kernel::redirect($app_url . "admin/system/sms/list/");
			}
		}
	break;
	
	case "edit":
		Kernel::template("sms/add-edit.smarty");
		Kernel::$CkEditor = true;
		
		if(empty($request->post)) { Form::$post = $sms->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			if( $sms->save( $request->get['id'] ) == true ) {
				Kernel::redirect($app_url . "admin/system/sms/list/");
			}
		}
	break;
	
	case "delete":
		$sms->delete( $request->get['id'] );
		Kernel::redirect($app_url . "admin/system/sms/list/");
	break;
}