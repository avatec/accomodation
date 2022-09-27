<?php
use \Core\Backend\Navigation as Navigation;

/**
  * Klasa Promotion dla Avatec Framework
  *
  * @author: Grzegorz Miskiewicz <biuro@avatec.pl>
  * @package: Avatec Framework
  *
  * Ten produkt jest licencjonowany
  * Możesz modyfikować te pliki jednak nie możesz usuwać oryginalnych komentarzy
  * w szczególności informacji o autorze tego oprogramowania
  *
  * W przypadku indywidualnego modyfikowania tego pliku autor nie ponosi
  * odpowiedzialności za wszelkie błędy i/lub wady tego oprogramowania.
 */

class Promotion
{
	protected static $table = "promotion";
	public static $Error;

	public static $Promotion = [
		[ 'id' => 'VIEW', 'name' => 'Wyświetlanie' ],
		[ 'id' => 'SEARCH' , 'name' => 'Wyróżnienie na liście wyszukiwania' ],
		[ 'id' => 'MAIN' , 'name' => 'Wyróżnienie na głównej stronie' ],
		[ 'id' => 'SPECIAL' , 'name' => 'Oferta specjalna' ]
	];

	public static $Type = [
		[ 'id' => 'ONLINE', 'name' => 'Płatność za pomocą systemu płatności online' ],
		[ 'id' => 'SMS' , 'name' => 'Płatność za pomocą kodu SMS' ]
	];

	public function __construct()
	{
		global $config;
		self::$table = $config['db_prefix'] . self::$table;
	}

	public static function getTypeName( $id )
	{
		if(!empty(self::$Type)) {
			foreach(self::$Type as $i) {
				if($i['id'] == $id) {
					return $i['name'];
				}
			}
		}
	}

	public static function getName( $id )
	{
		$Result = Db::row("name" , self::$table , "WHERE id='" . $id . "'");
		if(!empty($Result)) {
			return $Result['name'];
		}
	}

	public static function getAmount( $id, $type )
	{
		$Result = Db::row("*" , self::$table , "WHERE id='" . $id . "'");
		if(!empty($Result)) {
			return $Result['amount_' . $type];
		}
	}

	public static function getDays( $id )
	{
		$Result = Db::row("days" , self::$table , "WHERE id='" . $id . "'");
		if(!empty($Result)) {
			return $Result['days'];
		}
	}

	public static function getTypeOf( $id )
	{
		$Result = Db::row("what" , self::$table , "WHERE id='" . $id . "'");
		if(!empty($Result)) {
			return $Result['what'];
		}
	}

	public static function getSMS( $id )
	{
		return Db::row("amount_sms,sms_number,sms_text" , self::$table , "WHERE id='" . $id . "'");
	}

	public function get( $id = null )
	{
		if(!is_null($id)) {
			$Result = Db::row("*" , self::$table , "WHERE id='" . $id . "'");
			$Result['type'] = explode("," , $Result['type']);
			$Result['edit'] = true;
		} else {
			$Result = Db::exec("*" , self::$table , "");
			if(!empty($Result)) {
				foreach($Result as $k=>$i) {
					$Result[$k]['type'] = explode("," , $i['type']);
				}
			}
		}

		if(!empty($Result)) {
			return $Result;
		}
	}

	public function getByType( $type = null )
	{
		$Result = Db::exec("*" , self::$table , (isset($type) ? "WHERE what='" . $type . "'" : ""));
		if(!empty($Result)) {
			foreach($Result as $k=>$i) {
				$Result[$k]['type'] = explode("," , $i['type']);
			}
		}
		return $Result;
	}

	private function verify()
	{
		global $request;

		if(empty($request->post['what'])) {
			self::$Error[] = "wybierz typ abonamentu";
		}

		if(empty($request->post['name'])) {
			self::$Error[] = "podaj nazwę opcji wyróżnienia";
		}

		if(empty($request->post['type'])) {
			self::$Error[] = "wybierz jeden, lub wiele rodzajów płatności dla tej opcji wyróżnienia";
		} else {
			if(in_array("ONLINE" , $request->post['type']) == true) {
				if(empty($request->post['amount_online'])) {
					self::$Error[] = "podaj kwotę dla płatności przelewem online";
				}
			}
			if(in_array("SMS" , $request->post['type']) == true) {
				if(empty($request->post['amount_sms'])) {
					self::$Error[] = "podaj kwotę dla płatności kodem sms";
				}
				if(empty($request->post['sms_number'])) {
					self::$Error[] = "podaj numer telefonu na który należy wysłać SMS'a";
				}
				if(empty($request->post['sms_text'])) {
					self::$Error[] = "podaj treść SMS jaką należy wysłać, aby uzyskać kod";
				}
				if($request->post['sms_number'] >= 99999) {
					self::$Error[] = "nieprawidłowy numer dla SMS PREMIUM";
				}
			}
		}

		if(empty($request->post['days'])) {
			self::$Error[] = "podaj ilość dni dla tej opcji wyróżnienia";
		}
	}

	public function add()
	{
		global $request;

		$this->verify();
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Wystąpił błąd w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}
		$result = Db::insert( self::$table , "null,
		'" . $request->post['what'] . "',
		'" . $request->post['name'] . "',
		" . (!empty($request->post['description']) ? "'" . $request->post['description'] . "'," : "NULL,") . "
		'" . $request->post['days'] . "',
		'" . implode("," , $request->post['type']) . "',
		'" . str_replace("," , "." , $request->post['amount_online']) . "',
		" . (!empty($request->post['amount_sms']) ? "'" . str_replace("," , "." , $request->post['amount_sms']) . "'," : "NULL,") . "
		" . (!empty($request->post['sms_number']) ? "'" . $request->post['sms_number'] . "'," : "NULL,") . "
		" . (!empty($request->post['sms_text']) ? "'" . $request->post['sms_text'] . "'" : "NULL"));

		if($result == true) {
			Kernel::setMessage("NOTICE" , "Pomyślnie utworzono opcję wyróżnienia");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
			return false;
		}

	}

	public function save( $id )
	{
		global $request;

		$this->verify();
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Wystąpił błąd w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		$result = Db::update( self::$table , "what = '" . $request->post['what'] . "',
		name = '" . $request->post['name'] . "',
		description = " . (!empty($request->post['description']) ? "'" . $request->post['description'] . "'," : "NULL,") . "
		days = '" . $request->post['days'] . "',
		type = '" . implode("," , $request->post['type']) . "',
		amount_online = '" . str_replace("," , "." , $request->post['amount_online']) . "',
		amount_sms = " . (!empty($request->post['amount_sms']) ? "'" . str_replace("," , "." , $request->post['amount_sms']) . "'," : "NULL,") . "
		sms_number = " . (!empty($request->post['sms_number']) ? "'" . $request->post['sms_number'] . "'," : "NULL,") . "
		sms_text = " . (!empty($request->post['sms_text']) ? "'" . $request->post['sms_text'] . "'" : "NULL")  , "id='" . $id . "'");

		if($result == true) {
			Kernel::setMessage("NOTICE" , "Zmiany zostały pomyślnie zapisane");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
			return false;
		}
	}

	public function delete( $id )
	{
		if( Db::delete( self::$table , "id='" . $id . "'") == true) {
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto wybraną opcję wyróżnienia");
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych - prawdopodobnie, wybrana opcja wyróżnienia nie istnieje: " . self::$Error);
		}

		return true;
	}

}
