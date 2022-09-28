<?php
use \Core\Backend\Navigation as Navigation;

/**
 * Objects location class
 *
 * @package		Modules
 * @subpackage	Objects/Location
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

class ObjectsLocation {

	protected static$table = "locations";
	protected static$nl_table;
	public static $Error;

	public function __construct()
	{
		global $config;

		Navigation::submenu('objects' , 'Lokalizacja', "objects/location/list/");
		//Kernel::addAdminMenu("objects", "Lokalizacja", "admin/objects/location/list/", null, true);

		if(isset($_SESSION['lng']['code'])) {
			$langCode = $_SESSION['lng']['code'];
		} else {
			$langCode = "pl";
		}

		self::$nl_table = $config['db_prefix'] . self::$table . "_";
		self::$table = self::$table . "_" . $langCode;
		self::$table = $config['db_prefix'] . self::$table;
	}

	public function get( $id = NULL )
	{
		if(is_null($id)) {
			return Db::exec("*" , self::$table , "ORDER BY id");
		} else {
			$Result = Db::row("*" , self::$table , "WHERE id='".$id."'");
			$Result['edit'] = true;
			return $Result;
		}
	}

	public function getMain()
	{
		return Db::exec("*" , self::$table , "WHERE show_main='TRUE'");
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
		if(!empty($_FILES['photo']['name'])) {
			$photo = $this->_upload();
		}
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Wystąpiły błędy w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}
		foreach(Language::$available as $lang=>$i) {
			$result = Db::insert( self::$nl_table . $lang , "null,
			'" . $request->post['show_main'] . "',
			'" . $request->post['name'] ."',
			'" . (empty($request->post['rewrite']) ? Kernel::rewrite($request->post['name']) : Kernel::rewrite($request->post['rewrite'])) ."',
			" . (!empty($photo) ? "'" . $photo . "'" : "NULL"));

			if($result == true) {
				Kernel::setMessage("NOTICE" , "Dodano nową pozycję dla języka " . $lang);
			} else {
				self::$Error = Db::error();
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych (".self::$nl_table . $lang."):<br/>" . self::$Error);
			}
		}

		if($result == true) {
			return true;
		} else {
			return false;
		}

	}

	public function save( $id )
	{
		global $app_path, $request;

		$this->verify();
		if(!empty($_FILES['photo']['name'])) {
			$photo = $this->_upload();
			if(!empty($photo)) {
				if(!empty($request->post['old_photo'])) {
					if(file_exists($app_path . "userfiles/locations/" . $request->post['old_photo'])) {
						unlink( $app_path . "userfiles/locations/" . $request->post['old_photo']);
					}
				}
			}
		}
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Wystąpiły błędy w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		$result = Db::update( self::$table , "show_main = '" . $request->post['show_main'] . "',
		name = '" . $request->post['name'] ."',
		rewrite = '" . (empty($request->post['rewrite']) ? Kernel::rewrite($request->post['name']) : Kernel::rewrite($request->post['rewrite'])) ."'" .
		(!empty($photo) ? ",icon = '" . $photo . "'" : "") , "id='".$id."'");

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
		global $app_path;
		if( Db::check( self::$table , "id='" . $id ."'") == true) {
			$row = $this->get( $id );
			if(!empty($row['icon'])) {
				if(file_exists($app_path . "userfiles/locations/" . $row['icon']) == true) {
					unlink( $app_path . "userfiles/locations/" . $row['icon'] );
				}
			}
			foreach(Language::$available as $lang=>$i) {
				Db::delete( self::$nl_table . $lang , "id= '" . $id . "'");
			}
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto pozycję");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas usuwania pozycji. Najprawdopodobniej wybrana pozycja nie istnieje<br/>" . self::$Error);
			return false;
		}
	}

	public static function getSelect()
	{
		return Db::exec("*" , self::$table , "ORDER BY id");
	}

	public static function getID( $rewrite )
	{
		$row = Db::row("id" , self::$table , "WHERE rewrite='" . $rewrite . "'");
		return $row['id'];
	}

	public static function has( $rewrite )
	{
		if( Db::check( self::$table , "rewrite='" . $rewrite ."'") == true) {
			return self::getID( $rewrite );
		} else {
			return false;
		}
	}

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
				if($handle->image_src_x > 600) {
					$handle->image_resize		= true;
					$handle->image_x      		= 600;
					$handle->image_ratio_y		= true;
				}
				$handle->Process($app_path . "userfiles/locations/");

				if ($handle->processed) {
					return $handle->file_dst_name;
				}

				self::$Error[] = "plik nie został załadowany na serwer";

			} else { self::$Error[] = "wystąpił nieoczekiwany błąd podczas uploadu"; }

		}
	}
}
