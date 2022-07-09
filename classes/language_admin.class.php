<?php
/**
 * Admin Language class
 *
 * @package		Classes
 * @subpackage	LanguageAdmin
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
 
class LA {
	
	public static $available;
	public static $selected = 'pl';
	public static $lang = array();
	
	public static function init()
	{
		if(!empty($_SESSION['lng_admin']['code'])) {
			self::$selected = $_SESSION['lng_admin']['code'];
		} else {
			self::change( "pl" );
		}
	}
	
	public static function change( $code ) 
	{
		self::$selected = $code;
		self::update();
	}
	
	public static function get( $module, $translate, $replace = null ) 
	{
		global $app_url;
		
		if( strpos( $module , '/' ) == true ) {
			$e = explode("/" , $module);
			$module = $e['0'];
			$file = $e['1'];
			$module_arr = $e['0'] . '_' . $e['1'];
		} else {
			$module_arr = $module;
		}
		
		
		if(isset(self::$lang[$module_arr][$translate])) {
			$text = self::$lang[$module_arr][$translate];
			$text = str_replace("[app_url]" , $app_url , $text);
			$text = str_replace("[app_url_without_http]" , str_replace("http://" , "" , $app_url) , $text);
			if(!empty($replace)) {
				$text = preg_replace_callback('/([##]+)/', function( $matches ) use (&$replace) {
					return array_shift($replace);
				}, $text);
			}
			return $text;
		} else {
			return $translate;
			//trigger_error('Translation for <b>' . $module . '</b> => <u>' . $translate . '</u> <b>not found</b><br/>' , E_USER_NOTICE );
		}
	}
	
	public static function set($module, $value) 
	{
		self::$lang[$module] = $value;
	}
	
	public static function load( $module, $include = false ) 
	{
		global $app_path;
		
		if( strpos( $module , '/' ) == true ) {
			$e = explode("/" , $module);
			$module = $e['0'];
			$file = $e['1'];
			$module_arr = $e['0'].'_' . $e['1'];
		} else {
			$module_arr = $module;
		}

		
		if( $include == true ) {
			$lang_file = $app_path . 'include/languages/admin/' . $module . '_' . self::$selected . '.php';	
		} else {
			$lang_file = $app_path . 'modules/' .$module . '/languages/admin/' . (!empty($file) ? $file . '/' : '') . self::$selected . '.php';	
		}
		
		if( file_exists( $lang_file ) == true ) {
			include( $lang_file );
			if(isset($AdminLang)) {
				self::$lang[$module_arr] = $AdminLang;
				self::update();
			}
		} else {
			//trigger_error('Can\'t find language file in module <b>'.$lang_file.'</b>' , E_USER_NOTICE );
		}
	}
	
	public static function update()
	{
		$_SESSION['lng_admin'] = array(
			'code' => self::$selected,
			'translate' => self::$lang
		);
	}
	
	public static function detect()
	{
		return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	}
}