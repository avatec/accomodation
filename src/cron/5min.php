<?php
/*
 * Plik wywołujący funkcje harmonogramowe co 5 minut
 * -------------------------------------------------
 */

if(!defined("APP")) {
	Kernel::createLog('cron-5min.log' , '[ERROR] trying to run directly');
	die("Please configure Your cron to run this file using url: http://yourdomain.com/cron/5min");
}

$send_limit = 100;

/*
 * Newsletter
 * -------------------------------------------------
 */

$outbox = $messages->getOutbox( $send_limit );
if(!empty($outbox)) {
	$np = 0;
	$nn = 0;

	Kernel::createLog('cron-5min.log' , '[OUTBOX] ' . count($outbox) . ' messages ready to sent - using limit: ' . $send_limit);
	foreach($outbox as $i) {
		if(NewsletterMessages::isAutomatic($i['msg_id']) == true) {
			$msg = $messages->getMessage($i['msg_id']);
		} else {
			$msg = file_get_contents( $app_path . "include/email_templates/newsletter/main.html");	
		}
		$msg = str_replace("[app-url]" , $app_url, $msg);
		$msg = str_replace("[sender-name]" , $config['service_meta_title'], $msg);
		$msg = str_replace("[subject]" , $messages->getMessage($i['msg_id'], true), $msg);
		$msg = str_replace("[content]" , html_entity_decode($messages->getMessage($i['msg_id'])), $msg);
		$msg = str_replace("[delete]" , $app_url . "newsletter/unsubscribe/" . md5($i['email']), $msg);
		$msg = str_replace("[email]" , $i['email'], $msg);
		$msg = str_replace("[msg_id]" , $i['id'], $msg);


		Mail::$address = $i['email'];
		Mail::$name = $i['email'];
		Mail::$subject = $messages->getMessage($i['msg_id'], true);
		Mail::$text = $msg;

		if( Mail::send() == true ) {
			NewsletterMessages::_updateMsgStatus( "SENT" , $i['id'], $i['msg_id']);
			$np++;
		} else {
			NewsletterMessages::_updateMsgStatus( "ERROR" , $i['id']);
			Kernel::createLog('cron-5min.log' , '[ERROR] There was error trying to sent message ' . $i['msg_id'] . ' for: ' . $i['email'] . '. Returned error: ' . Mail::$error);
			$nn++;
		}
	}

	if( $np > 0 OR $nn > 0) {
		Kernel::createLog('cron-5min.log' , '[FINISH] Positive: ' . $np . ' / Negative: ' . $nn);
	}
}

echo 'You have configured Your cron correctly!';
exit;
?> 