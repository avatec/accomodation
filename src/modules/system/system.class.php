<?php
use \Core\Backend\Navigation as Navigation;

/**
 * System module main class
 *
 * @package		Modules
 * @subpackage	System
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

class System {

/* @var Database table name */
	public static $table = "config";

/* @internal Module internal config */
	public static $config;

/* Module error handler */
	public static $Error;

	protected static $thumbWidth = 768;
	protected static $thumbHeight = 768;

/* @internal Default true or false array */
	public static $truefalse = [
		[ 'id' => 'FALSE' , 'name' => 'nie' ],
		[ 'id' => 'TRUE' , 'name' => 'tak' ]
	];

/* @internal Default type of promoted looks on main page */
	public static $PromotedMainTypes = [
    	['id' => 'STANDARD' , 'name' => 'Kafelki - 3 szt. na wiersz'],
    	['id' => 'SLIDER' , 'name' => 'Karuzela - 3 szt. na slide' ]
	];

/* @internal Visibility of elements */
	public static $visibility = [
		['id' => '0', 'name' => 'ukryte', 'label' => 'warning'],
		['id' => '1', 'name' => 'widoczne', 'label' => 'success']
	];

/* @internal Class constructor */
	public function __construct()
	{
		global $config;

		Navigation::menu(0, 'system' , 'Pulpit', 'start.html', 'fa-desktop');
		Navigation::menu(1, 'config' , LA::get('system' , 'menu_config'), null, 'fa-cogs');
			Navigation::submenu('config' , LA::get('system' , 'menu_config_main'), "system/config/main/");
			Navigation::submenu('config' , LA::get('system' , 'menu_config_contact'), "system/config/contact/");
			Navigation::submenu('config' , LA::get('system' , 'menu_config_social'), "system/config/social/");
			Navigation::submenu('config' , LA::get('system' , 'menu_config_seo'), "system/config/seo/");
			Navigation::submenu('config' , 'Wyróżnienia', "system/promotion/list/");
		Navigation::menu(2, 'advanced' , LA::get('system' , 'menu_config_advanced'), null, 'fa-wrench');
			Navigation::submenu('advanced' , 'Użytkownicy', "system/users/list/");
			Navigation::submenu('advanced' , LA::get('system' , 'menu_config_advanced_smtp'), "system/config/smtp/");
			Navigation::submenu('advanced' , LA::get('system' , 'menu_config_payments'), "system/config/payments/");
		Navigation::label(9, 'Moduły strony');

		/**
		Kernel::addAdminMenu("config", "Konfiguracja", null, "fa-cogs", null, false);
			Kernel::addAdminMenu("config", "Podstawowa", "admin/system/config/main/", null, true);
			Kernel::addAdminMenu("config", "Dane teleadresowe", "admin/system/config/contact/", null, true);
			Kernel::addAdminMenu("config", "Społecznościowe", "admin/system/config/social/", null, true);
			Kernel::addAdminMenu("config", "SEO / Meta tagi", "admin/system/config/seo/", null, true);
			Kernel::addAdminMenu("config", "Bramki płatności", "admin/system/config/payments/", null, true);
			Kernel::addAdminMenu("config", "Wyróżnienia", "admin/system/promotion/list/", null, true);
			//Kernel::addAdminMenu("config", "Treść umowy", "admin/system/config/rules/", null, true);
		Kernel::addAdminMenu("advanced", "Zaawansowane", null, "fa-wrench", null, false);
			Kernel::addAdminMenu("advanced", "Użytkownicy", "admin/system/users/list/", null, true);
			Kernel::addAdminMenu("advanced", "Konto SMTP", "admin/system/config/smtp/", null, true);
		**/

		self::$table = $config['db_prefix'] . self::$table;

		Language::load("system");
		self::$truefalse = Language::get("system" , "system_class_truefalse");

		Kernel::registerComponent( null, "brak modułu" , null);
		Kernel::registerComponent( 1 , "Strona kontakt z formularzem" , "contact");
	}

/**
 *	Sprawdzanie czy wybrany plik istnieje i czy nie jest katalogiem
 *	@param (string) $file
 *	@return (bool)
 */
	public static function file_exists( $file )
	{
		if( !is_dir( $file ) && file_exists( $file ) == true ) {
			return true;
		}

		return false;
	}

/**
 *	Sprawdzanie czy wybrany katalog istnieje
 *	@param (string) $dir
 *	@return (bool)
 */
	public static function dir_exists( $dir )
	{
		if( file_exists( $dir ) == true && is_dir( $dir) == true ) {
			return true;
		}
		return false;
	}

/**
 *	Tworzenie katalogu
 *	@param (string) $dir
 *	@return (bool)
 */
	public static function create_dir( $dir, $chmod = 0777 )
	{
		if( self::dir_exists( $dir ) == false) {
			mkdir( $dir , $chmod );
		}
	}

	public static function delete_file( $file )
	{
		if( file_exists( $file ) == true ) {
			unlink( $file );
		}
	}

	public static function rebuild_multiupload( &$file_post )
	{
	    $file_ary = array();
	    $file_count = count($file_post['name']);
	    $file_keys = array_keys($file_post);

	    for ($i=0; $i<$file_count; $i++) {
	        foreach ($file_keys as $key) {
	            $file_ary[$i][$key] = $file_post[$key][$i];
	        }
	    }

	    return $file_ary;
	}

	public static function read( $id, $array, $label = false )
	{
		foreach( $array as $i ) {
			if( $id == $i['id'] ) {
				if( $label == false ) {
					return $i['name'];
				}

				return '<span class="label label-' . $i['label'] . '">' . $i['name'] . '</span>';
			}
		}
	}

	public function configGet()
	{
		$Result = Db::exec("*" , self::$table , "");
		if(is_array($Result)) {
			foreach($Result as $key=>$item) {
				self::$config[$item['name']] = html_entity_decode($item['value']);
			}
		}

		return self::$config;
	}

	public function configSave()
	{
		global $request;

		if(!empty($request->post)) {
			foreach($request->post as $k=>$i) {
				if( Db::check( self::$table , "name='" . html_entity_decode($k) . "'", true) == true) {
					Db::update( self::$table , "value='" . addslashes($i) . "'" , "name='" . html_entity_decode($k) . "'");
				} else {
					Db::insert( self::$table , "null,'" . $k . "','" . addslashes($i) . "'");
				}
			}
		}
	}

	public static function update_config( $name, $value )
	{
		if( Db::check( self::$table , "name='" . $name . "'") == true) {
			Db::update( self::$table , "value='" . addslashes($value) . "'" , "name='" . $name . "'");
		} else {
			Db::insert( self::$table , "null,'" . $name . "','" . addslashes($value) . "'");
		}

		return;
	}

/* @internal Uploading social image */
	public function configSocialImage()
	{
		global $app_path;

		if(!empty($_FILES['social_img']['name'])) {
			$social_logo = $this->upload( $_FILES['social_img'], [
				'upload_dir' => $app_path . "templates/website/images/",
				'filename' => 'facebook',
				'overwrite' => true
			]);

			Kernel::setMessage("NOTICE" , "Zdjęcie zostało zmienione na nowe");
			Db::update( self::$table , "value='facebook.jpg'" , "name='social_img'");
			return $social_logo;
		}
	}

/* @internal Uploading logo */
	public function configUploadLogo()
	{
		global $app_path;

		if(!empty($_FILES['website_logo']['name'])) {
			$website_logo = $this->upload( $_FILES['website_logo'], [
				'upload_dir' => $app_path . "templates/website/images/",
				'filename' => 'logo',
				'overwrite' => true
			]);

			Kernel::setMessage("NOTICE" , "Logo zostało zmienione na nowe");
			Db::update( self::$table , "value='logo.png'" , "name='website_logo'");
			return $website_logo;
		}
	}

/* @internal Uploading watermark logo */
	public function waterMarkLogoUpload()
	{
		global $app_path;

		if(!empty($_FILES['watermark']['name'])) {
			$watermark_logo = $this->upload( $_FILES['watermark'], [
				'upload_dir' => $app_path . "templates/website/images/",
				'filename' => 'watermark',
				'overwrite' => true
			]);

			Kernel::setMessage("NOTICE" , "Logo zostało zmienione na nowe");
			Db::update( self::$table , "value='watermark.png'" , "name='watermark_logo'");
			return $watermark_logo;
		}
	}

/* @internal Uploading facebook image */
	public function facebookImageUpload()
	{
		global $app_path;

		if(!empty($_FILES['facebook']['name'])) {
			$facebook = $this->upload( $_FILES['facebook'], [
				'upload_dir' => $app_path . "templates/website/images/",
				'filename' => 'facebook',
				'overwrite' => true
			]);

			Kernel::setMessage("NOTICE" , "Logo zostało zmienione na nowe");
			return $facebook;
		}
	}

/**
 *	Method for uploading files
 *	@since 1.3
 *
 *	@params array $file
 *	@params array $options
 *	@params $options['filename'] string default uploaded file name
 *	@params $options['upload_dir'] string upload folder name
 *	@params $options['with_thumbs'] boolean generate thumbnail (true/false)
 *	@params $options['thumb_width'] integer width of thumbnail
 *	@params $options['thumb_height'] integer height of thumbnail
 *	@params $options['overwrite'] boolean overwrite existing file (true/false)
 *
 *	@example System::upload( $_FILES['plik'], [ 'upload_dir' => '/upload_folder/' ]);
 *
 *	@return filename
 */

 public static function upload( $file, $options )
 {
	 global $app_path;

	 $filename = (isset($options['filename']) ? $options['filename'] : time() );
	 $upload_dir = (isset($options['upload_dir']) ? $options['upload_dir'] : 'userfiles/');
	 $with_thumbs = (!empty($options['thumbs']) ? $options['thumbs'] : false);
	 $overwrite = (!empty($options['overwrite']) ? $options['overwrite'] : false);
	 self::$thumbWidth = (isset($options['thumb_width']) ? $options['thumb_width'] : 768);
	 self::$thumbHeight = (isset($options['thumb_height']) ? $options['thumb_height'] : 768);

	 if((!empty($file) && is_array($file))) {
		 if($file['error']==1) { self::$Error[] = "uploadowany plik przekracza dyrektywe upload_max_filesize w php.ini"; }
		 if($file['error']==2) { self::$Error[] = "uploadowany plik przekracza dyrektywe MAX_FILE_SIZE w formularzu HTML"; }
		 if($file['error']==3) { self::$Error[] = "uploadowany plik nie został poprawnie wgrany - błąd numer 3"; }
		 if($file['error']==4) { self::$Error[] = "brak pliku"; }
		 if($file['error']==6) { self::$Error[] = "brak dostępu do katalogu tymczasowego na serwerze - błąd numer 6"; }
		 if($file['error']==7) { self::$Error[] = "nie udało się zapisać na dysku - błąd numer 7"; }
		 if($file['error']==8) { self::$Error[] = "uploadowanie przerwane przez rozszerzenie - błąd numer 8 (UPLOAD_ERR_EXTENSION)"; }

		 if(file_exists( $app_path . "vendor/verot/class.upload.php/src/class.upload.php") == true) {
			 include_once $app_path . "vendor/verot/class.upload.php/src/class.upload.php";
		 } else {
			 trigger_error('Upload class not found');
		 }

		 $handle = new Upload( $file );
		 if ($handle->uploaded)
		 {
			 $handle->file_new_name_body	= $filename;
			 $handle->file_overwrite		= $overwrite;
			 if( $overwrite == false ) {
				 $handle->file_auto_rename	= true;
			 }
			 $handle->Process( $upload_dir );

			 if ($handle->processed) {
				 if( $with_thumbs == true ) {
					 Thumbnail::cropImage(
						 self::$thumbWidth,
						 self::$thumbHeight,
						 $upload_dir . "/" . $handle->file_dst_name,
						 $upload_dir . "/thumbs/" . $handle->file_dst_name
					 );
				 }
				 return $handle->file_dst_name;
			 }

			 self::$Error[] = "plik nie został załadowany na serwer";

		 } else {
			 self::$Error[] = "wystąpił nieoczekiwany błąd podczas uploadu";
		 }
	 }

	 if(!empty(self::$Error)) {
		 Kernel::setMessage("ERROR" , "Wystąpił błąd podczas uploadu pliku" , self::$Error);
		 return false;
	 }
 }

}
