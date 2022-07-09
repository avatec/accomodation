<?php
use \Core\Backend\Navigation as Navigation;

/**
 * Objects Photos Room class
 *
 * @package		Modules
 * @subpackage	Objects/PhotosRoom
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

class ObjectsPhotosRoom {

	static protected $table = "rooms_photos";
	static protected $uid;
	static protected $imgPath = "userfiles/rooms/photos/";
	public static $Error;

	public function __construct()
	{
		global $config;

		self::$table = $config['db_prefix'] . self::$table;
	}

	public function getByRoom( $room_id, $for_user = true )
	{
		if(!empty(User::$admin)) {
			self::$uid = User::$admin['id'];
			return Db::exec("*" , self::$table , "WHERE room_id='" . $room_id . "' ORDER BY priority");
		} else {
			self::$uid = User::$user['id'];
			return Db::exec("*" , self::$table , "WHERE room_id='" . $room_id . "'" . (($for_user == true) ? " AND uid='" . self::$uid . "'" : "") . "  ORDER BY priority");
		}
	}

	public static function _get( $room_id )
	{
		return Db::exec("*" , self::$table , "WHERE room_id='" . $room_id . "' ORDER BY priority");
	}

	public function filter($room_id)
	{
		$Result = Db::exec("*" , self::$table, "WHERE room_id = '" . $room_id ."' ORDER BY priority");
		if(!empty($Result))
		{
			foreach($Result as $k=>$i) {
				$y = date('Y' , strtotime($i['date']));
				$m = date('m' , strtotime($i['date']));
				$Filter['years'][$y] = $y;
				$Filter['months'][$m] = $m;
			}

			if(!empty($Filter)) {
				return $Filter;
			}
		}
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

	public function last_priority( $room_id )
	{
		$r = Db::row("*" , self::$table , "WHERE room_id='" . $room_id . "' ORDER BY priority");
		if(!empty($r['priority'])) {
			return $r['priority']++;
		} else {
			return 1;
		}
	}

	public function verify()
	{
		global $request;

	}

	public function add( $filename = null )
	{
		global $app_path, $config, $request;

		if(!empty(User::$admin)) {
			self::$uid = Objects::getUidById($request->post['room_id']);
		} else {
			self::$uid = User::$user['id'];
		}

		$count = Db::counter(self::$table , "room_id='" . $request->post['room_id'] . "'");
		if($count == 0) {
			$main = "TRUE";
		} else {
			$main = "FALSE";
		}

		$check = Db::check(self::$table , "uid='" . self::$uid . "' AND room_id='" . $request->post['room_id'] . "' AND file='" . $filename . "'");
		if( $check == false) {
			$Result = Db::insert(self::$table , "null,
			'" . self::$uid . "',
			'" . $request->post['room_id'] . "',
			'" . $this->last_priority( $request->post['room_id'] ) . "',
			'" . $filename . "',
			'" . $main . "',
			NOW()");

			if($Result == true) {
				if( $config['announcement_moderate'] == "TRUE" ) {
					Objects::setStatus($request->post['room_id'], "FALSE");
				}
				return true;
			} else {
				self::$Error = Db::error();
				Kernel::createLog('photosroom.log' , 'photosroom.class.php @ 107 error: ' . implode( ',' . self::$Error ));
				return false;
			}
		} else {
			self::$Error = "File exists";
			Kernel::createLog('photosroom.log' , 'photosroom.class.php @ 112 error: ' . implode( ',' . self::$Error ));
			return false;
		}
	}

	public function save( $id )
	{
		global $app_path, $request;

		if(!empty($_FILES['file']['name'])) {
			$file = $this->upload();
			if(!empty($file)) {
				if(!empty($request->post['old_file'])) {
					if(file_exists(  $app_path . "userfiles/rooms/photos/" . $request->post['old_file'] )) {
						unlink( $app_path . "userfiles/rooms/photos/" . $request->post['old_file']);
					}
					if(file_exists($app_path . "userfiles/rooms/photos/" . $request->post['old_file'])) {
						unlink( $app_path . "userfiles/rooms/photos/thumbs/" . $request->post['old_file']);
					}
				}
			}
		} else {
			return true;
		}

		if(!empty(self::$Error)) {
			return false;
		}

		if(isset($file)) {
			$result = Db::update( self::$table , "file = '" . $file . "'" , "id='".$id."'");
		}

		if(!empty($result)) {
			return true;
		} else {
			return false;
		}

	}

	public function makeMain( $id )
	{
		global $request;
		Db::update(self::$table , "main='FALSE'" , "room_id='" . $request->get['room_id'] ."'");
		Db::update(self::$table , "main='TRUE'" , "id='" . $id . "' AND room_id='" . $request->get['room_id'] . "'");
		return true;
	}

	public static function update_priority( $id, $priority )
	{
		return Db::update( self::$table , "priority='" . $priority . "'" , "id='" . $id . "'");
	}

	public static function deleteByObject( $room_id )
	{
		$Result = Db::exec("*" , self::$table , "WHERE room_id='" . $room_id . "'");
		if(!empty($Result)) {
			foreach($Result as $k=>$i) {
				self::delete( $i['id'], $i['file'] );
			}
		}
	}

	public static function delete( $id, $file )
	{
		global $app_path;

		if( Db::check( self::$table , "id='" . $id ."'") == true) {
			if(file_exists($app_path . "userfiles/rooms/photos/" . $file) == true) {
				unlink($app_path . "userfiles/rooms/photos/" . $file);
			}
			if(file_exists($app_path . "userfiles/rooms/photos/thumbs/" . $file) == true) {
				unlink($app_path . "userfiles/rooms/photos/thumbs/" . $file);
			}

			Db::delete( self::$table , "id= '" . $id . "'");
			return true;
		} else {
			$db_error = Db::error();
			if(!empty($db_error)) {
				self::$Error[] = $db_error;
			}
			return false;
		}
	}

	public function upload( $edit = false )
	{
		if(!empty($_FILES['file'])) {
			if($_FILES['file']['error']==1) { self::$Error[] = "uploadowany plik przekracza dyrektywe upload_max_filesize w php.ini"; }
			if($_FILES['file']['error']==2) { self::$Error[] = "uploadowany plik przekracza dyrektywe MAX_FILE_SIZE w formularzu HTML"; }
			if($_FILES['file']['error']==3) { self::$Error[] = "uploadowany plik nie został poprawnie wgrany - błąd numer 3"; }
			if($_FILES['file']['error']==4) { self::$Error[] = "brak pliku"; }
			if($_FILES['file']['error']==6) { self::$Error[] = "brak dostępu do katalogu tymczasowego na serwerze - błąd numer 6"; }
			if($_FILES['file']['error']==7) { self::$Error[] = "nie udało się zapisać na dysku - błąd numer 7"; }
			if($_FILES['file']['error']==8) { self::$Error[] = "uploadowanie przerwane przez rozszerzenie - błąd numer 8 (UPLOAD_ERR_EXTENSION)"; }

			if(!empty(self::$Error)) {
				return false;
			}

			global $app_path, $request, $config;

			if(file_exists( $app_path . "vendor/verot/class.upload.php/src/class.upload.php") == true) {
				include_once $app_path . "vendor/verot/class.upload.php/src/class.upload.php";
			} else {
				throw new CMSError('Upload class not found');
			}

			$name = ObjectsRooms::getName($request->post['room_id']);

			$handle = new Upload( $_FILES['file'] );
			if ($handle->uploaded) {
				$handle->file_new_name_body	= $request->post['room_id'] . "_" . Kernel::rewrite($name) . "_" . time();
				$handle->file_overwrite		= false;
				$handle->file_auto_rename	= true;
				$handle->jpeg_quality 		= $config['announcement_photo_quality'];
				if($handle->image_src_x > $config['announcement_photo_width']) {
					$handle->image_resize		= true;
					$handle->image_x      		= $config['announcement_photo_width'];
					$handle->image_ratio_y		= true;
				}
				$handle->Process($app_path . "userfiles/rooms/photos/");

				if ($handle->processed) {
					//ini_set("log_errors", 1);
					//ini_set("error_log", $app_path . "php-error.log");
					//error_log($config['announcement_thumb_width']);
					//error_log($config['announcement_thumb_height']);
					//error_log($app_path . "userfiles/objects/photos/" . $handle->file_dst_name);
					try {
						Thumbnail::cropImage(
							$config['announcement_thumb_width'],
							$config['announcement_thumb_height'],
							$app_path . "userfiles/rooms/photos/" . $handle->file_dst_name,
							$app_path . "userfiles/rooms/photos/thumbs/" . $handle->file_dst_name
						);
					} catch( Exception $e) {
						//error_log( $e->getMessage() );
						file_put_contents($app_path . "logs/photosrooms.log" , $e->getMessage() . PHP_EOL, FILE_APPEND);
					}
					//file_put_contents($app_path . "logs/files.log" , "utworzono miniature" . PHP_EOL, FILE_APPEND);
					if( $edit == true ) {
						//file_put_contents($app_path . "logs/files.log" , "kierowanie do add: " . $handle->file_dst_name .  PHP_EOL, FILE_APPEND);
						$this->add( $handle->file_dst_name );
					}
					return $handle->file_dst_name;
				}

				self::$Error[] = "plik nie został załadowany na serwer";

			} else { self::$Error[] = "wystąpił nieoczekiwany błąd podczas uploadu"; }

			return false;

		}
	}

	public static function getImage( $room_id, $thumb = true )
	{
		global $app_url;

		$Result = Db::row("*" , self::$table, "WHERE room_id='" . $room_id . "' AND main='TRUE'");
		if(!empty($Result)) {
			if($thumb == true) {
				return $app_url . self::$imgPath . "thumbs/" . $Result['file'];
			} else {
				return $app_url . self::$imgPath . $Result['file'];
			}

		} else {
			return $app_url . "userfiles/blank.jpg";
		}
	}

	public static function howManyCanUpload( $room_id )
	{
		global $config;
		$photos_for_object = self::countPhotos( $room_id );
		$result = (int) $config['announcement_max_photos'] - (int) $photos_for_object;
		return (int) $result;
	}

	public static function canUploadPhotos( $room_id )
	{
		if( self::howManyCanUpload( $room_id ) > 0) {
			return true;
		} else {
			return false;
		}
	}

	public static function countPhotos( $room_id )
	{
		return Db::count( self::$table , "room_id='" . $room_id . "'");
	}
}
