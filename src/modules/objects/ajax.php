<?php
switch( $command )
{
	case "AddPlusRecommend":
		echo $objects->recommend($request->post['id'], 'plus');
	break;

	case "AddMinusRecommend":
		echo $objects->recommend($request->post['id'], 'minus');
	break;

	case "AddPlusRecommendComment":
		echo $comments->recommend($request->post['id'], 'plus');
	break;

	case "AddMinusRecommendComment":
		echo $comments->recommend($request->post['id'], 'minus');
	break;

	case "get-for-map":
		echo $objects->get_for_map();
	break;

	case "add-comment":
		if($comments->add() == true)
		{
			$JSON['RESULT'] = true;
		} else {
			$JSON['RESULT'] = false;
			$JSON['ERROR'] = ObjectsComments::$Error;
		}

		die( json_encode( $JSON ));
	break;

	case "update-photo-priority":
		$r = ObjectsPhotos::update_priority($request->post['id'], $request->post['priority']);
		if($r == true ) {
			$JSON['RESULT'] = true;
		} else {
			$JSON['RESULT'] = false;
			$JSON['ERROR'] = Db::error();
		}

		die( json_encode( $JSON ));
	break;

	case "update-room-photo-priority":
		$r = ObjectsPhotosRoom::update_priority($request->post['id'], $request->post['priority']);
		if($r == true ) {
			$JSON['RESULT'] = true;
		} else {
			$JSON['RESULT'] = false;
			$JSON['ERROR'] = Db::error();
		}

		die( json_encode( $JSON ));
	break;

	case "request-photos":
		if(!empty($request->post['object_id'])) {
			$o = Objects::_get( $request->post['object_id'] );
			$name = User::getField( "name" , $o['uid'] );
			$email = User::getField( "email" , $o['uid'] );
			$link = $app_url . 'noclegi/' . $o['city_rw'] . '/' . Kernel::rewrite($o['name']) . '-i' . $o['id'];
			$login_link = $app_url . 'panel/login/';

			Mail::$name = $name;
			Mail::$address = $email;
			Mail::$subject = "Prośba o dodanie zdjęć do oferty";
			$text = str_replace("[object-name]" , $o['name'], Emails::getByName("request-photos"));
			$text = str_replace("[object-link]" , $link, $text);
			Mail::$text = str_replace("[login-url]" , $login_link, $text);

			if( Mail::send() == true ) {
				die("OK");
			} else {
				die("ERROR: " . Mail::$error);
			}
		}
	break;

	case "send-message":
		if( $request->post['token'] != Kernel::$token ) {
			//$json['ERROR'][] = 'Nieprawidłowy token. Przeładuj stronę i spróbuj ponownie.';
		}

		if(empty($request->post['contact_name'])) {
			$error[] = "musisz podać swoje imię i nazwisko";
		}
		if(empty($request->post['contact_email'])) {
			$error[] = "musisz podać swój adres e-mail";
		}
		if(empty($request->post['contact_msg'])) {
			$error[] = "wprowadź wiadomość";
		}

		if(!empty($error)) {
			$json['ERROR'] = $error;
		}

		$o = Objects::_get( $request->post['object_id'] );
		$name = User::getField( "name" , $o['uid'] );
		$email = User::getField( "email" , $o['uid'] );
		$link = $app_url . 'noclegi/' . $o['city_rw'] . '/' . Kernel::rewrite($o['name']) . '-i' . $o['id'];

		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			Mail::$address = $email;
		} else {
			Mail::$address = $o['email'];
		}
		Mail::$name = $name;
		Mail::$reply_to = $request->post['contact_email'];
		Mail::$subject = "Pytanie dotyczące ofery";

		$text = Emails::getByName("object-contact-msg");
		$text = str_replace("[object-name]" , $o['name'], $text);
		$text = str_replace("[object-link]" , $link, $text);
		$text = str_replace("[name]" , $request->post['contact_name'], $text);
		$text = str_replace("[email]" , $request->post['contact_email'], $text);
		$text = str_replace("[phone]" , $request->post['contact_phone'], $text);
		$text = str_replace("[text]" , $request->post['contact_msg'], $text);

		$email_template = file_get_contents( $app_path . "include/email_templates/newsletter/template.html");
		$msg = str_replace("[app-url]" , $app_url, $email_template);
		$msg = str_replace("[sender-name]" , $config['service_meta_title'], $msg);
		$msg = str_replace("[subject]" , Mail::$subject, $msg);
		$msg = str_replace("[content]" , $text, $msg);

		Mail::$text = $msg;
		$Result = Mail::send();
		if(($Result == true) && (empty($json))) {
			$json['RESULT'] = true;
		} else {
			$json['ERROR'][] = Mail::$error;
			$json['RESULT'] = false;
		}
		die( json_encode( $json ));
	break;

	case "order-amount":
		if((empty($request->post['pid'])) || (empty($request->post['payment']))) {
			die("ERROR");
		}

		$result = $promotion->get( $request->post['pid'] );
		$JSON['text'] = '<li>' . $result['name'] . '</li>';

		if($request->post['payment'] == "sms") {
			$JSON['amount'] = $result['amount_sms'];
			$JSON['text'] .= '<li>Płatność za pomocą kodu SMS</li>';
		}
		if($request->post['payment'] == "online") {
			$JSON['amount'] = $result['amount_online'];
			$JSON['text'] .= '<li>E-przelew, płatność kartą kredytową, przelew tradycyjny</li>';
		}

		if(!empty($JSON)) {
			echo json_encode( $JSON );
		}
	break;
}

exit;
