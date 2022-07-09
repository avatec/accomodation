<?php
use \Core\Backend\Navigation as Navigation;

class Sections {

	protected static $table = "content_sections";
	public static $Error;

	public function __construct()
	{
		LA::load('content');
		LA::load('content/sections');

		global $config;

		if(isset($_SESSION['lng']['code'])) {
			$langCode = $_SESSION['lng']['code'];
		} else {
			$langCode = "pl";
		}

		Navigation::menu('4' , 'content' , LA::get('content' , 'menu_content') , null , 'fa-list');
		Navigation::submenu('content' , 'Sekcje menu' , 'content/sections/list/');

		//Kernel::addAdminMenu("content", LA::get('content' , 'menu_content'), null, "fa-list", null, false);
		//	Kernel::addAdminMenu("content", "Sekcje menu", "admin/content/sections/list/", null, true);

		self::$table = $config['db_prefix'] . self::$table . "_" . $langCode;

		self::__install();
	}

	protected function __install()
	{
		if( Db::has_table( self::$table ) == false ) {
			$sql[] = "DROP TABLE IF EXISTS `" . self::$table . "`;";
			$sql[] = "CREATE TABLE `" . self::$table . "` (
					  `id` int(11) NOT NULL,
					  `priority` int(11) NOT NULL,
					  `name` varchar(200) NOT NULL,
					  `rewrite` varchar(200) NOT NULL
					  ) ENGINE=InnoDB DEFAULT CHARSET=utf8; COLLATE=utf8_unicode_ci;";
			$sql[] = "ALTER TABLE `" . self::$table . "` ADD PRIMARY KEY (`id`);";
			$sql[] = "ALTER TABLE `" . self::$table . "` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";

			Db::install( $sql );
			Kernel::setMessage("NOTICE" , "Instalacja tabeli bazy danych została zakończona");
		}
	}

	public function get( $id = null )
	{
		if(is_null($id)) {
			return Db::exec("*" , self::$table , "ORDER BY priority");
		} else {
			$Result = Db::row("*" , self::$table , "WHERE id='" . $id . "'");
			$Result['edit'] = true;
			return $Result;
		}
	}

	public static function _get( $id = null )
	{
		if(is_null($id)) {
			return Db::exec("*" , self::$table , "ORDER BY priority");
		} else {
			$Result = Db::row("*" , self::$table , "WHERE id='" . $id . "'");
			$Result['edit'] = true;
			return $Result;
		}
	}

	public static function _get_list()
	{
		$r = Db::exec("*" , self::$table , "ORDER BY priority");
		$r[] = ['id' => 0, 'name' => 'Nieprzypisane'];
		return $r;
	}

	public static function getIdByRewrite( $rewrite )
	{
		$result = Db::row("id" , self::$table , "WHERE rewrite='" . $rewrite . "'");
		return $result['id'];
	}

	public static function getNameByRewrite( $rewrite )
	{
		$result = Db::row("name" , self::$table , "WHERE rewrite='" . $rewrite . "'");
		return $result['name'];
	}

	public static function getRewriteById( $id )
	{
		$result = Db::row("name_rw" , self::$table , "WHERE id='" . $id . "'");
		return $result['name_rw'];
	}

	public static function getName( $id, $as_label = true )
	{
		if(is_array($id)) {
			foreach($id as $i) {
				$result = Db::row("name" , self::$table , "WHERE id='" . $i . "'");
				if(!empty($result['name'])) {
					$h[] = $result['name'];
				}
			}
			if(!empty($h)) {
				if( $as_label == true ) {
					return '<span class="label label-warning">' . implode('</span>,<span class="label label-warning">' , $h) . '</span>';
				} else {
					return implode("," , $h);
				}
			}
		} else {
			$result = Db::row("name" , self::$table , "WHERE id='" . $id . "'");
			if(empty($result)) {
				return "nie przypisano do sekcji";
			} else {
				return $result['name'];
			}
		}

	}

	public function lastPriority()
	{
		$Row = Db::row("*" , self::$table , "ORDER BY id DESC");
		$priority = $Row['priority'];
		return $priority+1;
	}

	public function verify()
	{
		global $request;

		if(empty($request->post['name'])) {
			self::$Error[] = "wprowadź nazwę sekcji menu";
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
		'" . (!empty($request->post['priority']) ? $request->post['priority'] : $this->lastPriority()) . "',
		'" . $request->post['name'] . "',
		'" . (empty($request->post['rewrite']) ? Kernel::rewrite($request->post['name']) : $request->post['rewrite']) . "'");

		if(!empty($result)) {
			return true;
		} else {
			return false;
		}

	}

	public function save( $id )
	{
		global $request;

		$this->verify();
		if(!empty(self::$Error)) {
			return false;
		}

		$result = Db::update( self::$table , "priority = '" . $request->post['priority'] . "'" .
		(!empty($request->post['name']) ? ",name = '" . $request->post['name'] . "'" : "") .
		(!empty($request->post['rewrite']) ? ",rewrite = '" . $request->post['rewrite'] . "'" : ",rewrite = '" . Kernel::rewrite($request->post['name']) . "'") ,
		"id='" . $id . "'");

		if(!empty($result)) {
			return true;
		} else {
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
