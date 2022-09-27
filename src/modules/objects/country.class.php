<?php
use \Core\Backend\Navigation as Navigation;

/**
 * Objects countrys class
 *
 * @package		Modules
 * @subpackage	Objects/Countrys
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

class ObjectsCountrys {

	static protected $table = "country";
	static protected $nl_table;
	static private $search_query;
	public static $Error;

	public function __construct()
	{
		global $config;

		Navigation::submenu('objects' , 'Kraje', "objects/country/list/");
		//Kernel::addAdminMenu("config", "Kraje", "admin/objects/country/list/", null, true);

		if(isset($_SESSION['lng']['code'])) {
			$langCode = $_SESSION['lng']['code'];
		} else {
			$langCode = "pl";
		}

		self::$nl_table = $config['db_prefix'] . self::$table . "_";
		self::$table = self::$table . "_" . $langCode;
		self::$table = $config['db_prefix'] . self::$table;
	}

	private function search()
	{
		global $request;

		if(!empty($request->get['q'])) {
			self::$search_query[] = "(name LIKE '%" . $request->get['q'] . "%' OR rewrite LIKE '%" . $request->get['q'] . "%')";
		}
	}

	public static function getByBrowserLang()
	{
		$browser_lang = Language::detect();
		$lang = Db::row("*" , self::$table , "WHERE code='" . strtoupper($browser_lang) . "'");
		if(isset($lang['id'])) {
			return $lang['id'];
		}
	}

	public function get( $id = NULL )
	{
		if(is_null($id)) {
			$this->search();
			Paginate::$perpage = 25;
			Paginate::$query = "SELECT * FROM " . self::$table . " " . (!empty(self::$search_query) ? "WHERE " . implode(" OR " , self::$search_query) : "") . " ORDER BY id";
			return Paginate::make();
		} else {
			$Result = Db::row("*" , self::$table , "WHERE id='".$id."'");
			$Result['edit'] = true;
			return $Result;
		}
	}

	public static function getSelect()
	{
		return Db::exec("*" , self::$table , "ORDER BY id");
	}

	public static function getName( $id )
	{
		$Row = Db::row("*" , self::$table , "WHERE id='" . $id . "'");
		if(!empty($Row)) {
			return $Row['name'];
		} else {
			return "ObjectsStates ERROR - id not found";
		}
	}

	public function verify()
	{
		global $request;

		if(empty($request->post['name'])) {
			self::$Error[] = "podaj nazwę";
		}
	}

	public function add()
	{
		global $request;

		$this->verify();
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Znaleziono błędy w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		foreach(Language::$avaiable as $lang=>$i) {
			$result = Db::insert( self::$nl_table . $lang , "null,
			'" . $request->post['name'] ."',
			'" . Kernel::rewrite($request->post['name']) ."',
			'" . $request->post['code'] . "'");

			if($result == true) {
				Kernel::setMessage("NOTICE" , "Dodano nową pozycję dla języka");
			} else {
				self::$Error = Db::error();
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych (".self::$nl_table . $lang."):<br/>" . self::$Error);
			}
		}

		if(!empty(self::$Error)) {
			return false;
		}

		return true;
	}

	public function save( $id )
	{
		global $app_path, $request;

		$this->verify();
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Znaleziono błędy w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		$result = Db::update( self::$table , "name = '" . $request->post['name'] ."',
		rewrite = '" . Kernel::rewrite($request->post['name']) ."',
		code = '" . $request->post['code'] . "'" , "id='".$id."'");

		if($result == true) {
			Kernel::setMessage("NOTICE" , "Pomyślnie zapisano zmiany");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd w bazie danych:<br/>" . self::$Error);
			return false;
		}

	}

	public function delete( $id )
	{
		foreach(Language::$avaiable as $lang=>$i) {
			if( Db::check( self::$nl_table . $lang , "id='" . $id ."'") == true) {
				if( Db::delete( self::$nl_table . $lang , "id= '" . $id . "'") == true ) {
					Kernel::setMessage("NOTICE" , "Pomyślnie usunięto pozycję dla języka " . $lang);
				} else {
					self::$Error = Db::error();
					Kernel::setMessage("ERROR" , "Wystąpił błąd podczas usuwania pozycji " . $lang. ". Najprawdopodobniej wybrana pozycja nie istnieje<br/>" . self::$Error);
				}
			}
		}
		return true;
	}
}
