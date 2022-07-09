<?php
/**
 * Payments Przelewy24 class
 *
 * @package		Modules
 * @subpackage	Payments/p24
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

class p24 
{
	public static $Error;	
	public static $pos_id;
	public static $crc_key;
	public static $session_id;
	public static $sign;
	public static $post;
	public static $token;
	public static $version = "3.2";
	public static $ip = ['91.216.191.181' , '91.216.191.182' , '91.216.191.183' , '91.216.191.184' , '91.216.191.185'];
	public static $log;

	public static $mode = 'request';
	public static $p24_url = "https://secure.przelewy24.pl/";
	public static $sandbox = true;
	public static $sandbox_url = "https://sandbox.przelewy24.pl/";
	
	
	protected static $table = "p24";
	protected static $debug = true;
	
	public function __construct( $pos_id, $crc_key, $ip )
	{
		global $config, $app_path;
		
		self::$table = $config['db_prefix'] . self::$table;
		
		if(!empty($pos_id)) {
			self::$pos_id = (int) $pos_id;
		}
		
		if(!empty($crc_key)) {
			self::$crc_key = $crc_key;
		}
		
		if(!empty($ip)) {
			self::$ip = $ip;
		} else {
			self::$ip = "195.150.9.37";
		}
		if( $config['p24_testmode'] == "TRUE" ) {
			self::$sandbox = true;
		} else {
			self::$sandbox = false;
		}
		
		self::$log = $app_path . "logs/p24_" . date('d-m-Y'). ".log";
	}
	
	public static function init( $silent = false ) 
	{
		if( $silent == false ) {
			if(empty(self::$pos_id)) {
				throw ('Please define pos_id parametr using Przelewy24::$pos_id');
			}
			if(empty(self::$crc_key)) {
				throw ('Please define crc_key parametr using Przelewy24::$crc_key');
			}
		}
	}
	
	public static function request( $type )
	{
		self::init();
		
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
	
	public static function verify( $type, $data = null )
	{
		self::init( true );
		switch( $type )
		{
			case "SMS":
				return self::verifySMS();
			break;
			
			case "ONLINE":
				return self::verifyONLINE($data);
			break;
		}
	}
	
	public static function url() 
	{
		
		if( self::$sandbox == true ) {
			$url = self::$sandbox_url;
		} else {
			$url = self::$p24_url;
		}
		
		if( self::$mode == "direct" ) {
			$url = $url . "trnDirect";
		} else {
			$url = $url . "trnRequest";
		}
		
		return $url . (isset(self::$token) ? "/" . self::$token : "");
	}
	
	protected static function crc( $session_id, $pos_id, $amount, $currency ) 
	{
		return md5( $session_id . "|" . $pos_id . "|" . $amount . "|" . $currency . "|" . self::$crc_key);
	}
	
	protected static function sign( $session_id, $order_id, $amount, $currency ) 
	{
		return md5( $session_id . "|" . $order_id . "|" . $amount . "|" . $currency . "|" . self::$crc_key);
	}	
		
	public static function call( $func ) 
	{	
		$REQ = array();
		foreach(self::$post as $k=>$v) { 
			$REQ[] = $k . "=" . urlencode($v); 
		}
		$user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
		
		if( self::$sandbox == true ) {
			$url = self::$sandbox_url . $func;
		} else {
			$url = self::$p24_url . $func;
		}
		
		if($ch = curl_init()) {
            if(count($REQ)) {
                curl_setopt($ch, CURLOPT_POST,1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,join("&",$REQ));
            }
        
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			if($result = curl_exec ($ch)) {
				$INFO = curl_getinfo($ch);
				curl_close ($ch);
            
				if($INFO["http_code"]!=200) {
					file_put_contents(APP_PATH . "logs/p24_new.log" , PHP_EOL . "verify method: " . print_r($INFO,true) . PHP_EOL , FILE_APPEND);
                	return array("error"=>200,"errorMessage"=>"call:Page load error (".$INFO["http_code"].")");
            	} else {
                	$RES     = array();
					$X       = explode("&", $result);
					if(!empty($X)) {
						foreach($X as $val) {
	             			$Y           = explode("=", $val);
				 			$RES[trim($Y[0])] = urldecode(trim($Y[1]));
	             		}
				 		return $RES;
			 		}
			 		if(!isset($RES["error"])) return array("error"=>999,"errorMessage"=>"call:Unknown error");
            	}
        	} else {
            	curl_close ($ch);
				return array("error"=>203,"errorMessage"=>"call:Curl exec error");
        	}
        
    	} else {
        	return array("error"=>202,"errorMessage"=>"call:Curl init error");
    	}
	}
	
	public static function register( $data = null ) 
	{
		if(!empty($data)) {
			self::$post = $data;
		}
		
		self::$debug = true;
		self::log( print_r(self::$post, true));
		self::logToDB( self::$post );
		return self::call("trnRegister");
	}
	
	public static function direct( $data ) 
	{
		if(!empty($data)) {
			self::$post = $data;
		}
		
		self::log("Payments::direct > runs...");
		return self::call("trnDirect");
	}
	
	public static function verifyONLINE( $data = null ) 
	{
		if(!empty($data)) {
			self::$post = $data;
		} else {
			global $request;
			self::$data = $request->post;
		}
		self::$post['p24_sign'] = self::sign(
			self::$post['p24_session_id'], 
			self::$post['p24_order_id'], 
			self::$post['p24_amount'], 
			self::$post['p24_currency']
		);
		
		return self::call("trnVerify");
	}
	
	public static function registerSession()
	{
		if(empty($_COOKIE['p24_session'])) {
			session_regenerate_id();
			$sid = md5(session_id() . "|" . Kernel::getIp());
			setcookie('p24_session' , $sid , time() + (60 * 15) , "/");
		} else {
			$sid = $_COOKIE['p24_session'];
		}
		if( self::checkIsSessionRegistered( $sid ) == true ) {
			session_regenerate_id();
			$sid = md5(session_id() . "|" . Kernel::getIp());
			setcookie('p24_session' , $sid , time() + (60 * 15) , "/");
		}
		
		return $sid;
	}
	
	public static function log( $value ) 
	{
		if( self::$debug == true ) {
			global $app_path;
			file_put_contents($app_path . "logs/p24.log", $value . PHP_EOL, FILE_APPEND);
		}
	}
	
	public static function findTransaction( $p24_session_id )
	{
		$Row = Db::row("*" , self::$table , "WHERE p24_session_id='" . $p24_session_id . "'");
		if(!empty($Row)) {
			return $Row['order_id'];
		}
	}
	
	public static function checkIsSessionRegistered( $p24_session_id )
	{
		return Db::check( self::$table , "p24_session_id='" . $p24_session_id . "'");
	}
	
	public static function getBySession( $sid )
	{
		return Db::row("control" , self::$table , "WHERE p24_session_id='" . $sid . "'");
	}
	
	public static function logToDB( $data )
	{
		$result = Db::insert( self::$table , "null,
		'" . $data['p24_number_1'] . "',
		'" . $data['p24_merchant_id'] . "',
		'" . $data['p24_pos_id'] . "',
		'" . $data['p24_session_id'] ."',
		'" . $data['p24_amount'] . "',
		'" . (isset($data['p24_currency']) ? $data['p24_currency'] : "PLN") . "',
		" . (!empty($data['p24_order_id']) ? "'" . $data['p24_order_id'] . "'" : "NULL") . ",
		'PENDING'");
		
		if($result == true) {
			self::log('logtodb success');
			return true;
		} else {
			self::log( 'logtodb false ' . Db::error());
			return false;
		}
		
	}
	
	protected static $errors = [
		'1' => 'Proces rejestracji w systemie przelewy24 nie został ukończony, wykonywanie płatności jest nie możliwe. Więcej informacji uzyskasz kontaktując się z <a href="http://przelewy24.pl" target="_blank">przelewy24.pl</a>'
	];
	
	public static function showError( $error_code )
	{
		if(!empty(self::$errors[$error_code])) {
			return self::$errors[$error_code];
		} else {
			return "Wystąpił błąd podczas rejestracji transakcji w systemie przelewy24 - skontaktuj się z właścicielem tego serwisu";
		}
	}
}	