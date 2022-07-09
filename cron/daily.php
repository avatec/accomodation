<?php
/*
 * Plik wywołujący funkcje harmonogramowe raz dziennie
 * ---------------------------------------------------
 */

if(!defined("APP")) {
	Kernel::createLog('cron-daily.log' , '[ERROR] trying to run directly');
	die("Please configure Your cron to run this file using url: http://yourdomain.com/cron/daily");
}

/*
 * Pobieranie informacji o ofertach
 * -----------------------------------------------
 */
 
 	$ViewTommorowExpire = Objects::getExpire( 'VIEW' , 1) ;
 	if(!empty($ViewTommorowExpire)) {
	 	foreach($ViewTommorowExpire as $k=>$i) {
		 	$email = User::getField('email' , $i['uid']);
		 	$View[$email][$k] = $i;
	 	}
	 	if(!empty($email)) {
		 	unset($email);
	 	}
 	}
 	
 	$MainTommorowExpire = Objects::getExpire( 'MAIN' , 1 );
 	if(!empty($MainTommorowExpire)) {
	 	foreach($MainTommorowExpire as $k=>$i) {
		 	$email = User::getField('email' , $i['uid']);
		 	$Main[$email][$k] = $i;
	 	}
	 	if(!empty($email)) {
		 	unset($email);
	 	}
 	}
 	
 	$SearchTommorowExpire = Objects::getExpire( 'SEARCH' , 1 );
 	if(!empty($SearchTommorowExpire)) {
	 	foreach($SearchTommorowExpire as $k=>$i) {
		 	$email = User::getField('email' , $i['uid']);
		 	$Search[$email][$k] = $i;
	 	}
	 	if(!empty($email)) {
		 	unset($email);
	 	}
 	}

/*
 * Przygotowywanie maila
 * -----------------------------------------------
 */
 
 	if(!empty($View)) {
	 	$email_text = Email::getByName('daily-view-expire');
	 	foreach($View as $email=>$array) {
		 	$html[$email] = $email_text;
		 	$objects = '';
		 	foreach($array as $i) {
			 	$objects .= '<p class="well"><b>'.$i['name'].'</b><br/>' . $i['address'] . '<br/>' . $i['postcode'] . ' ' . $i['city'] . '<br/><b>Wyświetlanie ważne do:</b> ' . $i['view_expire'].'</p><hr/>';
			 	$sms_objects = $i['name'];
			 	
		 	}
		 	
		 	$html[$email] = str_replace("[objects]" , $objects, $html[$email]);
		 	$html[$email] = str_replace("[app_url]" , $app_url, $html[$email]);
	 	}
 	}
 	
 	if(!empty($html)) {
	 	$content = file_get_contents($app_path . "include/email_templates/newsletter/template.html");
	 	foreach($html as $email=>$text) {
		 	$subject = "Jutro upływa termin wyświetlania Twojego obiektu";
		 	
		 	$content = str_replace('[subject]' , $subject, $content);
		 	$content = str_replace('[sender-name]' , $config['service_name'], $content);
		 	$content = str_replace('[content]' , $text, $content);
		 	
		 	if( $messages->cronAddMessage([
			 	'subject' => $subject,
			 	'text' => $content,
		 	], $email) == true ) {
			 	Kernel::createLog('cron-daily.log' , '[OK] view_expire objects added');
		 	}
		 	
		 	//
		 	//SMS::getByName('view-expire');
		 	//
	 	}
 	}
 	unset($html);
 	unset($View);
 	unset($content);
 	
 	if(!empty($Main)) {
	 	$email_text = Email::getByName('daily-main-expire');
	 	foreach($Main as $email=>$array) {
		 	$html[$email] = $email_text;
		 	$objects = '';
		 	foreach($array as $i) {
			 	$objects .= '<p class="well"><b>'.$i['name'].'</b><br/>' . $i['address'] . '<br/>' . $i['postcode'] . ' ' . $i['city'] . '<br/><b>Promocja na stronie głównej ważna do:</b> ' . $i['view_expire'].'</p><hr/>';
			 	
		 	}
		 	$html[$email] = str_replace("[objects]" , $objects, $html[$email]);
		 	$html[$email] = str_replace("[app_url]" , $app_url, $html[$email]);
	 	}
 	}
 	
 	if(!empty($html)) {
	 	$content = file_get_contents($app_path . "include/email_templates/newsletter/template.html");
	 	foreach($html as $email=>$text) {
		 	$subject = $email . " - Jutro kończy się promocja na stronie głównej Twojego obiektu";
		 	
		 	$content = str_replace('[subject]' , $subject, $content);
		 	$content = str_replace('[sender-name]' , $config['service_name'], $content);
		 	$content = str_replace('[content]' , $text, $content);
		 	
		 	if( $messages->cronAddMessage([
			 	'subject' => $subject,
			 	'text' => $content,
		 	], $email) == true ) {
			 	Kernel::createLog('cron-daily.log' , '[OK] search_expire objects added');
		 	}
	 	}
 	}
 	
 	unset($html);
 	unset($Main);
 	unset($content);
 	
 	if(!empty($Search)) {
	 	$email_text = Email::getByName('daily-search-expire');
	 	foreach($Search as $email=>$array) {
		 	$html[$email] = $email_text;
		 	$objects = '';
		 	foreach($array as $i) {
			 	$objects .= '<p class="well"><b>'.$i['name'].'</b><br/>' . $i['address'] . '<br/>' . $i['postcode'] . ' ' . $i['city'] . '<br/><b>Wyróżnienie w wyszukiwarce ważne do:</b> ' . $i['view_expire'].'</p><hr/>';
			 	
		 	}
		 	$html[$email] = str_replace("[objects]" , $objects, $html[$email]);
		 	$html[$email] = str_replace("[app_url]" , $app_url, $html[$email]);
	 	}
 	}
 	
 	if(!empty($html)) {
	 	$content = file_get_contents($app_path . "include/email_templates/newsletter/template.html");
	 	foreach($html as $email=>$text) {
		 	$subject = $email . " - Jutro kończy się wyróżnienie w wynikach wyszukiwania Twojego obiektu";
		 	
		 	$content = str_replace('[subject]' , $subject, $content);
		 	$content = str_replace('[sender-name]' , $config['service_name'], $content);
		 	$content = str_replace('[content]' , $text, $content);
	 		
	 		if( $messages->cronAddMessage([
			 	'subject' => $subject,
			 	'text' => $content,
		 	], $email) == true ) {
			 	Kernel::createLog('cron-daily.log' , '[OK] main_expire objects added');
		 	}
	 		
	 	}
 	}
 	
 	unset($html);
 	unset($Search);
 	unset($content);
 
echo 'You have configured Your cron correctly!';
exit;
?> 