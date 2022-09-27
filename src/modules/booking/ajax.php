<?php
switch($command) {
	case "checkin":
		if(empty($request->get['checkin'])) {
			die("ERROR_NO_DATA_SENDED");
		}
		
		$day_checkin = date('N' , strtotime($request->get['checkin']));
		$month_checkin = date('n' , strtotime($request->get['checkin']));
		$year_checkin = date('Y' , strtotime($request->get['checkin']));
		
		$array['checkin'] = array(
			"day" => date('j' , strtotime($request->get['checkin'])),
			"dayname" => Booking::dayName($day_checkin, false),
			"month" => Booking::monthName($month_checkin, false),
			"year" => $year_checkin
		);
		
		if(!empty($array)) {
			die(json_encode($array));
		}
	break;
	
	case "checkout":
		if(empty($request->get['checkout'])) {
			die("ERROR_NO_DATA_SENDED");
		}
		
		$day_checkout = date('N' , strtotime($request->get['checkout']));
		$month_checkout = date('n' , strtotime($request->get['checkout']));
		$year_checkout = date('Y' , strtotime($request->get['checkout']));
		
		$array['checkout'] = array(
			"day" => date('j' , strtotime($request->get['checkout'])),
			"dayname" => Booking::dayName($day_checkout, false),
			"month" => Booking::monthName($month_checkout, false),
			"year" => $year_checkout
		);
		
		if(!empty($array)) {
			die(json_encode($array));
		}
	break;
	
/** Wyliczanie łącznej ceny za noclegi **/
	case "count-amount":
		if(empty($request->get['object_id'])) {
			die("Booking_Ajax::count-amount: ERROR_NO_DATA_SENDED");
		}
		if(empty($request->get['room_id'])) {
			die("Booking_Ajax::count-amount: ERROR_NO_DATA_SENDED");
		}
		if(empty($request->get['checkin'])) {
			die("Booking_Ajax::count-amount: ERROR_NO_DATA_SENDED");
		}
		if(empty($request->get['checkout'])) {
			die("Booking_Ajax::count-amount: ERROR_NO_DATA_SENDED");
		}
		
		
		$adult = (isset($request->get['adult']) ? $request->get['adult'] : 1);
		$child = (isset($request->get['child1']) ? $request->get['child1'] : 0);
		
		$amount = 0;
		for($i=1;$i<=$request->get['days'];$i++) {
			$date = date("Y-m-d" , strtotime("-" . $i . " days", strtotime($request->get['checkout'])));
			$price = ObjectsPrices::getAmount( $request->get['object_id'], $request->get['room_id'], $date );
			$array['amounts'][] = $price;
			$array['dates'][] = $date;
			$amount = $amount + $price;
		}
		$amount = $amount * $adult;
		unset($price);
		
		$amount_child = 0;
		if($child > 0) {
			for($i=1;$i<=$request->get['days'];$i++) {
				$date = date("Y-m-d" , strtotime("-" . $i . " days", strtotime($request->get['checkout'])));
				$price = ObjectsPrices::getAmount( $request->get['object_id'], $request->get['room_id'], $date, true );
				$array['amounts'][] = $price;
				$array['dates'][] = $date;
				$amount_child = $amount_child + $price;
			}
		}
		$amount_child = $amount_child * $child;
		
		$amount = $amount + $amount_child;
		$advance = $amount * 0.25;
		
		$array['amount'] = $amount;
		$array['advance'] = sprintf("%01.2f" , $advance);
		
		die( json_encode($array) );
	break;
	
	case "parse":
		if(empty($request->get['object_id'])) {
			die("ERROR_NO_DATA_SENDED");
		}
		if(empty($request->get['room_id'])) {
			die("ERROR_NO_DATA_SENDED");
		}
		if(empty($request->get['checkin'])) {
			die("ERROR_NO_DATA_SENDED");
		}
		if(empty($request->get['checkout'])) {
			die("ERROR_NO_DATA_SENDED");
		}
		
		$day_checkin = date('N' , strtotime($request->get['checkin']));
		$month_checkin = date('n' , strtotime($request->get['checkin']));
		$year_checkin = date('Y' , strtotime($request->get['checkin']));
		
		$day_checkout = date('N' , strtotime($request->get['checkout']));
		$month_checkout = date('n' , strtotime($request->get['checkout']));
		$year_checkout = date('Y' , strtotime($request->get['checkout']));
		
		$array['checkin'] = array(
			"day" => date('j' , strtotime($request->get['checkin'])),
			"dayname" => Booking::dayName($day_checkin, false),
			"month" => Booking::monthName($month_checkin, false),
			"year" => $year_checkin,
			"checkin" => $request->get['checkin']
		);
		$array['checkout'] = array(
			"day" => date('j' , strtotime($request->get['checkout'])),
			"dayname" => Booking::dayName($day_checkout, false),
			"month" => Booking::monthName($month_checkout, false),
			"year" => $year_checkout,
			"checkout" => $request->get['checkout']
		);
		
		die(json_encode($array));
	break;
	
	case "read":
		if( (isset($request->get['object_id'])) && (isset($request->get['room_id'])) ) {
			$Results = Booking::get($request->get['object_id'], $request->get['room_id']);
			if(!empty($Results)) {
				foreach($Results as $k=>$v) {
					$checkin = $v['checkin'];
					$checkout = $v['checkout'];
					$days = strtotime($checkout) - strtotime($checkin);
					$days = $days / 60 / 60 / 24;
					
					for($i=0; $i<=$days; $i++) {
						$dates[$k][] = date('Y-m-d' , strtotime("+" . $i . " days", strtotime($checkin)));
					}
					unset($checkin);
					unset($checkout);
					unset($days);
				}
				
				if(!empty($dates)) {
					die(json_encode($dates, true));
				}
			} else {
				die("NO_RESERVATION");
			}
			die("ERROR_NO_DATA_SENDED");
		}
	break;	
}

exit;
?>