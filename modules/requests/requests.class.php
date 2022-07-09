<?php
/**
 * @inherit
 */
class Requests {
	
	static protected $table = "requests";
	public static $Error;
	
	public function __construct() {
		global $config;
		
		Kernel::addAdminMenu("requests", "Zapytania o oferty", "admin/requests/list/", "fa-slideshare", null, false);
		
		if(isset($_SESSION['lng']['code'])) {
			$langCode = $_SESSION['lng']['code'];
		} else {
			$langCode = "pl";
		}
		
		self::$table = self::$table . "_" . $langCode;
		self::$table = $config['db_prefix'] . self::$table;
	}
	
	public function countRows() {
		$result = Db::query("SELECT count(id) as count FROM " . self::$table);
		return $result[0]['count'];
	}
	
	public function get( $id = NULL )
	{
		if(is_null($id)) {
			return Db::exec("*" , self::$table , "ORDER BY id");
		} else {
			$Result = Db::row("*" , self::$table , "WHERE id='".$id."'");
			$Result['edit'] = true;
			if(isset($Result['description'])) {
				$Result['description'] = html_entity_decode(html_entity_decode($Result['description']));
			}
			return $Result;
		}
	}
	
	public function verify()
	{
		$request = new Request();
		
		if(empty($request->post['name'])) {
			self::$Error[] = "podaj nazwę stanowiska";
		}
		if(empty($request->post['short_description'])) {
			self::$Error[] = "wprowadź krótki opis";
		}
		if(empty($request->post['description'])) {
			self::$Error[] = "wprowadź opis";
		}
		if(empty($request->post['icon'])) {
			self::$Error[] = "podaj kod dla ikony stanowiska";
		}
	}
	
	public function add()
	{
		$request = new Request();
		
		$this->verify();			
		if(is_array(self::$Error)) {
			return false;
		}

		$result = Db::insert( self::$table , "null,
		'" . $request->post['icon'] . "',
		'" . $request->post['name'] ."',
		'" . $request->post['short_description'] ."',
		'" . $request->post['description'] ."'");
		
		if(!empty($result)) {
			return true;
		} else {
			return false;
		}
		
	}
	
	public function save( $id )
	{
		global $app_path;
		
		$request = new Request();
		
		$this->verify();
		if(is_array(self::$Error)) {
			return false;
		}
		
		$result = Db::update( self::$table , "icon = '" . $request->post['icon'] . "',
		name = '" . $request->post['name'] ."',
		short_description = '" . $request->post['short_description'] ."',
		description = '" . $request->post['description'] ."'" , "id='".$id."'");
		
		if(!empty($result)) {
			return true;
		} else {
			return false;
		}
		
	}
	
	public function delete( $id )
	{
		if( Db::check( self::$table , "id='" . $id ."'") == true) {
			Db::delete( self::$table , "id= '" . $id . "'");
			return true;
		} else {
			return false;
		}
	}
	
	public function send() {
		global $app_url, $request, $config;
		
		$cv_file = $_FILES['cv'];
		$ml_file = $_FILES['list'];
		
		if(empty($request->post['name'])) { self::$Error[] = Language::get("cms","error_full_name"); }
		if(empty($request->post['email'])) { self::$Error[] = Language::get("cms","error_email"); }
		if(empty($request->post['phone'])) { self::$Error[] = Language::get("cms","error_phone"); }
		//if(empty($request->post['position'])) { self::$Error[] = Language::get("cms","error_position"); }
		if(empty($cv_file)) { self::$Error[] = Language::get("cms","error_cv_file"); }
		
		if( is_array(self::$Error)) {
			return false;
		}
		
		$count = count($_FILES);
		
		$text = "Witaj,<br><br><b>Na stronie " . substr(str_replace("http://" , "" , $app_url),0,-1) . " wysłano formularz CV w dziale Kariera.</b><br>" . PHP_EOL .
		"<small>Wiadomość została nadana dnia " . date('Y-m-d') . " o godz. " . date('H:i:s') . PHP_EOL . PHP_EOL . "</small><br><br>" .
		(isset($request->post['position']) ? "<b>Dotyczy stanowiska:</b> <br> -" . $request->post['position'] . PHP_EOL : "") . "<br>" .
		"<b>Wprowadzone dane: </b>" . PHP_EOL .  "<br>" .
		"- imię i nazwisko: " . $request->post['name'] . PHP_EOL . "<br>" .
		"- adres e-mail: " . $request->post['email'] . PHP_EOL .  "<br>" .
		"- numer telefonu: " . $request->post['phone'] . PHP_EOL .  "<br>" .
		"<b>Wprowadzona wiadomość: </b>" . PHP_EOL . "<br>" .
		$request->post['msg'] . PHP_EOL . PHP_EOL .  "<br><br>" .
		"<small>-----------------------------------------------------------------------<br>W załączniku tej wiadomości " . ($count == 1 ? "znajduje się jeden plik</small>" : "znajdują się załączone pliki</small>") . PHP_EOL . PHP_EOL;
		
		
		
		Mail::$address = $config['default_email'];
		Mail::$name = $config['default_email'];
		Mail::$subject = "CV - " . $request->post['name'];
		Mail::$bbc = $request->post['email'];
		Mail::attachment($cv_file);
		Mail::attachment($ml_file);
		
		Mail::$text = $text;
		$Result = Mail::send();
		
		if($Result == true) {
			return true;
		} else {
			self::$Error[] = "Wystąpił błąd podczas wysyłania wiadomości.<br/>" .Mail::$error;
			return false;
		}
	}
	
}