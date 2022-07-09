<?php
Kernel::access("booking;");
Kernel::module("booking");
Kernel::$ModuleName = "Rezerwacje";

switch( $command ) {
	
	case "list":
		Kernel::template("list.smarty");
		$smarty->assign("list" , $booking->get());
	break;
	
	case "list-by-user":
		Kernel::$CheckBox = true;
		Kernel::$ModuleName = "Rezerwacje użytkownika: <b>" . User::getField('name', $request->get['uid']) . "</b>";
		Kernel::template("list-by-user.smarty");
		Kernel::setJs('admin/list-by-user.js' , 'booking');
		
		$result = $booking->getByUser( $request->get['uid'] );
		$smarty->assign("list" , $result['list']);
		$smarty->assign("data" , $result['data']);
	break;
	
	case "payoff":
		Kernel::$ModuleName = "Wypłata środków dla użytkownika: <b>" . User::getField('name', $request->post['uid']) . "</b>";
		Kernel::template("payoff.smarty");
		
		if(!empty($request->post['module'])) {
			if(!empty($request->post['invoice'])) {
				$booking->payoff_complete();
				Kernel::redirect( $app_url . "admin/booking/list-by-user/?uid=" . $request->post['uid']);
			} else {
				$result = $booking->payoff_prepare_data();
				
				$smarty->assign("data" , $result['data']);
				$smarty->assign("u" , $result['u']);
				$smarty->assign("selectid" , addslashes(json_encode($request->post['selectid'])));
			}
		}
	break;
	
	case "add":
		Kernel::template("add-edit.smarty");
		Form::$post = $request->post;
		
		if(!empty($request->post['module'])) {
			if( $booking->add() == true ) {
				Kernel::redirect($app_url . "admin/booking/list/");
			}
		}
	break;
	
	case "edit":
		Kernel::template("add-edit.smarty");
		if(empty($request->post)) { 
			Form::$post = $booking->getSingle($request->get['id']); 
		} else { 
			Form::$post = $request->post; 
		}
		
		if(!empty($request->post['module'])) {
			if( $booking->save( $request->get['id'] ) == true ) {
				Kernel::redirect($app_url . "admin/booking/list/");
			}
		}
	break;
	
	case "delete":
		$booking->delete( $request->get['id'], $request->get['file'] );
		Kernel::redirect($app_url . "admin/booking/list/");
	break;
	
	case "pay-accept":
		Booking::_updatePayment( $request->get['id'], 'CONFIRM', true );
		Kernel::redirect($app_url . "admin/booking/list/");
	break;
	
	case "cancel":
		$Result = $booking->cancel($request->get['id'], $request->get['object_id'], $request->get['room_id']);
		Kernel::redirect($app_url . "admin/booking/list/");
	break;
	
}	


?>