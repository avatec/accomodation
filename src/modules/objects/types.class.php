<?php
use \Core\Backend\Navigation as Navigation;

/**
 * Objects types class
 *
 * @package		Modules
 * @subpackage	Objects/Types
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

class ObjectsTypes {

	protected static$table = "types";
	public static $Error;

	public $post, $get;

	public function __construct()
	{
		global $config, $request;

		Navigation::submenu('objects' , 'Typy obiektów', "objects/types/list/");
		//Kernel::addAdminMenu("objects", "Typy obiektów", "admin/objects/types/list/", null, true);

		if(isset($_SESSION['lng']['code'])) {
			$langCode = $_SESSION['lng']['code'];
		} else {
			$langCode = "pl";
		}

		self::$table = self::$table . "_" . $langCode;
		self::$table = $config['db_prefix'] . self::$table;

		$this->post = (!empty($request->post) ? $request->post : null);
		$this->get = (!empty($request->get) ? $request->get : null);
	}

	public static function searchByName( $name )
	{
		$r = Db::row("id" , self::$table , "WHERE name LIKE '%" . $name . "%' OR rewrite LIKE '%" . $name . "%'");
		if(empty($r)) {
			return;
		}

		return $r['id'];
	}

	public function get( $id = NULL )
	{
		if(is_null($id)) {
			return Db::exec("*" , self::$table , "ORDER BY id");
		}
		$r = Db::row("*" , self::$table , "WHERE id='" . $id . "'");
		if(empty($r)) {
			return;
		}

		$r['edit'] = true;
		return $r;
	}

	public static function getName( $id )
	{
		$Row = Db::row("*" , self::$table , "WHERE id='" . $id . "'");
		if(!empty($Row)) {
			return $Row['name'];
		} else {
			return "ObjectsTypes ERROR - id not found";
		}
	}

	public static function getSelect()
	{
		return Db::exec("*" , self::$table , "ORDER BY id");
	}

	public function verify()
	{
		if(empty($this->post['name'])) {
			self::$Error[] = "podaj nazwę";
		}
	}

	public function add()
	{
		$this->verify();
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , LA::get('cms','error_form_return_error') , self::$Error);
			return false;
		}

		$result = Db::insert( self::$table , "null,
		'" . $this->post['name'] ."',
		'" . Kernel::rewrite($this->post['name']) ."'");

		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'added_notice_success'));
			return true;
		}

		self::$Error = Db::error();
		Kernel::setMessage("ERROR" , LA::get('cms' , 'error_db_return_error') , self::$Error);
		return false;
	}

	public function save( $id )
	{
		$this->verify();
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , LA::get('cms','error_form_return_error') , self::$Error);
			return false;
		}

		$result = Db::update( self::$table , "name = '" . $this->post['name'] ."',
		rewrite = '" . Kernel::rewrite($this->post['name']) ."'" , "id='".$id."'");

		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'update_notice_success'));
			return true;
		}

		self::$Error = Db::error();
		Kernel::setMessage("ERROR" , LA::get('cms' , 'error_db_return_error') , self::$Error);
		return false;
	}

	public function delete( $id )
	{
		if( Db::check( self::$table , "id='" . $id ."'") == true) {
			Db::delete( self::$table , "id= '" . $id . "'");
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'delete_notice_success'));
			return true;
		}

		self::$Error = Db::error();
		Kernel::setMessage("ERROR" , LA::get('cms' , 'error_db_return_error') , self::$Error);
		return false;
	}
}
