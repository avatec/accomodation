<?php
Kernel::module('payment');
Kernel::schema("panel");
$module = Payment::$module;

switch($command) {
	case "begin":
		$data = $payment->prepare();
		if(!empty($data)) {
			switch($data['payment']) {
				case "sms":
					Kernel::template($module . "/sms-form.smarty");
				break;

				case "online":
					Kernel::template($module . "/pay-form.smarty");
				break;
			}
			$smarty->assign("data" , $data);
			if( $module == 'tpay' ) {
				Kernel::setJs("website/tpay-banklist.min.js" , "payment");
				Kernel::setCss("website/tpay-banklist.min.css" , "payment");
			}
		} else {
			if( $module == "dotpay" ) {
				if($payment->verify_sms() == true) {
					Kernel::redirect( $app_url . "payments/finish?status=OK");
				} else {
					Kernel::redirect( $app_url . "payments/finish");
				}
			}
			Kernel::redirect( $app_url . "panel/objects/");
		}
	break;

	case "process-p24":
		$data = $payment->prepare();
		$result = p24::register( $data['p24'] );
		if((!empty($result)) AND (isset($result['token']))) {
			Kernel::redirect( p24::url() . "/" .$result['token'] );
		} else {
			Kernel::template("error.smarty");
			$smarty->assign("error" , $result);
			$smarty->assign("error_msg" , p24::showError($result['error']));
		}
	break;


	case "finish":
		Kernel::template("finish.smarty");
	break;

	case "verify":
		$payment->verify();
		die("OK");
	break;

	case "sms-verify":

	break;
}
