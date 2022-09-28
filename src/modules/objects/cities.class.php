<?php
use \Core\Backend\Navigation as Navigation;

/**
 * Objects cities class
 *
 * @package		Modules
 * @subpackage	Objects/Cities
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

class ObjectsCities {

	protected static$table = "cities";
	static private $search_query;
	public static $Error;

	public function __construct()
	{
		global $config;

		Navigation::submenu('objects' , 'Miejscowości', "objects/cities/list/");
		//Kernel::addAdminMenu("config", "Miejscowości", "admin/objects/cities/list/", null, true);

		self::$table = $config['db_prefix'] . self::$table;
	}

	private function search()
	{
		global $request;

		if(!empty($request->get['q'])) {
			self::$search_query[] = "(name LIKE '%" . $request->get['q'] . "%' OR rewrite LIKE '%" . $request->get['q'] . "%')";
		}
	}

	public static function _get()
	{
		return Db::exec("*" , self::$table , "WHERE main='TRUE'");
	}

	public static function getMeta( $city_name , $state_id = null )
	{
		if( is_null( $state_id )) {
			$meta = Db::row("meta_title,meta_keywords,meta_description" , self::$table , "WHERE name = '" . $city_name . "' OR rewrite = '" . $city_name . "'");
		} else {
			if( self::exists( $city_name , $state_id ) == true ) {
				$meta = Db::row("meta_title,meta_keywords,meta_description" , self::$table , "WHERE state_id = '" . $state_id . "' AND (name = '" . $city_name . "' OR rewrite = '" . $city_name . "')");
			}
		}

		if(!empty($meta)) {
			return $meta;
		}
	}

	protected static function exists( $city_name , $state_id )
	{
		return Db::check( self::$table , "state_id='" . $state_id . "' AND (name = '" . $city_name . "' OR rewrite = '" . Kernel::rewrite($city_name) . "' OR rewrite = '" . $city_name . "')");
	}

	public static function quickAdd( $city_name, $state_id )
	{
		if( self::exists( $city_name , $state_id ) == false ) {
			$city_name = strtolower( $city_name );
			$result = Db::insert( self::$table , "null,
			'" . $state_id . "',
			'" . ucfirst(strtolower($city_name)) . "',
			'" . (!empty($request->post['description']) ? addslashes($request->post['description']) : "") . "',
			'" . Kernel::rewrite( $city_name ) . "',
			NULL,
			'FALSE',
			'',
			'',
			''");

			if($result == true) {
				return true;
			} else {
				self::$Error = Db::error();
				Kernel::setMessage("ERROR" , "ObjectsCities::quickAdd() - Wystąpił błąd podczas w bazie danych podczas operacji:<br/>" . self::$Error);
				return false;
			}
		}
	}

	public static function searchByName( $name )
	{
		$Result = Db::row("id" , self::$table , "WHERE name LIKE '%" . $name . "%' OR rewrite LIKE '%" . $name . "%'");
		if(!empty($Result['id'])) {
			return $Result['id'];
		}
	}

	public function get( $id = NULL )
	{
		if(is_null($id)) {
			$this->search();
			return Db::exec("*" , self::$table , (!empty(self::$search_query) ? "WHERE " . implode(" OR " , self::$search_query) : "") . " ORDER BY rewrite");
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
		$Row = Db::row("name" , self::$table , "WHERE id='" . $id . "'");
		if(!empty($Row)) {
			return $Row['name'];
		} else {
			//return "ObjectsCities ERROR - id not found";
		}
	}

	public static function getDescription( $id )
	{
		$Row = Db::row("description" , self::$table , "WHERE id='" . $id . "'");
		if(!empty($Row)) {
			return $Row['description'];
		} else {
			//return "ObjectsCities ERROR - id not found";
		}
	}

	public function verify()
	{
		global $request;

		if(empty($request->post['name'])) {
			self::$Error[] = "podaj nazwę";
		}

		if(empty($request->post['state_id'])) {
			self::$Error[] = "wybierz województwo";
		}
	}

	public function add()
	{
		global $request;

		$this->verify();
		if(!empty($_FILES['photo']['name'])) {
			$photo = $this->_upload();
		}
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Wystąpił błąd w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		$result = Db::insert( self::$table , "null,
		'" . $request->post['state_id'] . "',
		'" . $request->post['name'] ."',
		'" . (!empty($request->post['description']) ? addslashes($request->post['description']) : "") . "',
		'" . Kernel::rewrite($request->post['name']) ."',
		" . (!empty($photo) ? "'" . $photo . "'" : "NULL") . ",
		'" . $request->post['main'] . "',
		'" . (!empty($request->post['meta_title']) ? $request->post['meta_title'] : "") . "',
		'" . (!empty($request->post['meta_description']) ? $request->post['meta_description'] : "") . "',
		'" . (!empty($request->post['meta_keywords']) ? $request->post['meta_keywords'] : "") . "'");

		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , "Dodano nową pozycję");
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas w bazie danych podczas operacji::<br/>" . self::$Error);
		}

		if(!empty(self::$Error)) {
			return false;
		}

		return true;

	}

	public function save( $id )
	{
		global $request, $app_path;

		$this->verify();
		if(!empty($_FILES['photo']['name'])) {
			$photo = $this->_upload();
			if(!empty($photo)) {
				if(!empty($request->post['old_photo'])) {
					if(file_exists($app_path . "userfiles/objects/cities/" . $request->post['old_photo'])) {
						unlink( $app_path . "userfiles/objects/cities/" . $request->post['old_photo']);
					}
				}
			}
		}
		if(!empty(self::$Error)) {
			return false;
		}

		$result = Db::update( self::$table , "state_id = '" . $request->post['state_id'] . "',
		name = '" . $request->post['name'] ."',
		" . (!empty($request->post['description']) ? "description = '" . addslashes($request->post['description']) . "'," : "") . "
		rewrite = '" . Kernel::rewrite($request->post['name']) . "'
		" . (!empty($photo) ? ",photo = '" . $photo . "'" : "") . "
		,main = '" . $request->post['main'] . "'
		" . (!empty($request->post['meta_title']) ? ",meta_title = '" . $request->post['meta_title'] . "'" : "") . "
		" . (!empty($request->post['meta_description']) ? ",meta_description = '" . $request->post['meta_description'] . "'" : "") . "
		" . (!empty($request->post['meta_keywords']) ? ",meta_keywords = '" . $request->post['meta_keywords'] . "'" : "") , "id='".$id."'");

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
		if( Db::check( self::$table , "id='" . $id ."'") == true) {
			if( Db::delete( self::$table  , "id= '" . $id . "'") == true ) {
				Kernel::setMessage("NOTICE" , "Pomyślnie usunięto pozycję");
			} else {
				self::$Error = Db::error();
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas usuwania pozycji. Najprawdopodobniej wybrana pozycja nie istnieje<br/>" . self::$Error);
			}
		}
		return true;
	}

	/**
	 * Metoda odpowiedzialna za upload obrazka do newsa
	 *
	 * @params $_FILES['photo']
	 * @example $cities->_upload();
	 */
	public function _upload()
	{
		if(is_array($_FILES['photo'])) {
			if($_FILES['photo']['error']==1) { self::$Error[] = "uploadowany plik przekracza dyrektywe upload_max_filesize w php.ini"; }
			if($_FILES['photo']['error']==2) { self::$Error[] = "uploadowany plik przekracza dyrektywe MAX_FILE_SIZE w formularzu HTML"; }
			if($_FILES['photo']['error']==3) { self::$Error[] = "uploadowany plik nie został poprawnie wgrany - błąd numer 3"; }
			if($_FILES['photo']['error']==4) { self::$Error[] = "brak pliku"; }
			if($_FILES['photo']['error']==6) { self::$Error[] = "brak dostępu do katalogu tymczasowego na serwerze - błąd numer 6"; }
			if($_FILES['photo']['error']==7) { self::$Error[] = "nie udało się zapisać na dysku - błąd numer 7"; }
			if($_FILES['photo']['error']==8) { self::$Error[] = "uploadowanie przerwane przez rozszerzenie - błąd numer 8 (UPLOAD_ERR_EXTENSION)"; }

			global $app_path;

			if(file_exists( $app_path . "vendor/verot/class.upload.php/src/class.upload.php") == true) {
				include_once $app_path . "vendor/verot/class.upload.php/src/class.upload.php";
			} else {
				throw new CMSError('Upload class not found');
			}

			$handle = new Upload( $_FILES['photo'] );
			if ($handle->uploaded)
			{
				$handle->file_new_name_body	= time();
				$handle->file_overwrite		= true;
				$handle->file_auto_rename	= false;
				$handle->jpeg_quality 		= 85;
				if($handle->image_src_x > 768) {
					$handle->image_resize		= true;
					$handle->image_x      		= 768;
					$handle->image_ratio_y		= true;
				}
				$handle->Process($app_path . "userfiles/objects/cities/");

				if ($handle->processed) {
					return $handle->file_dst_name;
				}

				self::$Error[] = "plik nie został załadowany na serwer";

			} else { self::$Error[] = "wystąpił nieoczekiwany błąd podczas uploadu"; }

		}
	}
}
