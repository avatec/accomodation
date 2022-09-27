<?php
use \Core\Backend\Navigation as Navigation;

/**
 * Business Contrahents class
 *
 * @package		Modules
 * @subpackage	Business/Contrahents
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

class BusinessContrahents {
	protected static $table = "business_contrahent";
	public static $Error;

	public function __construct()
	{
		global $config;
		// Kernel::addAdminMenu("business", "Firma", "#", "fa-list", null, false);
		// Kernel::addAdminMenu("business", "Kontrahenci", "admin/business/contrahent/list/", null, "business", false);

		Navigation::menu(3, 'business' , 'Firma' , null, 'fa-list');
		Navigation::submenu('business' , 'Kontrahenci' , 'business/contrahent/list/');

		self::$table = $config['db_prefix'] . self::$table;
	}

	private function search()
	{
		global $request;
		if(!empty($request->get['q'])) {
			$qry[] = "pin='" . $request->get['q'] . "'";
			$qry[] = "(name LIKE '%" . $request->get['q'] ."%' OR person_name LIKE '%" . $request->get['q'] . "%')";
		}

		if(!empty($qry)) {
			return " WHERE " . implode(" OR " , $qry);
		}
	}

	public function get( $id = NULL )
	{
		if(is_null($id)) {
			$query = $this->search();
			Paginate::$perpage = 15;
			Paginate::$query = "SELECT * FROM " . self::$table . (!empty($query) ? $query : "") . " ORDER BY id DESC";
			$Result = Paginate::make();

			if(!empty($Result)) {
				foreach($Result as $k=>$i) {
					$Result[$k]['invoices'] = BusinessInvoice::countInvoicesByContrahent( $i['id'] );
				}
				if(!empty($Result)) {
					return $Result;
				}
			}
		} else {
			$Result = Db::row("*" , self::$table , "WHERE id='".$id."'");
			$Result['edit'] = true;
			return $Result;
		}
	}

	public static function _getSelect()
	{
		$Array = Db::exec("*" , self::$table , "");
		if(!empty($Array)) {
			//$Result[0] = array("id" => "", "name" => "");
			foreach($Array as $k=>$i) {
				$kk = $k+1;
				$Result[$kk] = array("id" => $i['id'], "name" => $i['pin'] . " [" . $i['name'] . ", " . $i['address'] . ", " . $i['postcode'] . " " . $i['city'] . "]");
			}
			return $Result;
		}
	}

	public static function _get()
	{
		$Array = Db::exec("*" , self::$table , "ORDER BY id");
		return $Array;
	}

	public static function _getValue( $id, $table )
	{
		$Row = Db::row("*" , self::$table , "WHERE id='" . $id . "'");
		if(!empty($Row)) {
			return html_entity_decode(html_entity_decode($Row[$table]));
		}
	}

	public static function _getName( $id )
	{
		$Row = Db::row("*" , self::$table, "WHERE id='" . $id . "'");
		return $Row['name'];
	}

	public function verify()
	{
		global $request;

		if(empty($request->post['pin'])) {
			//self::$Error[] = "wprowadź numer nip firmy";
		}
		if(empty($request->post['name'])) {
			self::$Error[] = "wprowadź nazwę firmy";
		}
		if(empty($request->post['address'])) {
			self::$Error[] = "wprowadź adres firmy";
		}
		if(empty($request->post['postcode'])) {
			self::$Error[] = "podaj kod pocztowy";
		}
		if(empty($request->post['city'])) {
			self::$Error[] = "podaj miejscowość";
		}
		if(empty($request->post['country'])) {
			self::$Error[] = "podaj państwo";
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
		'" . $request->post['name'] . "',
		'" . $request->post['address'] ."',
		'" . $request->post['postcode'] ."',
		'" . $request->post['city'] ."',
		'" . $request->post['country'] ."',
		'" . $request->post['person_name'] ."',
		'" . $request->post['person_phone'] ."',
		'" . $request->post['person_email'] ."',
		'" . (!empty($request->post['pin']) ? $request->post['pin'] : "") ."',
		'" . $request->post['notice'] ."'");

		if(!empty($result)) {
			return true;
		} else {
			return false;
		}

	}

	public function save( $id )
	{
		global $app_path, $request;

		$this->verify();
		if(is_array(self::$Error)) {
			return false;
		}

		$result = Db::update( self::$table , "name = '" . $request->post['name'] . "',
		address = '" . $request->post['address'] ."',
		postcode = '" . $request->post['postcode'] ."',
		city = '" . $request->post['city'] ."',
		country = '" . $request->post['country'] ."',
		person_name = '" . $request->post['person_name'] ."',
		person_phone = '" . $request->post['person_phone'] ."',
		person_email = '" . $request->post['person_email'] ."',
		pin = '" . (!empty($request->post['pin']) ? $request->post['pin'] : "") ."',
		notice = '" . $request->post['notice'] ."'" , "id='".$id."'");

		if(!empty($result)) {
			return true;
		} else {
			self::$Error = Db::error();
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
