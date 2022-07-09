<?php
/**
 * News gallery class
 *
 * @package		Modules
 * @subpackage	News/Gallery
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

class NewsGallery {

	protected static $table = "news_gallery";
	protected static $UploadDir = "news/gallery";
	protected static $file_schema;

	public static $Error;
	public static $UploadUrl;
	public static $thumbWidth = 768;
	public static $thumbHeight = 768;
	public static $maxWidth = 1680;
	public static $quality = 90;

	public function __construct()
	{
		global $config, $app_url, $app_path;

		if(isset($_SESSION['lng']['code'])) {
			$langCode = $_SESSION['lng']['code'];
		} else {
			$langCode = "pl";
		}

		self::$table = $config['db_prefix'] . self::$table . "_" . $langCode;

		if( !file_exists( $app_path . 'userfiles/' . self::$UploadDir . '/thumbs') ) {
			Kernel::createLog('news.log' , 'Folder [' . $app_path . 'userfiles/' . self::$UploadDir . '/thumbs/] doesn\'t exists ! Trying to create one: ' , false);
			if( $a = mkdir( $app_path . 'userfiles/' . self::$UploadDir . '/thumbs/', 0777 ) == true ) {
				Kernel::createLog('news.log' , 'success');
			} else {
				Kernel::createLog('news.log' , 'ERROR - Could not create');
				Kernel::createLog('news.log' , 'Please create this folder manually');
			}
		}

		self::$UploadUrl = $app_url . "userfiles/" . self::$UploadDir . "/";
	}

	private function __install()
	{
		/**
			CREATE TABLE `cms_news_gallery_pl` (
			  `id` int(11) UNSIGNED NOT NULL,
			  `news_id` int(11) UNSIGNED NOT NULL,
			  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `priority` int(11) NOT NULL,
			  `photo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `create_date` date NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

			ALTER TABLE `cms_news_gallery_pl` ADD PRIMARY KEY (`id`);
			ALTER TABLE `cms_news_gallery_pl` MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
		**/
	}

	public function getUsingNewsId( $news_id )
	{
		return Db::exec("*" , self::$table , "WHERE news_id='" . $news_id . "'");
	}

	public function get( $id = NULL )
	{
		if(is_null($id)) {
			return Db::exec("*" , self::$table , "ORDER BY priority");
		} else {
			$Result = Db::row("*" , self::$table , "WHERE id='".$id."'");
			$Result['edit'] = true;
			return $Result;
		}
	}

	public function verify()
	{

	}

	public function add()
	{
		set_time_limit(120);
		global $request;

		$this->verify();
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , LA::get('cms','error_form_return_error') . implode("<br/>" , self::$Error));
			return false;
		}
		if(!empty($_FILES['photo']['name'])) {
			$uploaded = $this->reArrayFiles($_FILES['photo']);
			$counted = count($_FILES['photo']['name']);
			if(!empty($uploaded)) {
				for($k=0;$k<$counted;$k++) {
					$org_filename = $uploaded[$k]['name'];

					if(isset($request->post['news_id'])) {
						self::$file_schema = $request->post['news_id'] . "_" . time();
					}
					$photo = $this->_upload( $uploaded[$k] );
					sleep(1);

					if($photo == false) {
						self::$Error = "plik: " . $org_filename . " nie został pomyślnie wgrany na serwer z powodu nieznanego błędu";
						Kernel::setMessage("ERROR" , self::$Error);
						return false;
					} else {

						if(!empty($photo)) {
							$result = Db::insert( self::$table , "null,
							'" . (isset($request->post['news_id']) ? $request->post['news_id'] : "") . "',
							'" . (isset($request->post['name']) ? $request->post['name'] : ""). "',
							'" . (!empty($request->post['priority']) ? $request->post['priority'] : "1") . "',
							'" . (!empty($photo) ? $photo : '') . "',
							NOW()");

							if($result == false) {
								self::$Error[] = Db::error();
							}
						}
					}
					unset($photo);
					unset($org_filename);
				}
			}
		}

		if(!empty(self::$Error)) {
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'error_db_return_error') . '<br/>' , self::$Error);
			return false;
		} else {
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'added_notice_success'));
			return true;
		}
	}

	public function save( $id )
	{
		global $request, $app_path;
		$this->verify();
		if($_FILES['photo']['error']['0'] == 0) {
			if(isset($request->post['category'])) {
				self::$file_schema = $request->post['category'] . "_" . time();
			}
			$fileArray = self::reArrayFiles($_FILES['photo']);
			$photo = $this->_upload($fileArray['0']);
			if(!empty($request->post['old_photo'])) {
				if(file_exists($app_path . "userfiles/" . self::$UploadDir . "/" . $file)) {
					unlink( $app_path . "userfiles/" . self::$UploadDir . "/" . $request->post['old_photo']);
				}
				if(file_exists($app_path . "userfiles/" . self::$UploadDir . "/thumbs/" . $file)) {
					unlink( $app_path . "userfiles/" . self::$UploadDir . "/thumbs/" . $request->post['old_photo']);
				}
			}
		}

		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , LA::get('cms','error_form_return_error') . implode("<br/>" , self::$Error));
			return false;
		}

		$result = Db::update( self::$table , "name = '" . (isset($request->post['name']) ? $request->post['name'] : "") . "',
		priority = '" . $request->post['priority'] . "'
		" . (!empty($photo) ? ",photo = '" . $photo . "'" : '') , "id='".$id."'");

		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'update_notice_success'));
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'error_db_return_error') . '<br/>' . self::$Error);
			return false;
		}

	}

	public function delete( $id, $file )
	{
		global $app_path;

		if( Db::check( self::$table , "id='" . $id ."'") == true) {
			Db::delete( self::$table , "id= '" . $id . "'");
			if(file_exists($app_path . "userfiles/" . self::$UploadDir . "/" . $file)) {
				@unlink( $app_path . "userfiles/" . self::$UploadDir . "/" . $file);
			}
			if(file_exists($app_path . "userfiles/" . self::$UploadDir . "/thumbs/" . $file)) {
				@unlink( $app_path . "userfiles/" . self::$UploadDir . "/thumbs/" . $file);
			}
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'delete_notice_success'));
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'error_db_return_error') . '<br/>' . self::$Error);
			return false;
		}
	}

	public static function delete_by_news( $news_id )
	{
		global $app_path;

		$r = Db::exec("*" , self::$table , "WHERE news_id='" . $news_id . "'");
		if(!empty($r)) {
			foreach( $r as $i ) {
				if(file_exists($app_path . "userfiles/" . self::$UploadDir . "/" . $i['photo'])) {
					@unlink( $app_path . "userfiles/" . self::$UploadDir . "/" . $i['photo']);
				}
				if(file_exists($app_path . "userfiles/" . self::$UploadDir . "/thumbs/" . $i['photo'])) {
					@unlink( $app_path . "userfiles/" . self::$UploadDir . "/thumbs/" . $i['photo']);
				}

				Db::delete( self::$table , "id= '" . $i['id'] . "'");
			}
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'delete_notice_success'));
			return true;
		}
	}

	public function delete_all( $post )
	{
		if(!empty($post)) {
			foreach($post as $id=>$file) {
				$result = $this->delete( $id, $file );
				if($result == false) {
					self::$Error[] = "Wystąpił błąd podczas usuwania zdjęcia " . $file . ". Najprawdopodobniej wybrana pozycja nie istnieje";
				}
			}
			if(empty(self::$Error)) {
				Kernel::setMessage("NOTICE" , "Pomyślnie usunięto wybrane zdjęcie");
				return true;
			} else {
				Kernel::setMessage("ERROR" , LA::get('cms','error_form_return_error') . implode("<br/>" , self::$Error));
			}
		}
	}

	public function priorityUpdate()
	{
		global $request;

		if(is_array($request->post['priority'])) {
			foreach($request->post['priority'] as $id => $value) {
				Db::update( self::$table , "priority='" . $value . "'" , "id='" . $id . "'");
			}
			return true;
		}
	}

	public function reArrayFiles(&$file_post)
	{
		if(!empty($file_post)) {
			foreach($file_post as $k=>$i) {
				foreach($i as $k2=>$i2) {
					foreach($i2 as $file ) {
						$file_repaired[$k2][$k] = $file;
						//echo 'f_r[' . $k2 . '][' . $k . '] = ' . $file . "<br/>";
					}
				}
			}
		}

		return $file_repaired;
	}

	public function multiply( $files )
	{
		$c = count($files['name']);
		if($c == 1) {
			return false;
		} else {
		    return true;
		}
	}

	public function _upload( $file )
	{
		if(!empty($file)) {
			if($file['error']==1) { self::$Error[] = "uploadowany plik przekracza dyrektywe upload_max_filesize w php.ini"; }
			if($file['error']==2) { self::$Error[] = "uploadowany plik przekracza dyrektywe MAX_FILE_SIZE w formularzu HTML"; }
			if($file['error']==3) { self::$Error[] = "uploadowany plik nie został poprawnie wgrany - błąd numer 3"; }
			if($file['error']==4) { self::$Error[] = "brak pliku"; }
			if($file['error']==6) { self::$Error[] = "brak dostępu do katalogu tymczasowego na serwerze - błąd numer 6"; }
			if($file['error']==7) { self::$Error[] = "nie udało się zapisać na dysku - błąd numer 7"; }
			if($file['error']==8) { self::$Error[] = "uploadowanie przerwane przez rozszerzenie - błąd numer 8 (UPLOAD_ERR_EXTENSION)"; }

			global $app_path;

			if(file_exists( $app_path . "vendor/verot/class.upload.php/src/class.upload.php") == true) {
				include_once $app_path . "vendor/verot/class.upload.php/src/class.upload.php";
			} else {
				throw new CMSError('Upload class not found');
			}

			$handle = new Upload( $file );
			if ($handle->uploaded)
			{
				if(isset(self::$file_schema)) {
					$handle->file_new_name_body	= self::$file_schema;
				} else {
					$handle->file_new_name_body	= time();
				}

				$handle->file_overwrite		= false;
				$handle->file_auto_rename	= false;
				$handle->jpeg_quality 		= self::$quality;
				if($handle->image_src_x > self::$maxWidth) {
					$handle->image_resize		= true;
					$handle->image_x      		= self::$maxWidth;
					$handle->image_ratio_y		= true;
				}
				$handle->Process($app_path . "userfiles/" . self::$UploadDir . "/");

				if ($handle->processed) {
					Thumbnail::cropImage(
						self::$thumbWidth,
						self::$thumbHeight,
						$app_path . "userfiles/" . self::$UploadDir . "/" . $handle->file_dst_name,
						$app_path . "userfiles/" . self::$UploadDir . "/thumbs/" . $handle->file_dst_name
					);
					return $handle->file_dst_name;
				}

				self::$Error[] = "plik nie został załadowany na serwer";

			} else { self::$Error[] = "wystąpił nieoczekiwany błąd podczas uploadu"; }

		}
		return false;
	}
}
