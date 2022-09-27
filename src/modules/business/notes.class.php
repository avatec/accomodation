<?php
use \Core\Backend\Navigation as Navigation;

/**
 * Business Notes class
 *
 * @package		Modules
 * @subpackage	Business/Notes
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

class BusinessNotes {
	protected static $table = "business_notes";
	public static $Error;

	public function __construct()
	{
		global $config;
		//Kernel::addAdminMenu("business", "Noty korygujące", "admin/business/notes/list/", null, "business", false);
		self::$table = $config['db_prefix'] . self::$table;
	}

	private function search()
	{
		global $request;
		if(!empty($request->get['q'])) {
			$qry[] = "number='" . $request->get['q'] . "'";
			$qry[] = "(s_name LIKE '%" . $request->get['q'] ."%' OR s_pin LIKE '%" . $request->get['q'] . "%')";
			$qry[] = "(b_name LIKE '%" . $request->get['q'] ."%' OR b_pin LIKE '%" . $request->get['q'] . "%')";
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
				return $Result;
			}
		} else {
			$Result = Db::row("*" , self::$table , "WHERE id='".$id."'");
			$Result['edit'] = true;
			return $Result;
		}
	}

	public static function _get()
	{
		$Array = Db::exec("*" , self::$table , "ORDER BY id");
		return $Array;
	}

	public function verify()
	{
		global $request;

		if(empty($request->post['s_pin'])) {
			self::$Error[] = "wprowadź numer nip wystawcy";
		}
		if(empty($request->post['s_name'])) {
			self::$Error[] = "wprowadź nazwę wystawcy";
		}
		if(empty($request->post['s_street'])) {
			self::$Error[] = "wprowadź adres wystawcy";
		}
		if(empty($request->post['s_postcode'])) {
			self::$Error[] = "wprowadź kod pocztowy wystawcy";
		}
		if(empty($request->post['s_city'])) {
			self::$Error[] = "wprowadź miejscowość wystawcy";
		}
		if(empty($request->post['b_pin'])) {
			self::$Error[] = "wprowadź numer nip adresata";
		}
		if(empty($request->post['b_name'])) {
			self::$Error[] = "wprowadź nazwę adresata";
		}
		if(empty($request->post['b_street'])) {
			self::$Error[] = "wprowadź adres adresata";
		}
		if(empty($request->post['b_postcode'])) {
			self::$Error[] = "wprowadź kod pocztowy adresata";
		}
		if(empty($request->post['b_city'])) {
			self::$Error[] = "wprowadź miejscowość adresata";
		}
		if(empty($request->post['incorrect'])) {
			self::$Error[] = "wprowadź treść przed korektą";
		}
		if(empty($request->post['correct'])) {
			self::$Error[] = "wprowadź treść po korekcie";
		}
		if(empty($request->post['invoice_number'])) {
			self::$Error[] = "wprowadź numer faktury, której dotyczy nota";
		}
		if(empty($request->post['invoice_create_date'])) {
			self::$Error[] = "wprowadź datę wystawienia faktury, której dotyczy nota";
		}

		if(empty($request->post['note_create_date'])) {
			self::$Error[] = "wprowadź datę wystawienia noty";
		}
		if(empty($request->post['note_number'])) {
			self::$Error[] = "wprowadź kolejny numer noty";
		}
		if(empty($request->post['note_city'])) {
			self::$Error[] = "wprowadź miejscowość wystawienia noty";
		}
		if(empty($request->post['note_name'])) {
			self::$Error[] = "wprowadź imię i nazwisko osoby wystawiającej notę";
		}
	}

	public function add()
	{
		global $request;

		$this->verify();
		if(!empty(self::$Error)) {
			return false;
		}

		$result = Db::insert( self::$table , "null,
		'" . $request->post['note_number'] . "',
		'" . $request->post['note_create_date'] ."',
		'" . $request->post['note_city'] ."',
		'" . $request->post['note_name'] ."',
		'" . $request->post['s_name'] ."',
		'" . $request->post['s_street'] ."',
		'" . $request->post['s_postcode'] ."',
		'" . $request->post['s_city'] ."',
		'" . $request->post['s_pin'] ."',
		'" . $request->post['b_name'] ."',
		'" . $request->post['b_street'] ."',
		'" . $request->post['b_postcode'] ."',
		'" . $request->post['b_city'] ."',
		'" . $request->post['b_pin'] ."',
		'" . $request->post['incorrect'] ."',
		'" . $request->post['correct'] . "',
		'" . $request->post['invoice_number'] . "',
		'" . $request->post['invoice_create_date']. "'");

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
