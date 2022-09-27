<?php
/**
 * News category class
 *
 * @package		Modules
 * @subpackage	News/Category
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

class NewsCategory {
	
	protected static $table = "news_category";
	public static $Error;
	
	public function __construct() 
	{
		if(isset($_SESSION['lng']['code'])) {
			$langCode = $_SESSION['lng']['code'];
		} else {
			$langCode = "pl";
		}
		
		global $config;
		self::$table = $config['db_prefix'] . self::$table . "_" . $langCode;
		
		if( Db::has_table( self::$table ) == false ) {
			self::__install();
		}
		
		/**$lp = 900;
		foreach(self::getSelect() as $i) {
			Kernel::registerComponent( $lp , "Lista aktualności [" . $i['name'] . "]" , "news/category-list:" . $i['id']);	
			$lp++;
		}**/
		//unset($lp);
	}
	
	public static function __install()
	{
		Db::install('DROP TABLE IF EXISTS ' . self::$table . ';');
		Db::install('CREATE TABLE ' . self::$table . ' (
		  `id` int(11) UNSIGNED NOT NULL,
		  `priority` int(11) NOT NULL,
		  `parent` int(11) NOT NULL,
		  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
		  `name_rw` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `description` text COLLATE utf8_unicode_ci NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
		
		Db::install('ALTER TABLE ' . self::$table . ' ADD PRIMARY KEY (`id`), ADD KEY `priority` (`priority`), ADD KEY `parent` (`parent`);');
		Db::install('ALTER TABLE ' . self::$table . ' MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;');
	}
	
	public static function get_first()
	{
		$Row = Db::row("id" , self::$table , "ORDER BY id LIMIT 0,1");
		return $Row['id'];
	}
	
	public static function getSelect()
	{
		return Db::exec("id,name" , self::$table, "ORDER BY priority");
	}
	
	public static function lastPriority( $parent = null ) 
	{
		$Row = Db::row("*" , self::$table , "ORDER BY priority DESC");
		$priority = $Row['priority'] + 1;
		return $priority;
	}
	
	public function get( $id = null ) 
	{
		global $app_url, $route;
		
		if(is_null($id)) {
			$Result = Db::exec("*" , self::$table , "ORDER BY priority");
			if(!empty($Result)) {
				foreach( $Result as $k=>$i ) {
					if( $route->isAdmin == true ) {
						$Result[$k]['num'] = News::countByCategory( $i['id'], true );
					} else {
						$Result[$k]['num'] = News::countByCategory( $i['id'], false );
					}
					$Result[$k]['link'] = $app_url . "news/" . Kernel::rewrite($i['name']) . "-c" . $i['id'];
				}
				return $Result;
			}
		} else {
			$Result = Db::row("*" , self::$table , "WHERE id='" . $id . "'");
			$Result['description'] = html_entity_decode($Result['description']);
			$Result['edit'] = true;
			return $Result;
		}
	}
	
	public function verify()
	{
		global $request;
		if(empty($request->post['name'])) {
			self::$Error[] = "wprowadź nazwę kategorii";
		}
	}
	
	public function add()
	{
		global $request;
		
		$this->verify();			
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , LA::get('cms','error_form_return_error') . implode("<br/>" , self::$Error));
			return false;
		}
		
		$result = Db::insert( self::$table , "null,
		'" . (!empty($request->post['priority']) ? $request->post['priority'] : $this->lastPriority()) . "',
		'" . (isset($request->post['parent']) ? $request->post['parent'] : '0') ."',
		'" . (isset($request->post['name']) ? $request->post['name'] : '') ."',
		'" . (!isset($request->post['name_rw']) ? Kernel::rewrite($request->post['name']) : $request->post['name_rw']) . "',
		'" . (isset($request->post['description']) ? $request->post['description'] : "") . "'");
		
		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'add_notice_success'));
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'error_db_return_error') . '<br/>' . self::$Error);
			return false;
		}
		
	}
	
	public function save( $id )
	{
		global $app_path, $request;
		
		$this->verify();
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , LA::get('cms','error_form_return_error') . implode("<br/>" , self::$Error));
			return false;
		}
		
		$result = Db::update( self::$table , "priority = '" . $request->post['priority'] . "',
		" . (isset($request->post['parent']) ? "parent = '" . $request->post['parent'] . "'," : "") . "
		name = '" . (isset($request->post['name']) ? $request->post['name'] : '') ."',
		name_rw = '" . (!isset($request->post['name_rw']) ? Kernel::rewrite($request->post['name']) : $request->post['name_rw']) ."',
		description = '" . $request->post['description'] . "'" , "id='".$id."'");
		
		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'update_notice_success'));
			return true;
		} else {
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'error_db_return_error') . '<br/>' . self::$Error);
			return false;
		}
		
	}
	
	public function delete( $id )
	{
		if( Db::check( self::$table , "id='" . $id ."'") == true) {
			Db::delete( self::$table , "id= '" . $id . "'");
			News::delete_by_category( $id );
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'delete_notice_success'));
			return true;
		} else {
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'error_db_return_error') . '<br/>' . self::$Error);
			return false;
		}
	}
	
	public static function getIdByRewrite( $rewrite ) 
	{
		$result = Db::row("id" , self::$table , "WHERE name_rw='" . $rewrite . "'");
		return $result['id'];
	}
	
	public static function getNameByRewrite( $rewrite ) 
	{
		$result = Db::row("name" , self::$table , "WHERE name_rw='" . $rewrite . "'");
		return $result['name'];
	}
	
	public static function getRewriteById( $id ) 
	{
		$result = Db::row("name_rw" , self::$table , "WHERE id='" . $id . "'");
		return $result['name_rw'];
	}
	
	public static function getNameById( $id ) 
	{
		$result = Db::row("name" , self::$table , "WHERE id='" . $id . "'");
		return $result['name'];
	}
	
}