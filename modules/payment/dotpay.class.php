<?php
/**
 * Payments dotpay class
 *
 * @package		Modules
 * @subpackage	Payments/Dotpay
 * @author		Grzegorz Miskiewicz <biuro@avatec.pl>
 * @license		Property license 
 *
 * <p>Ten produkt jest licencjonowany
 * Możesz modyfikować te pliki jednak nie możesz usuwać oryginalnych komentarzy
 * w szczególności informacji o autorze tego oprogramowania</p>
 *
 * <p>W przypadku indywidualnego modyfikowania tego pliku autor nie ponosi
 * odpowiedzialności za wszelkie błędy i/lub wady tego oprogramowania.</p>
 */

class Dotpay 
{
	protected static $id;
	protected static $pin;
	protected static $ip;
	protected static $log;
	protected static $table = "dotpay_sms";
	
	public static $url;
	public static $testmode = false;
	public static $api_version = "dev";
	
	public static $Error;
	
	public function __construct( $id, $pin, $ip )
	{
		global $config, $app_path;
		
		self::$table = $config['db_prefix'] . self::$table;
		
		if(!empty($id)) {
			self::$id = $id;
		}
		
		if(!empty($pin)) {
			self::$pin = $pin;
		}
		
		if(!empty($ip)) {
			self::$ip = $ip;
		} else {
			self::$ip = "195.150.9.37";
		}
		
		if( $config['dotpay_testmode'] == "TRUE" ) {
			self::$url = "https://ssl.dotpay.pl/test_payment/";
			self::$testmode = true;
		} else {
			self::$url = "https://ssl.dotpay.pl/t2/";
			self::$testmode = false;
		}
		
		self::$log = $app_path . "logs/dotpay_" . date('d-m-Y'). ".log";
	}
	
	public static function verifyChk( $data )
	{
		global $config, $request;
		
		$sign= $config['dotpay_pin'] . 
		(!empty(self::$id) ? self::$id : null) .
		(!empty($data['operation_number']) ? $data['operation_number'] : '') .
		(!empty($data['operation_type']) ? $data['operation_type'] : '') .
		(!empty($data['operation_status']) ? $data['operation_status'] : '') .
		(!empty($data['operation_amount']) ? $data['operation_amount'] : '') .
		(!empty($data['operation_currency']) ? $data['operation_currency'] : '') .
		(!empty($data['operation_withdrawal_amount']) ? $data['operation_withdrawal_amount'] : '') .
		(!empty($data['operation_commission_amount']) ? $data['operation_commission_amount'] : '') .
		(!empty($data['is_completed']) ? $data['is_completed'] : '') .
		(!empty($data['operation_original_amount']) ? $data['operation_original_amount'] : '') .
		(!empty($data['operation_original_currency']) ? $data['operation_original_currency'] : '') .
		(!empty($data['operation_datetime']) ? $data['operation_datetime'] : '') .
		(!empty($data['operation_related_number']) ? $data['operation_related_number'] : '') .
		(!empty($data['control']) ? $data['control'] : '') .
		(!empty($data['description']) ? $data['description'] : '') .
		(!empty($data['email']) ? $data['email'] : '') .
		(!empty($data['p_info']) ? $data['p_info'] : '') .
		(!empty($data['p_email']) ? $data['p_email'] : null) .
		(!empty($data['credit_card_issuer_identification_number']) ? $data['credit_card_issuer_identification_number'] : '') .
		(!empty($data['credit_card_masked_number']) ? $data['credit_card_masked_number'] : '') .
		(!empty($data['credit_card_brand_codename']) ? $data['credit_card_brand_codename'] : '') .
		(!empty($data['credit_card_brand_code']) ? $data['credit_card_brand_code'] : '') .
		(!empty($data['credit_card_id']) ? $data['credit_card_id'] : '') .
		(!empty($data['channel']) ? $data['channel'] : '') .
		(!empty($data['channel_country']) ? $data['channel_country'] : '') .
		(!empty($data['geoip_country']) ? $data['geoip_country'] : '');
		
		$signature = hash('sha256', $sign);
		return $signature;
	}
	
	public static function GenerateChk($ParametersArray, $ret = null) 
	{
		global $config;

		$ParametersArray['id'] = self::$id;
		$ChkParametersChain = self::$pin.
		//(isset($ParametersArray['api_version']) ? $ParametersArray['api_version'] : ''). 
		//(isset($ParametersArray['charset']) ? $ParametersArray['charset'] : ''). 
		(isset($ParametersArray['lang']) ? $ParametersArray['lang'] : ''). 
		(isset($ParametersArray['id']) ? $ParametersArray['id'] : ''). 
		(isset($ParametersArray['amount']) ? $ParametersArray['amount'] : ''). 
		//(isset($ParametersArray['currency']) ? $ParametersArray['currency'] : ''). 
		(isset($ParametersArray['description']) ? $ParametersArray['description'] : ''). 
		(isset($ParametersArray['control']) ? $ParametersArray['control'] : ''). 
		(isset($ParametersArray['channel']) ? $ParametersArray['channel'] : ''). 
		(isset($ParametersArray['credit_card_brand']) ? $ParametersArray['credit_card_brand'] : ''). 
		(isset($ParametersArray['ch_lock']) ? $ParametersArray['ch_lock'] : ''). 
		(isset($ParametersArray['channel_groups']) ? $ParametersArray['channel_groups'] : '').
		(isset($ParametersArray['onlinetransfer']) ? $ParametersArray['onlinetransfer'] : ''). 
		(isset($ParametersArray['url']) ? $ParametersArray['url'] : ''). 
		(isset($ParametersArray['type']) ? $ParametersArray['type'] : ''). 
		(isset($ParametersArray['buttontext']) ? $ParametersArray['buttontext'] : ''). 
		(isset($ParametersArray['urlc']) ? $ParametersArray['urlc'] : ''). 
		(isset($ParametersArray['firstname']) ? $ParametersArray['firstname'] : ''). 
		(isset($ParametersArray['lastname']) ? $ParametersArray['lastname'] : ''). 
		(isset($ParametersArray['email']) ? $ParametersArray['email'] : ''). 
		(isset($ParametersArray['street']) ? $ParametersArray['street'] : ''). 
		(isset($ParametersArray['street_n1']) ? $ParametersArray['street_n1'] : ''). 
		(isset($ParametersArray['street_n2']) ? $ParametersArray['street_n2'] : ''). 
		(isset($ParametersArray['state']) ? $ParametersArray['state'] : ''). 
		(isset($ParametersArray['addr3']) ? $ParametersArray['addr3'] : ''). 
		(isset($ParametersArray['city']) ? $ParametersArray['city'] : ''). 
		(isset($ParametersArray['postcode']) ? $ParametersArray['postcode'] : ''). 
		(isset($ParametersArray['phone']) ? $ParametersArray['phone'] : ''). 
		(isset($ParametersArray['country']) ? $ParametersArray['country'] : ''). 
		(isset($ParametersArray['code']) ? $ParametersArray['code'] : ''). 
		(isset($ParametersArray['p_info']) ? $ParametersArray['p_info'] : ''). 
		//(isset($ParametersArray['p_email']) ? $ParametersArray['p_email'] : ''). 
		(isset($ParametersArray['n_email']) ? $ParametersArray['n_email'] : ''). 
		(isset($ParametersArray['expiration_date']) ? $ParametersArray['expiration_date'] : ''). 
		(isset($ParametersArray['deladdr']) ? $ParametersArray['deladdr'] : ''). 
		(isset($ParametersArray['recipient_account_number']) ? $ParametersArray['recipient_account_number'] : ''). 
		(isset($ParametersArray['recipient_company']) ? $ParametersArray['recipient_company'] : ''). 
		(isset($ParametersArray['recipient_first_name']) ? $ParametersArray['recipient_first_name'] : ''). 
		(isset($ParametersArray['recipient_last_name']) ? $ParametersArray['recipient_last_name'] : ''). 
		(isset($ParametersArray['recipient_address_street']) ? $ParametersArray['recipient_address_street'] : '').
		(isset($ParametersArray['recipient_address_building']) ? $ParametersArray['recipient_address_building'] : '').
		(isset($ParametersArray['recipient_address_apartment']) ? $ParametersArray['recipient_address_apartment'] : '').
		(isset($ParametersArray['recipient_address_postcode']) ? $ParametersArray['recipient_address_postcode'] : '').
		(isset($ParametersArray['recipient_address_city']) ? $ParametersArray['recipient_address_city'] : ''). 
		(isset($ParametersArray['application']) ? $ParametersArray['application'] : ''). 
		(isset($ParametersArray['application_version']) ? $ParametersArray['application_version'] : ''). 
		(isset($ParametersArray['warranty']) ? $ParametersArray['warranty'] : ''). 
		(isset($ParametersArray['bylaw']) ? $ParametersArray['bylaw'] : ''). 
		(isset($ParametersArray['personal_data']) ? $ParametersArray['personal_data'] : ''). 
		(isset($ParametersArray['credit_card_number']) ? $ParametersArray['credit_card_number'] : ''). 
		(isset($ParametersArray['credit_card_expiration_date_year']) ? $ParametersArray['credit_card_expiration_date_year'] : ''). 
		(isset($ParametersArray['credit_card_expiration_date_month']) ? $ParametersArray['credit_card_expiration_date_month'] : ''). 
		(isset($ParametersArray['credit_card_security_code']) ? $ParametersArray['credit_card_security_code'] : ''). 
		(isset($ParametersArray['credit_card_store']) ? $ParametersArray['credit_card_store'] : ''). 
		(isset($ParametersArray['credit_card_store_security_code']) ? $ParametersArray['credit_card_store_security_code'] : ''). 
		(isset($ParametersArray['credit_card_customer_id']) ? $ParametersArray['credit_card_customer_id'] : ''). 
		(isset($ParametersArray['credit_card_id']) ? $ParametersArray['credit_card_id'] : ''). 
		(isset($ParametersArray['blik_code']) ? $ParametersArray['blik_code'] : ''). 
		(isset($ParametersArray['credit_card_registration']) ? $ParametersArray['credit_card_registration'] : ''). 
		(isset($ParametersArray['recurring_frequency']) ? $ParametersArray['recurring_frequency'] : ''). 
		(isset($ParametersArray['recurring_interval']) ? $ParametersArray['recurring_interval'] : ''). 
		(isset($ParametersArray['recurring_start']) ? $ParametersArray['recurring_start'] : ''). 
		(isset($ParametersArray['recurring_count']) ? $ParametersArray['recurring_count'] : '');
		(isset($ParametersArray['surcharge_amount']) ? $ParametersArray['surcharge_amount'] : '').
		(isset($ParametersArray['surcharge']) ? $ParametersArray['surcharge'] : '').
		(isset($ParametersArray['ignore_last_payment_channel']) ? $ParametersArray['ignore_last_payment_channel'] : '');
					
		$ChkValue = hash('sha256',$ChkParametersChain);

		if( $ret == 'chk' ) {
			return $ChkValue;
		} else {
			$RedirectionCode = '<form action="' . self::$url . '" method="POST" id="dotpay_redirection_form">' . PHP_EOL;
			foreach($ParametersArray as $key => $value) {
				$RedirectionCode .= '<input name="'.$key.'" value="'.$value.'" type="hidden"/>' . PHP_EOL;
			}
			$RedirectionCode .= '<input name="chk" value="' . $ChkValue . '" type="hidden"/>'.PHP_EOL . '</form>' . PHP_EOL; 
			//$RedirectionCode .= '<button id="dotpay_redirection_button" type="submit" form="dotpay_redirection_form" value="Submit">Confirm and Pay</button>' . PHP_EOL;
			return $RedirectionCode;
		}
	}
	
	protected static function log_add_sms( $o = null )
	{
		global $request;
		
		if(is_null($o)) {
			die("Dotpay::log_add_sms need option parametr to be pass");
		}
		
		if(isset(User::$user['id'])) {
			$uid = User::$user['id'];
		} else {
			$uid = 0;
		}
		
		$result = Db::insert( self::$table , "null,
		'" . $uid . "',
		'" . $request->post['object_id'] . "',
		" . (!empty($request->post['special_id']) ? "'" . $request->post['special_id'] . "'" : "NULL") . ",
		'" . $request->post['pid'] . "',
		NOW(),
		'" . $o['text'] . "',
		'" . (($o['valid'] == true) ? "TRUE" : "FALSE") . "',
		" . (!is_null($o['expire_date']) ? "'" . $o['expire_date'] . "'" : "NULL") . ",
		" . (!is_null($o['code']) ? "'" . $o['code'] . "'" : "NULL"));
		
		if($result == true) {
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
			return false;
		}
		
	}
	
	public static function request( $type )
	{
		switch( $type )
		{
			case "SMS":
				return self::requestSMS();
			break;
			
			case "ONLINE":
				return self::requestONLINE();
			break;
		}
	}
	
	private static function requestONLINE()
	{
		
	}
	
	private static function requestSMS()
	{
		global $config, $app_path, $request;
		
		if(empty($request->post['sms_code'])) {
			return false;
		}
		
		if(!empty($request->post['sms_text'])) {
			$text = explode("." , $request->post['sms_text']);
			
			if(!empty($text['1'])) {
				$code = $text['1'];
			} else {
				self::$Error = "Nie podano w formularzu sms_text";
				return false;
			}
		}
		
		self::log( ':: --------------------------------------------- ::' );
		self::log( ':: New request ' . date('H:i:s') . ' from ip: ' . Kernel::getIp() . '    ::' );
		self::log( ':: SMS Payment                                   ::' );
		self::log( ':: --------------------------------------------- ::' );
		
		$dotpay_id 			= $config['dotpay_id'];
		$dotpay_type		= 'sms';
		$sms_code			= $request->post['sms_code'];
		$delete 			= false;
		$dotpay_url 		= 'http://dotpay.pl/check_code.php?id=' . $dotpay_id . '&code=' . $code . '&type=' . $dotpay_type . '&del=' . $delete . '&check=' . $sms_code;
		
		$result = file_get_contents( $dotpay_url );
		if( $result == false) {
			self::log( '[FAIL] server ' . $dotpay_url . ' returns empty response' );
			self::$Error = "wystąpił nieoczekiwany błąd - być może serwer dotpay jest tymczasowo nieaktywny";
			return false;
		} else {
			self::log( '[OK] server receive query' );
			$response = explode(PHP_EOL , $result);
			if(!empty($response)) {
				$valid = $response['0'];
				$expire_time = (!empty($response['1']) ? $response['1'] : null);
				$code = (!empty($response['2']) ? $response['2'] : null);
			}
			
			self::log_add_sms([
				'text' => $sms_code,
				'valid' => (($valid == 1) ? true : false),
				'expire_date' => (isset($expire_time) ? date('Y-m-d H:i:s' , strtotime('+' . $expire_time . ' seconds')) : null),
				'code' => $code
			]);
			
			if((isset($valid)) && ($valid == true)) {
				self::log( '[OK] code accepted' );
				return true;
			} else {
				self::log( '[OK] code refused' );
				self::$Error = "kod został już wykorzystany";
				return false;
			}
		}
	}
	
	public static function verify( $type )
	{
		switch( $type )
		{
			case "SMS":
				return self::verifySMS();
			break;
			
			case "ONLINE":
				return self::verifyONLINE();
			break;
		}
	}
	
	
	private static function verifyONLINE()
	{
		global $request;
		
		self::log( ':: --------------------------------------------- ::' );
		self::log( ':: New request ' . date('H:i:s') . ' from ip: ' . Kernel::getIp() . '    ::' );
		self::log( ':: POST => ' . PHP_EOL . print_r($request->post, true) . PHP_EOL );
		
		$chk = self::verifyCHK( $request->post );
		if( $chk == $request->post['signature']) {
			self::log( ':: [OK] signature correct' . PHP_EOL );
			$status_transakcji = $request->post['operation_status'];
		} else {
			self::log( ':: [ERROR] signature incorrect' . PHP_EOL . '(' . $chk . ' = ' . $request->post['signature'] . ')');
			self::log( ':: --------------------------------------------- ::' . PHP_EOL );
			die("ERROR SIGNATURE");
		}
		
		switch ($status_transakcji) 
		{
			case "new": $status_transakcji_opis = "NOWA"; break;
		    case "completed": $status_transakcji_opis = "WYKONANA"; break;
		    case "rejected": $status_transakcji_opis = "ODRZUCONA"; break;
		    case "refund": $status_transakcji_opis = "REKLAMACJA"; break;
		}
		trim($status_transakcji_opis);
		self::log( '[INFO] transaction status: ' . $status_transakcji_opis );
		
		
		self::log( '[OK] Verification pass returning: ' . $status_transakcji );
		self::log( ':: --------------------------------------------- ::' . PHP_EOL );
		switch($status_transakcji)
		{
			case "new":
				return "NEW";
			break;
				
			case "completed":
				return "CONFIRM";
			break;
				
			case "rejected":
				return "REFUSED";
			break;
				
			case "refund":
				return "CANCEL";
			break;
		}
		
	}
	
	private static function verifySMS()
	{
		
	}
	
	public static function log( $text )
	{
		global $config;
		if( $config['payment_create_logs'] == "TRUE" ) {
			file_put_contents(self::$log, $text . PHP_EOL, FILE_APPEND);
		}
		file_put_contents(self::$log, $text . PHP_EOL, FILE_APPEND);
	}
	
	public static function readErrorCode( $code )
	{
		$ErrorCode = [
			"err01" => "nieprawidłowe wywołanie skryptu",
			"err01" => "nie uzyskano od sklepu potwierdzenia odebrania odpowiedzi autoryzacyjnej",
			"err02" => 'nie uzyskano odpowiedzi autoryzacyjnej',
			"err03" => "To zapytanie było już przetwarzane",
			"err04" => "Zapytanie autoryzacyjne niekompletne lub niepoprawne",
			"err05" => "Nie udało się odczytać konfiguracji sklepu internetowego",
			"err06" => "Nieudany zapis zapytania autoryzacyjnego",
			"err07" => "Inna osoba dokonuje płatności",
			"err08" => "Nieustalony status połączenia ze sklepem.",
			"err09" => "Przekroczono dozwoloną liczbę poprawek danych.",
			"err10" => "Nieprawidłowa kwota transakcji!",
			"err49" => "Zbyt wysoki wynik oceny ryzyka transakcji przeprowadzonej przez PolCard.",
			"err51" => "Nieprawidłowe wywołanie strony",
			"err52" => "Błędna informacja zwrotna o sesji!",
			"err53" => "Błąd transakcji !",
			"err54" => "Niezgodność kwoty transakcji !",
			"err55" => "Nieprawidłowy kod odpowiedzi !",
			"err56" => "Nieprawidłowa karta",
			"err57" => "Niezgodność flagi TEST !",
			"err58" => "Nieprawidłowy numer sekwencji !",
			"err101" => "Błąd wywołania strony. W żądaniu transakcji brakuje któregoś z wymaganych parametrów lub pojawiła się niedopuszczalna wartość.",
			"err102" => "Minął czas na dokonanie transakcji",
			"err103" => "Nieprawidłowa kwota przelewu",
			"err104" => "Transakcja oczekuje na potwierdzenie.",
			"err105" => "Transakcja dokonana po dopuszczalnym czasie",
			"err161" => "Żądanie transakcji przerwane przez użytkownika. Klient przerwał procedurę płatności wybierając przycisk 'Powrót' na stronie wyboru formy płatności.",
			"err162" => "Żądanie transakcji przerwane przez użytkownika. Klient przerwał procedurę płatności wybierając przycisk 'Rezygnuj' na stronie z instrukcją płatności."
		];
		
		return $ErrorCode[$code];
	}	
}