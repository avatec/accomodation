<?php
use \Core\Backend\Navigation as Navigation;

/**
  * Klasa Text SMS dla Avatec Framework
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

class SMS {

	public static $Error;
	protected static $table = "texts_sms";

	public function __construct()
	{
		LA::load('system');
		LA::load('cms');

		global $config;

		Navigation::submenu('config' , 'Treści SMS' , 'system/sms/list/');
		//Kernel::addAdminMenu("config", "Treści SMS", "admin/system/sms/list/", null, true);

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
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , La::get('cms','error_form_return_error') , self::$Error);
			return false;
		}

		$result = Db::insert(self::$table , "null,
		'" . $request->post['name'] . "',
		'" . addslashes($request->post['value']) . "',
		'" . addslashes($request->post['description']) . "'");

		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , La::get('cms','add_notice_success'));
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , La::get('cms','error_db_return_error') , self::$Error);
			return false;
		}
	}

	public function save( $id )
	{
		global $request;

		$this->verify();
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , La::get('cms','error_form_return_error') , self::$Error);
			return false;
		}

		$result = Db::save(self::$table, "name = '" . $request->post['name'] . "',
		value = '" . addslashes($request->post['value']) . "',
		description = '" . addslashes($request->post['description']) . "'" , "id='".$id."'");

		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , La::get('cms','update_notice_success'));
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , La::get('cms','error_db_return_error') , self::$Error);
			return false;
		}
	}

	public function delete( $id )
	{
		if( Db::check( self::$table , "id='" . $id ."'") == true) {
			Db::delete( self::$table , "id= '" . $id . "'");
			Kernel::setMessage("NOTICE" , La::get('cms','delete_notice_success'));
			return true;
		} else {
			Kernel::setMessage("ERROR" , La::get('cms','error_db_return_error') , self::$Error);
			return false;
		}
	}

}
