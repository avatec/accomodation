<?php
use \Core\Backend\Navigation as Navigation;

/**
  * Klasa Email dla Avatec Framework
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

class Emails {

	public static $Error;
	protected static $table = "emails";

	public static $types = [
		[ "id" => "HTML", 	"name" => "treść HTML" 	],
		[ "id" => "PLAIN",	"name" => "treść PLAIN" ]
	];

	public function __construct()
	{
		global $config;

		Navigation::submenu('config' , 'Treści e-mail' , 'system/emails/list/');
		//Kernel::addAdminMenu("config", "Treści e-mail", "admin/system/emails/list/", null, true);

		if(isset($_SESSION['lng']['code'])) {
			$langCode = $_SESSION['lng']['code'];
		} else {
			$langCode = "pl";
		}

		self::$table = self::$table . "_" . $langCode;
		self::$table = $config['db_prefix'] . self::$table;
	}

	public function get( $id = null, $name = false )
	{
		if(is_null($id)) {
			return Db::exec("*" , self::$table , "ORDER BY name");
		} else {
			if( $name ==true ) {
				$result = Db::row("*" , self::$table , "WHERE name='" . $id . "'");
			} else {
				$result = Db::row("*" , self::$table , "WHERE id='" . $id . "'");
			}
			$result['edit'] = true;
			$result['value'] = stripslashes(html_entity_decode($result['value']));
			$result['description'] = stripslashes(html_entity_decode($result['description']));
			return $result;
		}
	}

	public static function getByName( $name )
	{
		if(!isset($name)) {
			trigger_error("Emails::getByName - parametr is required");
		}
		$result = Db::row("name,value" , self::$table , "WHERE name='" . $name . "'");
		$value = stripslashes(html_entity_decode($result['value']));
		return html_entity_decode(stripslashes($value));
	}

	public function verify()
	{
		$request = new Request();

		if(empty($request->post['name'])) {
			self::$Error[] = "musisz wprowadzić nazwę";
		}

		if(empty($request->post['value'])) {
			self::$Error[] = "musisz wprowadzić treść";
		}

		if(empty($request->post['description'])) {
			self::$Error[] = "musisz wprowadzić opis dla pola";
		}

		if(empty($request->post['type'])) {
			self::$Error[] = "musisz wybrać typ tekstu";
		}
	}

	public function add()
	{
		$request = new Request();

		$this->verify();
		if(is_array(self::$Error)) {
			return false;
		}

		$result = Db::insert(self::$table , "null,
		'" . $request->post['name'] . "',
		'" . addslashes($request->post['value']) . "',
		'" . addslashes($request->post['description']) . "',
		'" . $request->post['type'] . "'");

		if(!empty($result)) {
			return true;
		} else {
			return false;
		}
	}

	public function save( $id )
	{
		$request = new Request();

		$this->verify();
		if(is_array(self::$Error)) {
			return false;
		}

		$result = Db::save(self::$table, "name = '" . $request->post['name'] . "',
		value = '" . addslashes($request->post['value']) . "',
		description = '" . addslashes($request->post['description']) . "',
		type = '" . $request->post['type'] . "'" , "id='".$id."'");

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
}
