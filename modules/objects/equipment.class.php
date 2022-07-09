<?php
use \Core\Backend\Navigation as Navigation;

/**
 * Objects equipment class
 *
 * @package		Modules
 * @subpackage	Objects/Equipment
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

class ObjectsEquipment {

	static protected $table = "equipment";
	static protected $nl_table;
	public static $Error;
	static protected $equipments;
	static private $search_query;

	public function __construct()
	{
		global $config;

		Navigation::submenu('objects' , 'Wyposażenie', "objects/equipment/list/");
		//Kernel::addAdminMenu("objects", "Wyposażenie", "admin/objects/equipment/list/", null, true);

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
			self::$search_query[] = "name LIKE '%" . $request->get['q'] . "%'";
		}
	}

	public static function getName( $id, $as_html = false )
	{
		if(empty(self::$equipments)) {
			self::$equipments = Db::exec("*" , self::$table , "ORDER BY id");
		}

		if(!empty(self::$equipments)) {
			foreach(self::$equipments as $i) {
				if( $id == $i['id'] ) {
					if( $as_html == false ) {
						return $i['name'];
					} else {
						return '<div class="col-md-6"><em class="fa fa-check"></em> ' . $i['name'] . '</div>';
					}
				}
			}
		}
	}

	public function get( $id = NULL, $paginated = false )
	{
		if(is_null($id)) {
			if( $paginated == true ) {
				$this->search();
				Paginate::$perpage = 25;
				Paginate::$query = "SELECT * FROM " . self::$table . " " . (!empty(self::$search_query) ? "WHERE " . implode(" OR " , self::$search_query) : "") . " ORDER BY id";
				return Paginate::make();
			} else {
				return Db::exec("*" , self::$table , "ORDER BY id");
			}
		} else {
			$Result = Db::row("*" , self::$table , "WHERE id='".$id."'");
			$Result['edit'] = true;
			return $Result;
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
			Kernel::setMessage("ERROR" , "Wystąpiły błędy w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		foreach(Language::$avaiable as $lang=>$i) {
			$result = Db::insert( self::$nl_table . $lang , "null,
			'" . $request->post['name'] ."',
			'" . Kernel::rewrite($request->post['name']) ."'");

			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Dodano nową pozycję");
			} else {
				self::$Error = Db::error();
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas w bazie danych podczas operacji::<br/>" . self::$Error);
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
			Kernel::setMessage("ERROR" , "Wystąpiły błędy w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		$result = Db::update( self::$table , "name = '" . $request->post['name'] ."',
		rewrite = '" . Kernel::rewrite($request->post['name']) ."'" , "id='".$id."'");

		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , "Pomyślnie zapisano zmiany");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas w bazie danych podczas operacji::<br/>" . self::$Error);
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
