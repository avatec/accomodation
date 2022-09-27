<?php
use \Core\Backend\Navigation as Navigation;

/**
 * Slider class
 *
 * @package		Modules
 * @subpackage	Slider
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

class Slider {

	protected static $table = "slider";
	public static $Error;

	public function __construct()
	{
		global $config;
		Navigation::menu(15,'slider','Slider','slider/list','fa-photo');
		//Kernel::addAdminMenu("slider", "Slider", "admin/slider/list/", "fa-photo", null, false);

		self::$table = $config['db_prefix'] . self::$table;
	}

	public static function readable_date( $date )
	{
		$e = explode("-" , $date);
		$day = $e[2];
		$month = Common::getMonthName( $e[1] );
		return (int) $day . " " . $month;
	}

	public static function getForNow()
	{
		global $app_url;
		$now = date('m-d');

		$Result = Db::row("*" , self::$table , "WHERE '2000-" . $now . "' BETWEEN display_start AND display_end");
		if(!empty($Result)) {
			return $app_url . "userfiles/slider/" . $Result['photo'];
		} else {
			return $app_url . "userfiles/slider/default.jpg";
		}
	}

	public function get( $id = NULL )
	{
		if(is_null($id)) {
			return Db::exec("*" , self::$table , "ORDER BY priority");
		} else {
			$Result = Db::row("*" , self::$table , "WHERE id='".$id."'");
			$Result['edit'] = true;
			$d = explode("-" , $Result['display_start']);
			$sd = $d[2];
			$sm = $d[1];
			unset($d);
			$d = explode("-" , $Result['display_end']);
			$ed = $d[2];
			$em = $d[1];
			unset($d);

			$Result['start_day'] = $sd;
			$Result['start_month'] = $sm;
			$Result['end_day'] = $ed;
			$Result['end_month'] = $em;
			return $Result;
		}
	}

	private function verify()
	{
		global $request;

		if(empty($request->post['name'])) {
			self::$Error[] = "Wprowadź nazwę";
		}
		if(empty($request->post['start_day'])) {
			self::$Error[] = "Wprowadź dzien rozpoczęcia wyświetlania";
		}
		if(empty($request->post['start_month'])) {
			self::$Error[] = "Wprowadź miesiąc rozpoczęcia wyświetlania";
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
			Kernel::setMessage("ERROR" , "Wykryto błędy w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		if(!empty($request->post['end_month']) && !emptY($request->post['start_month'])) {
			if($request->post['end_month'] < $request->post['start_month']) {
				$year = "2001";
			} else {
				$year = "2000";
			}
		}

		if((!empty($request->post['start_day'])) AND (!empty($request->post['start_month']))) {
			$display_start = "2000" . "-" . $request->post['start_month'] . "-" . $request->post['start_day'];
		}

		if((!empty($request->post['end_day'])) AND (!empty($request->post['end_month']))) {
			$display_end = $year . "-" . $request->post['end_month'] . "-" . $request->post['end_day'];
		}

		$result = Db::insert( self::$table , "null,
		'" . $request->post['name'] . "',
		'" . (!empty($request->post['text']) ? $request->post['text'] : "") . "',
		" . (!empty($request->post['link']) ? "'" . $request->post['link'] . "'" : "NULL") . ",
		'" . (!empty($request->post['priority']) ? $request->post['priority'] : "") . "',
		'" . (!empty($photo) ? $photo : '') . "',
		NOW(),
		'" . $display_start ."',
		" . (!empty($display_end) ? "'" . $display_end . "'" : "NULL"));

		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , "Utworzono nową pozycję");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
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
					if(file_exists($app_path . "userfiles/slider/" . $request->post['old_photo']) == true) {
						unlink( $app_path . "userfiles/slider/" . $request->post['old_photo']);
					}
				}
			}
		}

		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Wykryto błędy w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		if(!empty($request->post['end_month']) && !emptY($request->post['start_month'])) {
			if($request->post['end_month'] < $request->post['start_month']) {
				$year = "2001";
			} else {
				$year = "2000";
			}
		}

		if((!empty($request->post['start_day'])) AND (!empty($request->post['start_month']))) {
			$display_start = "2000" . "-" . $request->post['start_month'] . "-" . $request->post['start_day'];
		}

		if((!empty($request->post['end_day'])) AND (!empty($request->post['end_month']))) {
			$display_end = $year . "-" . $request->post['end_month'] . "-" . $request->post['end_day'];
		}

		$result = Db::update( self::$table , "name = '" . $request->post['name'] . "',
		text = '" . (!empty($request->post['text']) ? $request->post['text'] : "") . "',
		" . (!empty($request->post['link']) ? "link = '" . $request->post['link'] . "'," : "") . "
		" . (!empty($request->post['priority']) ? "priority = '" . $request->post['priority'] . "'," : "") . "
		" . (!empty($photo) ? "photo = '" . $photo . "'," : '') . "
		display_start = '" . $display_start ."'," .
		(!empty($display_end) ? "display_end = '" .$display_end . "'" : "display_end=NULL") , "id='".$id."'");

		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , "Zmiany zostały zapisane");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
			return false;
		}

	}

	public function delete( $id, $file )
	{
		global $app_path;

		if( Db::check( self::$table , "id='" . $id ."'") == true) {
			Db::delete( self::$table , "id= '" . $id . "'");
			if(file_exists($app_path . "userfiles/slider/" . $file) == true) {
				unlink( $app_path . "userfiles/slider/" . $file);
			}
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto pozycję");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
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
				$handle->jpeg_quality 		= 90;
				if($handle->image_src_x > 1680) {
					$handle->image_resize		= true;
					$handle->image_x      		= 1680;
					$handle->image_ratio_y		= true;
				}
				$handle->Process($app_path . "userfiles/slider/");

				if ($handle->processed) {
					return $handle->file_dst_name;
				}

				self::$Error[] = "plik nie został załadowany na serwer";

			} else { self::$Error[] = "wystąpił nieoczekiwany błąd podczas uploadu"; }

		}
	}
}
