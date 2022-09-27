<?php
use \Core\Backend\Navigation as Navigation;

/**
 * System Text class
 *
 * @package		Modules
 * @subpackage	System/Text
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


class Text {

	public static $Error;
	protected static $table = "texts";

	public function __construct()
	{
		global $config;

		Navigation::submenu('config' , 'Treści statyczne', "system/text/list/");
		//Kernel::addAdminMenu("config", "Treści statyczne", "admin/system/text/list/", null, true);

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
			trigger_error("Text::getByName - parametr is required");
		}

		$result = Db::row("name,value" , self::$table , "WHERE name='" . $name . "'");
		$value = stripslashes(html_entity_decode($result['value']));
		return html_entity_decode(stripslashes($value));
	}

	public function verify()
	{
		global $request;

		if(empty($request->post['name'])) {
			self::$Error[] = "musisz wprowadzić nazwę";
		}

		if(empty($request->post['value'])) {
			self::$Error[] = "musisz wprowadzić treść";
		}

		if(empty($request->post['description'])) {
			self::$Error[] = "musisz wprowadzić opis dla pola";
		}
	}

	public function add()
	{
		global $request;

		$this->verify();
		if(is_array(self::$Error)) {
			return false;
		}

		$result = Db::insert(self::$table , "null,
		'" . $request->post['name'] . "',
		'" . addslashes($request->post['value']) . "',
		'" . addslashes($request->post['description']) . "'");

		if(!empty($result)) {
			return true;
		} else {
			return false;
		}
	}

	public function save( $id )
	{
		global $request;

		$this->verify();
		if(is_array(self::$Error)) {
			return false;
		}

		$result = Db::save(self::$table, "name = '" . $request->post['name'] . "',
		value = '" . addslashes($request->post['value']) . "',
		description = '" . addslashes($request->post['description']) . "'" , "id='".$id."'");

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
