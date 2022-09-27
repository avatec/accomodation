<?php
use \Core\Backend\Navigation as Navigation;

/**
 * News class
 *
 * @package		Modules
 * @subpackage	News
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

LA::load('news');
LA::load('news/category');

class News {

	protected static $UploadFolder = "news";
	protected static $table = "news";
	protected static $table_nl = "news";

	public static $Error;
	public static $UploadUrl;
	public static $UploadPath;

	public function __construct()
	{
		global $app_url, $app_path, $config, $route;

		self::$table = $config['db_prefix'] . self::$table . "_" . Language::get_selected();
		self::$table_nl = $config['db_prefix'] . self::$table_nl . "_";

		self::$UploadUrl = $app_url . "userfiles/" . self::$UploadFolder . "/";
		self::$UploadPath = $app_path . "userfiles/" . self::$UploadFolder . "/";

		Language::load("news");

		Navigation::menu( 12, 'news' , LA::get('news' , 'menu_name'), null, 'fa-list');
		Navigation::submenu('news' , LA::get('news/category' , 'menu_name'), "news/category/list/");

		//Kernel::addAdminMenu("news", LA::get('news' , 'menu_name'), null, "fa-list", null, false);
		//Kernel::addAdminMenu("news", LA::get('news/category' , 'menu_name'), "admin/news/category/list/", null, true);

		// Rejestracja komponentu do menu
		Kernel::registerComponent( 4 , "Lista aktualności" , "news/list");

		$this->register( $route );
	}

	protected function register( $route )
	{
		$route->get('(news)\/(view)\/:string-i:id' , [
			'module' => 'news', 'file' => 'news', 'command' => 'view', 'id' => '$4'
		]);

		$route->get('(news)\/:string-c:id' , [
			'module' => 'news', 'file' => 'news', 'command' => 'list-by-category', 'id' => '$3'
		]);
	}

	public function get_all( $l )
	{
		return Db::exec("*" , self::$table_nl . $l , "WHERE status='TRUE'");
	}

	public function get( $id = NULL )
	{
		global $app_path;

		if(is_null($id)) {
			$Result = Db::exec("*" , self::$table , "ORDER BY create_date DESC");
			if(!empty($Result)) {
				foreach($Result as $key=>$i) {
					if( !file_exists( self::$UploadPath . "icon/" . $i['icon'])) {
						$Result[$key]['icon'] = null;
					}
				}
				return $Result;
			}
		} else {
			global $app_url;

			$Result = Db::row("*" , self::$table , "WHERE id='" . $id . "'");
			$Result['edit'] = true;
			$Result['topic'] = Kernel::html_decode( $Result['topic'] );
			$Result['preface'] = Kernel::html_decode( $Result['preface'] );
			$Result['content'] = Kernel::html_decode( $Result['content'] );
			if( !file_exists( self::$UploadPath . "icon/" . $Result['icon'])) {
				$Result['icon'] = null;
			}
			// checking next
			$next = Db::row("*" , self::$table , "WHERE id=( SELECT min(id) from " . self::$table . " WHERE id > '" . $id . "' AND category='" . $Result['category'] . "')");
			if(!empty($next)) {
				$Result['next_url'] = $app_url . "news/view/" . Kernel::rewrite($next['topic']) . "-i" . $next['id'];
			}
			$prev = Db::row("*" , self::$table , "WHERE id=( SELECT max(id) from " . self::$table . " WHERE id < '" . $id . "' AND category='" . $Result['category'] . "')");
			if(!empty($prev)) {
				$Result['prev_url'] = $app_url . "news/view/" . Kernel::rewrite($prev['topic']) . "-i" . $prev['id'];
			}
			return $Result;
		}
	}

	public function search( $q )
	{
		global $app_url;

		$qa = explode(" " , $q);
		if(!empty($qa)) {
			foreach($qa as $i) {
				$query[] = "(topic LIKE '%" . $i . "%' OR preface LIKE '%" . $i . "%' OR content LIKE '%" . $i . "%')";
			}
		} else {
			$query[] = "(topic LIKE '%" . $q . "%' OR preface LIKE '%" . $q . "%' OR content LIKE '%" . $i . "%')";
		}
		if(empty($query)) {
			return false;
		}

		$Result = Db::exec("*" , self::$table , "WHERE " . (!empty($query) ? implode( " AND " , $query) : "") . " AND status='TRUE'");
		if(!empty($Result)) {
			foreach($Result as $k=>$i) {
				$Result[$k]['name'] = $i['topic'];
				$Result[$k]['link'] = $app_url . "news/view/" . Kernel::rewrite($i['topic']) . "-i" . $i['id'];

				if(!empty($i['preface'])) {
					$Result[$k]['preface'] = html_entity_decode($i['preface']);
					$Result[$k]['preface'] = preg_replace('/.*(.{20}\b'.$q.'\b.{20}).*/s', '$1', Kernel::strip_tags_content($Result[$k]['preface']));
					if(!empty($qa)) {
						foreach($qa as $i) {
							$Result[$k]['text_result'] = preg_replace('/\b' . $i . '\b/i' , '<b class="mark">' . $i . '</b>' , $Result[$k]['preface']);
						}
					}
				}
				if(!empty($i['content'])) {
					$Result[$k]['content'] = html_entity_decode($i['content']);
					$Result[$k]['content'] = preg_replace('/.*(.{20}\b'.$q.'\b.{20}).*/s', '$1', Kernel::strip_tags_content($Result[$k]['content']));
					if(!empty($qa)) {
						foreach($qa as $i) {
							$Result[$k]['text_result'] = preg_replace('/\b' . $i . '\b/i' , '<b class="mark">' . $i . '</b>' , $Result[$k]['content']);
						}
					}
				}

			}
			return $Result;
		}
	}

	public function getSubmenu( $selected_id )
	{
		global $app_url;
		$Row = Db::row("category" , self::$table , "WHERE id='" . $selected_id . "'");
		$category_id = $Row['category'];

		$Result = Db::exec("id,topic" , self::$table , "WHERE category='" . $category_id . "' ORDER BY create_date DESC");
		if(!empty($Result)) {
			foreach($Result as $k=>$i) {
				$Result[$k]['name'] = $i['topic'];
				$Result[$k]['link'] = $app_url . "news/view/" . Kernel::rewrite($i['topic']) . "-i" . $i['id'];
				if($i['id'] == $selected_id) {
					$Result[$k]['selected'] = true;
				}
			}
			return $Result;
		}
	}

	public function getByCategory( $category, $for_website = null )
	{
		global $app_url;

		Paginate::$query = "SELECT * FROM " . self::$table . " WHERE category='" . $category . "'" . (!empty($for_website) ? " AND main='FALSE' AND status='TRUE'" : "") . " ORDER BY create_date DESC";
		Paginate::$perpage = 25;
		$Result = Paginate::make();
		if(!empty($Result)) {
			foreach($Result as $key=>$i) {
				$Result[$key]['preface'] = html_entity_decode(html_entity_decode($i['preface']));
				$Result[$key]['content'] = html_entity_decode(html_entity_decode($i['content']));
				$Result[$key]['link'] = $app_url . "news/view/" . Kernel::rewrite($i['topic']) . "-i" . $i['id'];

				if( !file_exists( self::$UploadPath . "icon/" . $i['icon'])) {
					$Result[$key]['icon'] = null;
				}
			}
			return $Result;
		}
	}

	public function getPaginated( $limit = 5, $category_id = null )
	{
		global $app_url;

		Paginate::$perpage = $limit;
		Paginate::$query = "SELECT * FROM " . self::$table . " WHERE status='TRUE' ORDER BY create_date DESC";
		$Result = Paginate::make();
		if(!empty($Result)) {
			foreach($Result as $k=>$i) {
				$Result[$k]['preface'] = Kernel::html_decode($i['preface']);
				$Result[$k]['topic'] = Kernel::html_decode( $i['topic'] );
				$Result[$k]['content'] = Kernel::html_decode( $i['content'] );
				$Result[$k]['link'] = $app_url . "news/view/" . Kernel::rewrite($i['topic']) . "-i" . $i['id'];

				if( !file_exists( self::$UploadPath . "icon/" . $i['icon'])) {
					$Result[$k]['icon'] = null;
				}
			}
			return $Result;
		}
	}

	public function getPaginatedArchive( $year, $limit = 10 )
	{
		global $app_url;

		Paginate::$perpage = $limit;
		Paginate::$query = "SELECT * FROM " . self::$table . " WHERE create_date LIKE '". $year . "-%' ORDER BY create_date DESC";
		$Result = Paginate::make();
		if(!empty($Result)) {
			foreach($Result as $k=>$i) {
				$Result[$k]['preface'] = Kernel::html_decode($i['preface']);
				$Result[$k]['topic'] = Kernel::html_decode( $i['topic'] );
				$Result[$k]['content'] = Kernel::html_decode( $i['content'] );
				$Result[$k]['link'] = $app_url . "news/view/" . Kernel::rewrite($i['topic']) . "-i" . $i['id'];

				if( !file_exists( self::$UploadPath . "icon/" . $i['icon'])) {
					$Result[$k]['icon'] = null;
				}
			}
			return $Result;
		}
	}

	public function main()
	{
		global $app_url;
		$Main = Db::row("*" , self::$table , "WHERE status='TRUE' AND main='TRUE'");
		if(!empty($Main)) {
			$Main['topic'] = Kernel::html_decode( $Main['topic'] );
			$Main['content'] = Kernel::html_decode( $Main['content'] );
			$Main['link'] = $app_url . "news/view/" . Kernel::rewrite($Main['topic']) . "-i" . $Main['id'];
			$Main['date'] = '<strong>' . date('d' , strtotime($Main['create_date'])) .
			'</strong><br>' . Common::getMonthName(date('m' , strtotime($Main['create_date'])),1);
			if( !file_exists( self::$UploadPath . "icon/" . $Main['icon'])) {
				$Main['icon'] = null;
			}
			return $Main;
		}
	}

	public function last( $limit = null, $only_main = null, $exclude = null )
	{
		global $app_url;

		$Result = Db::exec("*" , self::$table , "WHERE " . (!empty($exclude) ? "id != '" . $exclude . "' AND " : "") . " status='TRUE' AND main='" . (!empty($only_main) ? 'TRUE' : 'FALSE') . "' ORDER BY id DESC " . (!is_null($limit) ? " LIMIT 0," . $limit : ""));
		if(!empty($Result)) {
			foreach($Result as $k=>$i) {
				$Result[$k]['topic'] = Kernel::html_decode( $i['topic'] );
				$Result[$k]['content'] = Kernel::html_decode( $i['content'] );
				$Result[$k]['preface'] = Kernel::html_decode( strip_tags($i['preface']) );
				$Result[$k]['link'] = $app_url . "news/view/" . Kernel::rewrite($i['topic']) . "-i" . $i['id'];
				$Result[$k]['date'] = '<strong>' . date('d' , strtotime($i['create_date'])) .
				'</strong><br>' . Common::getMonthName(date('m' , strtotime($i['create_date'])),1);
				$Result[$k]['category_name'] = NewsCategory::getNameById($i['category']);

				if( !file_exists( self::$UploadPath . "icon/" . $i['icon'])) {
					$Result[$k]['icon'] = null;
				}
			}

			return $Result;
		}
	}

/**
 *	Zwraca wszystkie lata aktualności
 */

	public function getArchive()
	{
		$Rows = Db::exec("create_date" , self::$table, "ORDER BY create_date DESC");
		if(!empty($Rows)) {
			foreach( $Rows as $k=>$i ) {
				$year[] = date('Y' , strtotime($i['create_date']));
			}
			return array_values(array_unique($year));
		}
	}

	public function verify()
	{
		global $request;

		if(empty($request->post['topic'])) {
			self::$Error[] = LA::get('news','verify_topic');
		}
		if(empty($request->post['category'])) {
			self::$Error[] =  LA::get('news','verify_category');
		}
	}

	public function add()
	{
		global $request;

		$this->verify();
		if(!empty($_FILES['icon']['name'])) {
			$icon = $this->_upload($_FILES['icon'], "icon");
		}

		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , LA::get('cms','error_form_return_error') . implode("<br/>" , self::$Error));
			return false;
		}

		$result = Db::insert( self::$table , "null,
		'" . $request->post['topic'] . "',
		'" . (isset($request->post['preface']) ? $request->post['preface'] : '') ."',
		'" . (isset($request->post['content']) ? $request->post['content'] : '') ."',
		'" . (isset($request->post['status']) ? "TRUE" : "FALSE") ."',
		" . (!empty($icon) ? "'" . $icon . "'" : 'NULL') . ",
		'" . $request->post['category'] . "',
		'FALSE',
		" . (isset($request->post['create_date']) ? "'" . $request->post['create_date'] . "'" : "NOW()"));

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
		if(!empty($_FILES['icon']['name'])) {
			$icon = $this->_upload($_FILES['icon'], "icon");
			if(!empty($icon)) {
				if(!empty($request->post['old_icon'])) {
					if(file_exists( self::$UploadPath . "icon/" . $request->post['old_icon'])) {
						unlink( self::$UploadPath . "icon/" . $request->post['old_icon']);
					}
				}
			}
		}

		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , LA::get('cms','error_form_return_error') . implode("<br/>" , self::$Error));
			return false;
		}

		$result = Db::update( self::$table , "topic = '" . $request->post['topic'] . "',
		preface = '" . (isset($request->post['preface']) ? $request->post['preface'] : '') . "',
		content = '" . (isset($request->post['content']) ? $request->post['content'] : '') . "',
		status = '" . (isset($request->post['status']) ? "TRUE" : "FALSE") . "'" .
		(!empty($request->post['category']) ? ",category = '" . $request->post['category'] . "'" : "") .
		(!empty($icon) ? ",icon = '" . $icon . "'" : '') .
		(!empty($request->post['create_date']) ? ",create_date = '" . $request->post['create_date'] . "'" : "") , "id='".$id."'");

		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'update_notice_success'));
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'error_db_return_error') . '<br/>' . self::$Error);
			return false;
		}
	}

	public function delete( $id )
	{
		global $app_path;

		if( Db::check( self::$table , "id='" . $id ."'") == true) {
			$r = Db::row("*" , self::$table , "WHERE id='" . $id . "'");
			if(!empty($r)) {
				if( file_exists( self::$UploadPath . 'icon/' . $r['icon'] ) == true ) {
					@unlink( self::$UploadPath . 'icon/' . $r['icon'] );
				}
			}

			Db::delete( self::$table , "id= '" . $id . "'");
			NewsGallery::delete_by_news( $id );
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'delete_notice_success'));
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'error_db_return_error') . '<br/>' . self::$Error);
			return false;
		}
	}

	public static function delete_by_category( $category_id )
	{
		global $app_path;

		$r = Db::exec("*" , self::$table , "WHERE category='" . $category_id . "'");
		if(!empty($r)) {
			foreach( $r as $k=>$i ) {
				if( file_exists( self::$UploadPath . '/icon/' . $i['icon'] ) == true ) {
					unlink( self::$UploadPath . '/icon/' . $i['icon'] );
				}

				NewsGallery::delete_by_news( $i['id'] );

				Db::delete( self::$table , "id= '" . $i['id'] . "'");
			}
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'delete_notice_success'));
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'error_db_return_error') . '<br/>' . self::$Error);
			return false;
		}
	}

	public function set_main( $id, $category )
	{
		//Db::update(self::$table , "main='FALSE'" , "category='" . $category . "'");
		Db::update(self::$table , "main='TRUE'" , "id='" . $id . "' AND category='" . $category . "'");
	}

	public function unset_main( $id, $category )
	{
		Db::update(self::$table , "main='FALSE'" , "id='" . $id . "' AND category='" . $category . "'");
	}

	public static function countByCategory( $category, $admin = false )
	{
		if( $admin == false ) {
			$Row = Db::row("COUNT(id) as num" , self::$table , "WHERE category='" . $category . "' AND status='TRUE'");
		}
		if( $admin == true ) {
			$Row = Db::row("COUNT(id) as num" , self::$table , "WHERE category='" . $category . "'");
		}
		return $Row['num'];
	}

	public function _upload( $file, $folder, $cfg = null )
	{
		if(empty($cfg)) {
			$cfg = [
				'max_width' => 1680,
			];
		}

		if(is_array($file)) {
			if(!empty($file['error'])) {
				self::$Error[] = LA::get('cms' , 'error_upload_' . $file['error']);
				Kernel::setMessage("ERROR" , LA::get('cms','error_form_return_error') , self::$Error);
				return false;
			}

			global $app_path;

			if(file_exists( $app_path . "vendor/verot/class.upload.php/src/class.upload.php") == true) {
				include_once $app_path . "vendor/verot/class.upload.php/src/class.upload.php";
			} else {
				throw new CMSError('Upload class not found');
			}

			$handle = new Upload( $file );
			if ($handle->uploaded)
			{
				$handle->file_new_name_body	= time();
				$handle->file_overwrite		= true;
				$handle->file_auto_rename	= false;
				$handle->jpeg_quality 		= 90;
				if($handle->image_src_x > $cfg['max_width']) {
					$handle->image_resize		= true;
					$handle->image_x      		= $cfg['max_width'];
					$handle->image_ratio_y		= true;
				}
				$handle->Process( self::$UploadPath . $folder . "/");
				if ($handle->processed) {
					return $handle->file_dst_name;
				}

				self::$Error[] = LA::get('cms','error_upload_not');

			} else { self::$Error[] = LA::get('cms','error_upload_not'); }

		}
	}
}
?>
