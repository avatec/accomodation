<?php
Kernel::schema("contact");
$smarty->assign("partners" , $partner->getForCarousel());

if(!empty($request->post['module'])) {

$Pages = $content->getByModule("contact");
	$smarty->assign("content" , $Pages);
				
	Kernel::addMeta( 
		(empty($Pages['meta_title']) ? $config['service_meta_title'] . " - Formularz kontaktowy" : $Pages['meta_title']), 
		(empty($Pages['meta_desc']) ? $config['service_meta_description'] : $Pages['meta_desc']), 
		(empty($Pages['meta_keys']) ? $config['service_meta_keywords'] : $Pages['meta_keys']), 
		(empty($Pages['meta_index']) ? 'index' : $Pages['meta_index']), 
		(empty($Pages['meta_follow']) ? 'follow' : $Pages['meta_follow'])
	);
	
	Kernel::schema("contact");
	if(!empty($request->post)) {
	
		if(empty($request->post['name'])) {
			$Error[] = "musisz podać imię";
		}
		if(empty($request->post['email'])) {
			$Error[] = "musisz podać adres e-mail";
		}
		if(empty($request->post['phone'])) {
			$Error[] = "musisz podać numer telefonu";
		}
		if(empty($request->post['text'])) {
			$Error[] = "musisz wpisać wiadomość";
		}
		
		if(empty($request->post['rules']['1'])) {
			$Error[] = "musisz zaznaczyć pierwszego checkboxa, aby wysłać wiadomość";
		}
		
		if( !empty($config['google_recaptcha_secretkey']) ) {
			$recaptcha = new \ReCaptcha\ReCaptcha($config['google_recaptcha_secretkey']);
			$resp = $recaptcha->verify($request->post['g-recaptcha-response'], Kernel::getIp());
			if (!$resp->isSuccess()) {
				$Error[] = implode("" , $resp->getErrorCodes());
			}
		}
		
		if(!empty($Error)) {
			Kernel::setMessage("ERROR" , implode("<br/>" , $Error));
		} else {
			define("PHP_BR" , "<br/>");
			
			$text = (!empty($request->post['text']) ? $request->post['text'] : 'brak wiadomości');
			Mail::$address = $config['default_email'];
			Mail::$name = $config['default_email'];
			Mail::$subject = "Wiadomość z formularza kontaktowego " . str_replace("http://" , "" , $app_url);
			Mail::$text = "Uzupełniono formularz kontaktowy na stronie " . str_replace("http://" , "" , $app_url) . PHP_BR .
			"Nadawca wiadomości: " . PHP_BR . PHP_BR . 
			(!empty($request->post['business_name']) ? "Nazwa firmy: " . $request->post['business_name'] . PHP_BR : "") . 
			"Imię: " . $request->post['name'] . PHP_BR .
			"Email: " . $request->post['email'] . PHP_BR .
			"Telefon: " . $request->post['phone'] . PHP_BR .
			"Treść wiadomości: " . PHP_BR . $request->post['text'] . PHP_BR . PHP_BR . 
			"Zaznaczone zgody: " . PHP_BR .
			(!empty($request->post['rules'][1]) ? $config['rules_rodo_1'] . PHP_BR : '') .
			(!empty($request->post['rules'][2]) ? $config['rules_rodo_2'] . PHP_BR : '') .
			(!empty($request->post['rules'][3]) ? $config['rules_rodo_3'] . PHP_BR : '') . PHP_BR .
			"Wiadomość wysłana dnia " . date('Y-m-d') . " o godz. " . date('H:i:s') . PHP_BR .
			"z adresu IP: " . Kernel::getIp() . PHP_BR;
			
			$Result = Mail::send();
			if($Result !== true) {
				Kernel::setMessage("ERROR" , Mail::$error);
			}
			
			Kernel::setMessage("NOTICE" , "Wiadomość została wysłana");
		}
	}
}