<?php
use \Core\Backend\Navigation as Navigation;

class Content {

	public static $Error;
	public static $submenu = false;
	public static $components;

	protected static $table = "content";
	protected static $table_nl = "content";

	public static $UploadDir = "content";
	public static $UploadUrl, $UploadPath;

	public $post, $get;

	public function __construct()
	{
		//Kernel::addAdminMenu("content", "Pozycje menu", "admin/content/list/", null, true);

		Navigation::submenu('content' , 'Pozycje menu' , 'content/list/');

		global $config, $app_url, $app_path, $request;

		self::$table = $config['db_prefix'] . self::$table . "_" . Language::$selected;
		self::$table_nl = $config['db_prefix'] . self::$table_nl . "_";

		self::$UploadUrl  = $app_url  . 'userfiles/' . self::$UploadDir . '/';
		self::$UploadPath = $app_path . 'userfiles/' . self::$UploadDir . '/';

		$this->post = $request->post;
		$this->get = $request->get;
	}

	public function search( $q )
	{
		return Db::query("SELECT * FROM " . self::$table . " WHERE name LIKE '%" . $q . "%' OR text LIKE '%" . $q . "%' AND visibility='TRUE'");
	}

	public function get( $id = null, $parent = false )
	{
		if( $parent == false) {
			if(is_null($id)) {
				$Result = Db::exec("*" , self::$table , "WHERE parent='0' ORDER BY section,priority");
				if(!empty($Result)) {
					foreach($Result as $k=>$i) {
						$Result[$k]['section'] = explode(";" , $i['section']);
					}
					return $Result;
				}
			} else {
				$r = Db::row("*" , self::$table , "WHERE id='".$id."'");
				$r['edit'] = true;
				$r['text'] = stripslashes($r['text']);
				$r['text'] = html_entity_decode($r['text']);
				$r['section'] = explode(";" , $r['section']);
				$r['component'] = Kernel::getComponentID( $r['component'] );
				return $r;
			}
		} else {
			return Db::exec("*" , self::$table , "WHERE parent='".$id."' ORDER BY priority");
		}
	}

	public function verify()
	{
		if(empty($this->post['name'])) {
			self::$Error[] = "wpisz nazwę pozycji menu";
		}
	}

	public function add()
	{
		global $request, $app_path;

		$this->verify();
		if(!empty($_FILES['photo']['name'])) {
			$photo = System::upload( $_FILES['photo'], [
				'upload_dir' => $app_path . 'userfiles/content/',
				'filename' => Language::$selected . '_p_' . Kernel::rewrite($request->post['name'])
			]);
		}

		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Wystąpiły błędy w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		$parent = (isset($request->post['parent']) ? $request->post['parent'] : '0');
		if( strpos( $request->post['rewrite'], 'http' ) !== false) {
			$rewrite = $request->post['rewrite'];
		} else {
			$rewrite = (!empty($request->post['rewrite']) ? Kernel::rewrite($request->post['rewrite']) : Kernel::rewrite($request->post['name']));
		}

		$component = null;
		if((!empty($request->post['component'])) && ($request->post['component'] > 0)) {
			$component = Kernel::readComponents($request->post['component']);
		}

		if(!empty($request->post['section'])) {
			$section = implode(";" , $request->post['section']) . ";";
		} else {
			$section = '';
		}

		if(!empty($request->post['priority'])) {
			$priority = $request->post['priority'];
		} else {
			$priority = $this->lastPriority( $parent );
		}

		$parent = (!empty($request->post['parent']) ? $request->post['parent'] : 0);

		$result = Db::insert( self::$table , "null,
		'" . $section . "',
		'" . $parent . "',
		'" . $priority . "',
		'" . $request->post['name'] ."',
		'" . (!empty($request->post['title']) ? $request->post['title'] : '') ."',
		'" . $rewrite . "',
		'" . (!empty($request->post['text']) ? $request->post['text'] : '') ."',
		" . (!empty($component) ? "'" . $component . "'" : 'NULL') . ",
		'" . (!empty($request->post['redirect']) ? $request->post['redirect'] : '0') . "',
		'" . (!empty($request->post['status']) ? $request->post['status'] : 'FALSE') . "',
		'" . (!empty($request->post['visibility']) ? $request->post['visibility'] : 'FALSE') . "',
		'" . (!empty($request->post['editable']) ? $request->post['editable'] : 'TRUE') . "',
		'" . (!empty($request->post['meta_title']) ? $request->post['meta_title'] : $request->post['name']) . "',
		'" . (!empty($request->post['meta_keys']) ? $request->post['meta_keys'] : ''). "',
		'" . (!empty($request->post['meta_desc']) ? $request->post['meta_desc'] : '') . "',
		'" . (!empty($request->post['meta_index']) ? $request->post['meta_index'] : 'FALSE') ."',
		'" . (!empty($request->post['meta_follow']) ? $request->post['meta_follow'] : 'FALSE') . "',
		'FALSE'");


		if($result == true) {
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'add_notice_success'));
			return true;
		}

		self::$Error = Db::error();
		Kernel::setMessage("ERROR" , LA::get('cms' , 'error_db_return_error') . '<br/>' . self::$Error);
		return false;
	}

	public function save( $id )
	{
		global $request, $app_path;

		$this->verify();

		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Wystąpiły błędy w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		$parent = (isset($request->post['parent']) ? $request->post['parent'] : '0');
		if( strpos( $request->post['rewrite'], 'http' ) !== false) {
			$rewrite = $request->post['rewrite'];
		} else {
			$rewrite = (!empty($request->post['rewrite']) ? Kernel::rewrite($request->post['rewrite']) : Kernel::rewrite($request->post['name']));
		}

		$component = null;
		if((!empty($request->post['component'])) && ($request->post['component'] > 0)) {
			$component = Kernel::readComponents($request->post['component']);
		}

		if(!empty($request->post['section'])) {
			$section = implode(";" , $request->post['section']) . ";";
		} else {
			$section = '';
		}

		$r = Db::save( self::$table , "section = '" . $section . "',
		parent = '" . (isset($request->post['parent']) ? $request->post['parent'] : 0) . "',
		priority = '" . $request->post['priority'] . "',
		name = '" . $request->post['name'] . "',
		title = '" . (!empty($request->post['title']) ? $request->post['title'] : '') ."',
		rewrite = '" . $rewrite . "',
		text = '" . (isset($request->post['text']) ? addslashes($request->post['text']) : '') ."',
		component = " . (!empty($component) ? "'" . $component . "'" : 'NULL') . ",
		redirect = '" . (isset($request->post['redirect']) ? $request->post['redirect'] : '0') . "',
		status = '" . (isset($request->post['status']) ? $request->post['status'] : 'FALSE') . "',
		visibility = '" . (isset($request->post['visibility']) ? $request->post['visibility'] : 'FALSE') . "',
		editable = '" . (isset($request->post['editable']) ? $request->post['editable'] : 'TRUE') . "',
		meta_title = '" . (!empty($request->post['meta_title']) ? $request->post['meta_title'] : '') . "',
		meta_keys = '" . (isset($request->post['meta_keys']) ? $request->post['meta_keys'] : ''). "',
		meta_desc = '" . (isset($request->post['meta_desc']) ? $request->post['meta_desc'] : '') . "',
		meta_index = '" . (isset($request->post['meta_index']) ? $request->post['meta_index'] : 'FALSE') ."',
		meta_follow = '" . (isset($request->post['meta_follow']) ? $request->post['meta_follow'] : 'FALSE') . "'"  , "id='" . $id . "'");

		if($r == true) {
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'update_notice_success'));
			return true;
		}

		self::$Error = Db::error();
		Kernel::setMessage("ERROR" , LA::get('cms' , 'error_db_return_error') . '<br/>' . self::$Error);
		return false;
	}

	public function delete( $id )
	{
		if( Db::check( self::$table , "id='" . $id ."'") == true) {

			$this->delete_submenu( $id );
			Db::delete( self::$table , "id= '" . $id . "'");
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'delete_notice_success'));
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , LA::get('cms' , 'error_db_return_error') . '<br/>' . self::$Error);
			return false;
		}
	}

	public function delete_submenu( $id )
	{
		$Submenu1 = Db::exec("*" , self::$table , "WHERE parent='" . $id . "'");
		if(!empty($Submenu1)) {
			foreach($Submenu1 as $k=>$i) {
				Db::delete( self::$table , "WHERE parent='" . $i['id'] . "'");
			}
			Db::delete( self::$table , "WHERE parent='" . $id . "'");
		}
	}

	public static function getBySection( $section, $parent = null )
	{
		if( is_null( $parent )) {
			$parent = 0;

			$Result = Db::exec("*" , self::$table , "WHERE " . (($section==0) ? "(section='0' OR section='')" : "section LIKE '%" . $section . ";%'") . " AND parent='" . $parent . "' ORDER BY priority");

		} else {
			$Result = Db::exec("*" , self::$table , "WHERE parent='" . $parent . "' ORDER BY priority");
		}

		if(!empty($Result)) {
			foreach($Result as $k=>$i) {
				$Result[$k]['section'] = explode(";" , $i['section']);
			}
			return $Result;
		}
	}

	public static function hasSubmenu( $id )
	{
		return Db::check(self::$table , "parent='" . $id . "' AND visibility='TRUE'");
	}

	public static function getParentName( $id, $as_parent = false )
	{
		if( $as_parent == false ) {
			$Row = Db::row("name" , self::$table , "WHERE id = '" . $id . "'");
		} else {
			$Row = Db::row("name" , self::$table , "WHERE parent = '" . $id . "'");
		}
		if(!empty($Row)) {
			return $Row['name'];
		}
	}

	public static function getLink ( $id, $pid = null )
	{
		$Result = Db::row("rewrite,component" , self::$table , "WHERE id='" . $id . "'");
		if((!empty($Result['component'])) && (!empty($pid))) {

			switch($Result['component']) {
				case "prices":
					return 'c/' . Projects::_get_rewrite_name( $pid ) .  '-i' . $pid;
				break;
			}
		}
		return $Result['rewrite'];
	}

	public function priority( $parent )
	{
		$Result = Db::row( "priority" , self::$table , "WHERE parent='" . $parent . "' ORDER BY priority DESC" );
		if(!empty($Result)) {
			return $Result['priority'] + 1;
		} else {
			return 1;
		}
	}

	public function getParents( $parent )
	{
		if(isset($parent)) {
			$parent = Db::row("parent" , self::$table , "WHERE id='" . $parent . "'");
			return Db::exec("*" , self::$table , "WHERE parent='" . $parent['parent'] . "' ORDER BY priority");
		}
		return false;
	}

	public static function getChilds( $id, $select = true )
	{
		$Result = Db::exec("*" , self::$table, "WHERE parent='" . $id . "'  ORDER BY priority");
		if(!empty($Result)) {
			if($select == true) {
				foreach($Result as $i) {
					$s[] = array("id" => $i['id'], "name" => $i['name']);
				}
				return $s;
			}

			return $Result;
		}
	}

	public static function get_select( $parent = 0 )
	{
		$Result = Db::exec("*" , self::$table , "WHERE parent='" . $parent . "' ORDER BY priority");
		if(!empty($Result)) {
			foreach( $Result as $k=>$i ) {
				if( Db::check( self::$table , "parent='" . $i['id'] . "'") == true ) {
					$Select[] = [
						'id' => $i['id'],
						'name' => $i['name'],
						'optgroup' => true,
						'childs' => self::get_select( $i['id'] )
					];
				} else {
					$Select[] = [
						'id' => $i['id'],
						'name' => $i['name'],
						'optgroup' => false,
						'childs' => false
					];
				}
			}

			if(!empty($Select)) {
				return $Select;
			}
		}
	}

	public static function make_link( $id, $pid = null )
	{
		global $app_url;
		return $app_url . Language::$selected . '/' . self::getLink( $id, $pid );
	}

	public static function getByComponent( $component )
	{
		$r = Db::row("id,parent,name,title,rewrite,text,meta_title,meta_desc,meta_keys" , self::$table, "WHERE component='" . $component . "'");
		if(!empty($r['parent'])) {
			$r2 = Db::row("name as parent_name" , self::$table , "WHERE id='" . $r['parent'] . "'");
			return array_merge( $r, $r2 );
		}

		return $r;
	}

	public static function getParentLink( $parent )
	{
		$r = Db::row("name" , self::$table , "WHERE id='".$parent."'");
		if(isset($r)) {
			return Kernel::rewrite( $r['name'] );
		}
	}

	public static function getTextByRewrite( $rewrite )
	{
		$Result = $Result = Db::row("*" , self::$table , "WHERE rewrite='".$rewrite."'");
		if(isset($Result)) {
			return html_entity_decode(stripslashes(stripslashes(html_entity_decode($Result['text']))));
		}
	}

	public static function getSubmenuByRewrite( $rewrite )
	{
		$Result = Db::row("*" , self::$table , "WHERE rewrite='" . $rewrite . "'");
		return Db::exec("*" , self::$table , "WHERE parent = '" . $Result['id'] . "' AND visibility='TRUE' ORDER BY priority");
	}

	public static function getSidemenu( $id, $parent )
	{
		$Result1 = Db::row("id,parent,rewrite,name" , self::$table , "WHERE id='" . $parent . "'");
		if($Result1['parent'] > 0) {
			$Menu = Db::exec("id,parent,rewrite,name" , self::$table , "WHERE parent='" . $Result1['parent'] . "' AND visibility='TRUE' ORDER BY priority");
			if(!empty($Menu)) {
				foreach($Menu as $k=>$i) {
					if( $parent == $i['id'] ) {
						$Menu[$k]['submenu'] = Db::exec("*" , self::$table , "WHERE parent='" . $parent . "' AND visibility='TRUE' ORDER BY priority");
					}
				}

				return $Menu;
			}
		} else {
			$Menu = Db::exec("id,parent,rewrite,name" , self::$table , "WHERE parent='" . $parent . "' AND visibility='TRUE' ORDER BY priority");
			if(!empty($Menu)) {
				foreach($Menu as $k=>$i) {
					if( $id == $i['id'] ) {
						$Menu[$k]['submenu'] = Db::exec("*" , self::$table , "WHERE parent='" . $i['id'] . "' AND visibility='TRUE' ORDER BY priority");
					}
				}

				return $Menu;
			}
			return $Menu;
		}
	}

	private static $level = 0;
	private static $last;

	public static function checkLevel( $id )
	{
		$Result = Db::row("*" , self::$table , "WHERE id='" . $id . "'");
		if($Result['parent'] == 0) {
			return self::$level;
		} else {
			self::$level++;
			self::$last = $Result['id'];
			self::checkLevel( $Result['parent'] );;
		}
	}

	public static function getSubmenu( $parent, $section = null, $result = true )
	{
		global $app_url, $Pages;

		if( (isset($result)) && ($result == true) ) {
			if( is_null( $section )) {
				$r = Db::exec("id,parent,name,rewrite,visibility" , self::$table , "WHERE parent='" . $parent . "' AND visibility='TRUE' ORDER BY priority");
			} else {
				$r = Db::exec("id,parent,name,rewrite,visibility" , self::$table , "WHERE section LIKE '%" . $section . ";%' AND parent='" . $parent . "' AND visibility='TRUE' ORDER BY priority");
			}

			if( empty( $r )) {
				return;
			}

			foreach($r as $k=>$i) {
				$r[$k]['rewrite'] = $app_url . self::getParentLink($i['parent']) . '/' . $i['rewrite'];
				if($Pages['id'] == $i['id'] ) {
					$r[$k]['selected'] = true;
				}
			}

			return $r;
		} else {
			return Db::check( self::$table, "parent='" . $parent . "' AND visibility='TRUE'");
		}
	}

	public static function getMenu( $section = null, $parent = null )
	{
		if(!empty($section)) {
			return Db::exec("*" , self::$table , "WHERE section LIKE '%" . $section . ";%' AND parent=0 AND visibility='TRUE' ORDER BY priority");
		} else {
			return Db::exec("*" , self::$table , "WHERE parent='". $parent . "' AND visibility='TRUE' ORDER BY priority");
		}
	}

/**
 *	Pobieranie pozycji menu na podstawie ścieżki (nazwa rewrite)
 *	@params $path string nazwa rewrite w bazie
 *
 *	@return array
 */
	public function getByPath( $path, $parent_page_id = null, $gallery_limit = null )
	{
		if(empty($path)) {
			$r = Db::row("*" , self::$table , "WHERE rewrite='index'");
			return $r;
		}

		$r = Db::row("*" , self::$table , "WHERE rewrite='" . $path . "'" . (!empty($parent_page_id) ? " AND parent='" . $parent_page_id . "'" : ""));
		if(empty($r)) {
			return;
		}

		$r['text'] = stripslashes($r['text']);
		$r['text'] = html_entity_decode($r['text']);

		if($r['parent'] > 0 ) {
			$r['parent_name'] = Db::row("name" , self::$table , "WHERE id='" . $r['parent'] . "'");
		}

		return $r;
	}

	public function getByModule( $name )
	{
		return Db::row("*" , self::$table , "WHERE component = '" . $name . "'");
	}

	public function getMain()
	{
		$r = Db::row("*" , self::$table , "WHERE main='TRUE' LIMIT 0,1");
		if(empty($r)) {
			return;
		}

		return $r;
	}

	public function lastPriority( $parent )
	{
		$Result = Db::row("priority" , self::$table , "WHERE parent='" . $parent . "' ORDER BY id DESC");
		$priority = $Result['priority'] + 1;
		return $priority;
	}

	public static function checkVisibility( $rewrite )
	{
		$result = Db::row("*" , self::$table , "WHERE rewrite='" . $rewrite . "'");
		return $result['visibility'];
	}

/**
 *	Pobieranie pozycji menu dla funkcji generate
 *	@params $section int numer ID sekcji lub null
 *	@params $parent int numer ID rodzica lub null
 */

	protected static function get_for_generate( $section = null, $parent = null )
	{
		if(!empty( $section ) && is_numeric( $section ))
		{
			$query[] = "section LIKE '%" . $section . ";%'";
		}

		if(!empty( $parent ) && is_numeric( $parent )) {
			$query[] = "parent='" . $parent . "'";
		}

		return Db::exec("*" , self::$table , "WHERE " . (!empty($query) ? implode(" AND " , $query) : "") . " AND visibility='TRUE' ORDER BY priority");
	}

	protected function generate_by_template( $o, $template, $list )
	{
		global $route;

		$html[] = '<ul' . (!empty($o['class']) ? ' class="' . $o['class'] . '"' : '') . '>';
		foreach( $list as $k=>$i ) {

			if($route->isLanguage == true ) {
				$link_prefix = '/' . $route->language . '/';
			} else {
				$link_prefix = '/';
			}

			if($prow = Db::row("rewrite" , self::$table , "WHERE id='" . $i['parent'] . "'")) {
				$link = $link_prefix . $prow['rewrite'] . '/' . $i['rewrite'];
			} else {
				$link = $link_prefix . $i['rewrite'];
			}

			$tpl = str_replace("{link}" , $link, $template);
			$tpl = str_replace("{name}" , $i['name'], $tpl);
			$html[] = $tpl;
			unset($tpl);
		}
		$html[] = '</ul>';

		return implode(PHP_EOL , $html);
	}

	public static function generate2( $o )
	{
		if(empty($o)) {
			die( __METHOD__	. ' empty options ');
		}

		$section 	= (!empty($o['section']) ? $o['section'] : null);
		$parent  	= (!empty($o['parent']) ? intval($o['parent']) : 0);
		$class 	 	= (!empty($o['class']) ? $o['class'] : 'nav navbar-nav ');
		$dropdown	= (isset($o['dropdown']) ? true : false);
		$onepage	= (isset($o['onepage']) ? true : false);
		$template	= (!empty($o['template']) ? $o['template'] : null);

		// Pobieranie danych z bazy
		if(!empty($section)) {
			$q[] = "parent='" . $parent . "'";
		}

		if(!empty($parent)) {
			$q[] = "parent='" . $parent . "'";
		}

		$list = Db::exec("id, name, rewrite, redirect, parent" , self::$table , "WHERE " . (!empty($q) ? implode(" AND " , $q) . " AND " : "") .
		"visibility='TRUE' ORDER BY priority");
		if(empty($list)) {
			return;
		}

		// Wyświetlanie według wzoru template'a
		if(!empty($template)) {
			return Content::generate_by_template( $o, $template, $list );
		}

		global $route;

		// Wyświetlanie tradycyjnego menu
		$html[] = '<ul' . (!empty($class) ? ' class="' . $class . '"' : '') . '>';
		foreach( $list as $k=>$i ) {
			$active = false; // czy wybrana strona jest aktywna
			$dropdown = false; // czy strona posiada podmenu
			$target = null; // czy target aktywny

			if($route->isLanguage == true ) {
				$link_prefix = '/' . $route->language . '/';
			} else {
				$link_prefix = '/';
			}

			// Jeżeli ustawiono pełny adres url
			if(strpos( $i['rewrite'] , 'http' ) !== false ) {
				$link = $i['rewrite'];
				$target = ' target="_blank"';
			}

			// Czy strona jest aktywna
			if( $route->path == $i['rewrite'] ) {
				$active = true;
			}

			// Automatyczne przekierowanie gdy ustawiono
			if( !empty($i['redirect']) && $prow = Db::row("rewrite" , self::$table , "WHERE id='" . $i['redirect'] . "'")) {
				$link = $link_prefix . $prow['rewrite'];
			}

			// Jeżeli strona nie ma podmenu
			if( $prow = Db::exec("id,rewrite,name" , self::$table , "WHERE parent='" . $i['id'] . "'") == false ) {
				$html[] = '<li class="nav-item"><a class="nav-link' . (!empty($active) ? ' ' . $active : '') . '" ' .
				'href="' . $link . '"' .
				(!empty($target) ? $target : '') . '>' . $i['name'] . '</a></li>';
			} else {
				$html[] = '<li class="nav-item dropdown">';
				$html[] = '<a class="nav-link' . (!empty($active) ? ' ' . $active : '') . ' dropdown-toggle" data-toggle="dropdown"' .
				' href="#" aria-haspopup="true" aria-expanded="false">' . $i['name'] . '</a>';
				$html[] = '<div class="dropdown-menu">';
				foreach( $prow as $sk=>$si ) {
					$active = ($route->path == $si['rewrite'] ? true : false);

					$html[] = '<a class="dropdown-item' . (!empty($active) ? ' ' . $active : '') . '" href="' . $si['link'] . '">' .
					$si['name'] . '</a>';

					$active = false;
				}
				$html[] = '</div></li>';
			}

			$active = false;
			$target = false;
		}
		$html[] = '</ul>';

		if(!empty($html)) {
			return implode( PHP_EOL , $html );
		}
	}

	public static function generate( $options = null )
	{
		if(!empty($options)) {
			$section = (!empty($options['section']) ? $options['section'] : null);
			$align = (isset($options['align']) ? " " . $options['align'] : "");
			$class = (!empty($options['class']) ? ' class="' . $options['class'] . '"' : ' class="nav navbar-nav' . $align . '"');
			$dropdown = (isset($options['dropdown']) ? $options['dropdown'] : false);
			$parent = (isset($options['parent']) ? intval( $options['parent']) : null);
			$main = (isset($options['main']) ? $options['main'] : null);
			$language = (isset($options['language']) ? $options['language'] : null);
			$template = (!empty($options['template']) ? $options['template'] : null);
			$onepage = (!empty($option['onepage']) ? $option['onepage'] : false);
		}

		global $app_url, $app_request_url, $route, $Pages, $config;
		$MenuLevel0 = self::get_for_generate($section, $parent);
		if(is_array($MenuLevel0)) {

			// Generowanie prostej listy ul->li na podstawie templatki
			// podanej w $template
			if(!empty($template)) {
				$html[] = "<ul" . $class . ">";
				foreach($MenuLevel0 as $k0=>$i0) {
					$tpl = $template;
					if( $route->isLanguage == true ) {
						$tpl = str_replace("{link}" , '/' . $route->language . '/' . $i0['rewrite'], $tpl);
					} else {
						if($prow = Db::row("name,rewrite,parent" , self::$table , "WHERE id='" . $i0['parent'] . "'")) {
							$i0['rewrite'] = $prow['rewrite'] . '/' . $i0['rewrite'];
						}
						$tpl = str_replace("{link}" , '/' . $i0['rewrite'], $tpl);
					}
					$tpl = str_replace("{name}" , $i0['name'], $tpl);
					$html[] = $tpl;
					unset($tpl);
				}
				$html[] = "</ul>";

				return implode("",$html);
			}

			$add_class = "";
			$main_item = false;

			$active = false;

			$html[] = '<ul' . (!empty($class) ? $class : "") . '>';
			foreach($MenuLevel0 as $k0=>$i0) {

				// Jeżeli link zawiera pełny adres URL
				if( strpos( $i0['rewrite'], "http" ) !== false ) {
					$href_link = $i0['rewrite'];
					$target = '_blank';
				}

				// Jeżeli strona jest wielojęzyczna, kieruj na odpowiedni URL
				$href_link = $app_url . $i0['rewrite'];


				// Ustawienie klasy active na odpowiednim LI
				if( ($route->isMain == true && $i0['main'] == "TRUE") && $i0['rewrite'] == 'start' ) {
					$active = true;
					$main_item = true;
				}

				if($route->path == $i0['rewrite'] || $route->path == '/' . $i0['rewrite']) {
					$active = true;
				}

				// Ustawienie przekierowania
				if((isset($i0['redirect'])) && ($i0['redirect'] > 0)) {
					$redirect = Db::row("rewrite" , self::$table, "WHERE id='" . $i0['redirect'] . "'");
					unset($redirect);
				}

				// Komponenty
				if(!empty($i0['component'])) {
					$class_name = ucfirst( $i0['component'] );
				}

				if( $main_item == true ) {
					$a_class_tag = "nav-link";
					$li_class_tag = "nav-item";
					$active = true;
				} elseif( $section == 3 ) {
					$a_class_tag = "";
					$li_class_tag = "";
				} else {
					$a_class_tag = "nav-link";
					$li_class_tag = "nav-item";
				}

				$has_new = null;

				$name_check = $i0['name'];
				$submenu = self::getSubmenu( $i0['id'], null, true );
				if(empty($submenu)) {
					if(empty($i0['parent']) || !isset($i0['parent'])) {

						$html[] = '<li class="' . $li_class_tag . ($active == true ? ' active' : '') . '">';

						$html[] = '<a class="' . $a_class_tag . '" href="' . $href_link . '"' .
									(!empty($target) ? ' target="' . $target . '"' : '') . ">" . $i0['name'] . '</a>';

						$html[] = '</li>';

					}
				} else {

					$html[] = '<li class="' . $li_class_tag . ' dropdown">';
					$html[] = '<a class="' . $a_class_tag . ($active == true ? ' active' : '') . ' dropdown-toggle" data-toggle="dropdown" href="#"' .
								(!empty($target) ? ' target="' . $target . '"' : '') . ' aria-haspopup="true" aria-expanded="false">' . '<span>' . $i0['name'] . '</span> ' .'</a>';
					$html[] = '<ul class="dropdown-menu">';
					foreach($submenu as $child) {
						$active_d = ($child['id'] == $Pages['id'] ? true : false);

						$html[] = '<li><a class="dropdown-item' . ($active_d == true ? ' active' : '') .
								'" href="' . $child['rewrite'] . '">' . $child['name'] . '</a></li>';

					}
					$html[] = '</ul>';

					$html[] = '</li>';
				}
				$active = false;
				$main_item = false;
			}
			$html[] = '</ul>';
		}

		if(isset($html)) {
			echo implode($html);
			unset($html);
		}
	}


	public static function generateSubmenu( $submenu, $option = null )
	{
		if(!empty($submenu)) {
			if(!empty($option['header'])) { $html[] = '<h3 class="header visible-lg visible-md">' . $option['header'] . '</h3>'; }

			$html[] = '<div class="navbar-header"><button type="button" class="navbar-toggle collapsed navbar-toggle-dark" data-toggle="collapse" data-target="#menu-stacked" aria-expanded="false"><span>ROZWIŃ MENU</span></button></div>';

			$html[] = '<div class="collapse navbar-collapse" id="menu-stacked"><ul class="nav nav-pills nav-stacked">';

			foreach($submenu as $i)
			{
				 $html[] = '<li role="presentation" ' . ($option['active'] == $i['id'] ? 'class="active"' : '') .
				 '><a href="/' . $i['rewrite'] . '"><span class="fa fa-angle-double-right"></span> ' . $i['name'] . '</a></li>';
			}
			$html[] = '</ul></div>';

			if(!empty($html)) {
				return implode(PHP_EOL , $html);
			}
		}
	}
}
