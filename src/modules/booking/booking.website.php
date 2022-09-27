<?php
Kernel::module("booking");
Kernel::schema("panel");

switch( $command ) {
	case "list":
		Kernel::template("panel/object-list.smarty");
		Kernel::setCss("cal.css" , "booking");
		$smarty->assign("calendar" , 
			Booking::HTMLCalendar( (isset($request->get['date']) ? $request->get['date'] : null ), $request->get['object_id'])
		);
	break;
	
/*
 | Formularz rezerwacji
 */
 
	case "check":
		Kernel::template("add.smarty");
		Kernel::setCss("booking.css" , "booking");
		Kernel::setCss("calendar.css" , "booking");
		
		Kernel::setJs("reservation.js" , "booking");
		Kernel::setJs("booking.js" , "booking");
		
		if(!empty($id)) {
			$smarty->assign("object" , $objects->get($id[0]));
			$smarty->assign("room" ,  $rooms->get($id[1]));	
			$smarty->assign("room_id" , $id[1]);
		} else {
			$smarty->assign("object" , $objects->get($id[0]));
		}
		
		$calendar = implode("" , Booking::calendar(null,null,null,[ 
			'object_id' => $id[0], 
			'room_id' => $id[1] 
		]));
		$smarty->assign("calendar" , $calendar);
		
		if(!empty(User::$user['id'])) {
			Form::$post['first_name'] = User::$user['first_name'];
			Form::$post['last_name'] = User::$user['last_name'];
			Form::$post['phone'] = User::$user['phone'];
			Form::$post['email'] = User::$user['email'];
		}
		
		if(!empty($request->post['module'])) {
			$Result = $booking->add();
			if($Result == true) {
				$id = md5(Booking::$last_id);
				Kernel::redirect($app_url . "booking/finish/?ident=" . $id);
			}
		}
	break;
	
	case "finish":
		Kernel::template("finish.smarty");
		
		$order = $booking->payPrepare( $request->get['ident'] );
		$data = $payment->prepare( $order );
		if($data !== false) {
			$smarty->assign( "data" , array_merge($data,$order) );
			$smarty->assign( "payment_module" , Payment::$module );
		} else {
			echo 'błąd';
			exit;
		}
	break;
	
/**
 * Strona po powrocie z systemu płatności
 */
 
	case "payment-finish":
		Kernel::template("payment/message.smarty");
	break;
	
/** 
 * Weryfikacja płatności 
 */
 
	case "payment-verify":
		$result = $payment->verify( true );
		if(!empty( $result )) {
			$bid_pm = '/bid\=([0-9]+)/';
			preg_match_all($bid_pm, $request->post['control'], $m);
			if((!empty($m)) && (!empty($m['1']['0']))) {
				$bid = $m['1']['0'];
				unset($m);
			}
			Booking::_updatePayment( $bid , $result );
		}
		exit;
	break;
	
	case "pay":
		Kernel::template("finish.smarty");
		if(!empty($request->get['sid'])) {
			$Result = Booking::checkStatusBySID( $request->get['sid'] );
			$smarty->assign("result" , $Result);
		}
		$smarty->assign("done" , true);
	break;
	
	case "verify":
		file_put_contents($logs . "p24.log",  PHP_EOL . PHP_EOL . "Script sends POST to verify: " . PHP_EOL , FILE_APPEND);
		file_put_contents($logs . "p24.log",  "Verify POST: " . PHP_EOL . print_r($request->post, true) . PHP_EOL, FILE_APPEND);

		include $app_path . "modules/payment/p24.class.php";
		Payments::$pos_id = "14158";
		Payments::$crc_key = "e4ea3606262af21a";
		Payments::$sandbox = true;
		
		$Result = Payments::verify($request->post);
		file_put_contents($logs . "p24.log",  "Verify result: " . PHP_EOL . print_r($Result, true) . PHP_EOL, FILE_APPEND);
		
		if($Result['error'] == 0) {
			$booking->updatePayment($request->post['p24_statement'], $request->post['p24_session_id']);
			die("OK");
		}
	break;
	
	case "cancel":
		$Result = $booking->cancel($request->get['id'], $request->get['object_id'], $request->get['room_id']);
		if($Result == true) {
			echo "anulowano";
			exit;
		} else {
			echo "anulowano ale false";
			exit;
		}
	break;
}
?>