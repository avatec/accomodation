<?php
Kernel::access("config;");
Kernel::module('system');
Form::$post = $system->configGet();

$smarty->assign("hash" , time());

switch($command) {
	case "main":
		Kernel::$ModuleName = "Konfiguracja podstawowa";
		Kernel::setJs("config/main.js", "system");
		Kernel::template("config/main.smarty");
		if(!empty($request->post)) {
			$system->configSave();
			$system->configUploadLogo();
			
			Kernel::redirect($app_request_url);
		}
	break;
	
	case "contact":
		Kernel::$ModuleName = "Konfiguracja danych teleadresowych";
		Kernel::template("config/contact.smarty");
		if(!empty($request->post)) {
			$system->configSave();
			Kernel::redirect($app_request_url);
		}
	break;
	
	case "smtp":
		Kernel::$ModuleName = "Konfiguracja konta SMTP";
		Kernel::template("config/smtp.smarty");
		if(!empty($request->post)) {
			
			if(isset($request->post['test-mail'])) {
				Mail::$address = $request->post['email'];
				Mail::$name = $request->post['email'];
				Mail::$subject = "Wiadomość testująca ustawienia konta SMTP";
				Mail::$text = "Gratulujemy" . PHP_EOL . "Wygląda na to, że skonfigurowałeś konto SMTP poprawnie." . PHP_EOL;
				
				$Result = Mail::send();
				if($Result == true) {
					
					Kernel::setMessage("NOTICE" , "Serwer SMTP skonfigurowany poprawnie");
					Kernel::redirect($app_request_url);
				} else {
					Kernel::setMessage("ERROR" , Mail::$error);
				}
			} else {
				Kernel::setMessage("NOTICE" , "Konfiguracja została zapisana");
				$system->configSave();
				Kernel::redirect($app_request_url);
			}
		}
	break;
	
	case "social":
		Kernel::$ModuleName = "Konfiguracja adresów kont serwisów społecznościowych";
		Kernel::template("config/social.smarty");
		if(!empty($request->post)) {
			if(!empty($_FILES['social_img']['name'])) {
				$system->configSocialImage();
			}
			$system->configSave();
			Kernel::redirect($app_request_url);
		}
	break;
	
	case "seo":
		Kernel::$ModuleName = "Konfiguracja domyślnych ustawień SEO";
		Kernel::template("config/seo.smarty");
		if(!empty($request->post)) {
			$system->configSave();
			global $app_request_url;
			Kernel::redirect($app_request_url);
		}
	break;
	
	case "payments":
		Kernel::$ModuleName = "Konfiguracja bramek płatności";
		Kernel::setJs("config/payments.js" , "system");
		Kernel::template("config/payments.smarty");
		$smarty->assign("payments_modules" , $payments_modules);

		if(!empty($request->post)) {
			$system->configSave();
			Kernel::redirect($app_request_url);
		}
	break;
	
	case "accomodation":
		Kernel::$ModuleName = "Konfiguracja domyślnych ustawień skryptu";
		Kernel::template("config/accomodation.smarty");
		Kernel::setCss("config/watermark.css" , "system");
		Kernel::setJs("config/accomodation.js", "system");
		Kernel::setJs("config/watermark.js" , "system");
		
		if( file_exists( $app_path . "templates/website/images/watermark.png" ) == true ) {
			$smarty->assign("preview_watermark" , $app_url . "templates/website/images/watermark.png");
		} else {
			$smarty->assign("preview_watermark" , $app_url . "templates/admin/img/logo.png");	
		}
		
		if(!empty($request->post)) {
			if(!empty($_FILES['watermark']['name'])) {
				$system->waterMarkLogoUpload();
			}
			$system->configSave();
			Kernel::redirect($app_request_url);
		}
	break;
	
	case "newsletter":
		Kernel::$ModuleName = "Konfiguracja newslettera";
		Kernel::template("config/newsletter.smarty");
		if(!empty($request->post)) {
			$system->configSave();
			Kernel::redirect($app_request_url);
		}
	break;
	
	/**case "rules":
		Kernel::$ModuleName = "Konfiguracja treści umowy";
		Kernel::template("config/rules.smarty");
		if(!empty($request->post)) {
			$system->configSave();
			global $app_request_url;
			Kernel::redirect($app_request_url);
		}
	break;**/
}