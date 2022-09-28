<?php
use \Core\Backend\Navigation as Navigation;

/**
 * Objects class
 *
 * @package		Modules
 * @subpackage	Objects
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

class Objects {

	protected static$table = "objects";
	public static $Error;

	public static $Status = [
		["id" => "DISABLED" , "name" => "Wyłączony", "label" => "primary"],
		["id" => "PENDING" , "name" => "Wymaga zatwierdzenia", "label" => "danger" ],
		["id" => "ACTIVE" , "name" => "Zatwierdzony", "label" => "info"]
	];

	public static $viewExpire = [
		"free" 		=> "bezterminowo",
		"no" 		=> "nie",
		"expired" 	=> "wygasło",
		"today" 	=> "dzisiaj wygasa",
		"active" 	=> "aktywne"
	];

	public function __construct()
	{
		global $config, $route;

		Navigation::menu( 10, 'objects' , 'Baza Noclegowa', null, 'fa-slideshare');
		Navigation::submenu('objects' , 'Ustawienia', "system/config/accomodation/");
		Navigation::submenu('objects' , 'Przeglądaj oferty', "objects/list/");

		//Kernel::addAdminMenu("objects", "Baza noclegowa", null, "fa-slideshare", null, false);
		//	Kernel::addAdminMenu("objects", "Ustawienia", "admin/system/config/accomodation/", null, true);
		//	Kernel::addAdminMenu("objects", "Przeglądaj oferty", "admin/objects/list/", null, true);


		self::$table = $config['db_prefix'] . self::$table;

		Language::load("objects");
		self::$Status = array_replace_recursive( self::$Status, Language::get("objects" , "objects_class_status"));
		self::$viewExpire = Language::get("objects" , "objects_class_view_expire");

		Kernel::registerComponent( 2 , "Wyszukiwarka obiektów" , "objects/search");
		Kernel::registerComponent( 3 , "Mapa z obiektami" , "objects/map");

		$this->register( $route );
	}

	protected function register( $route )
	{
		$route->get('(noclegi)\/:string' , [
			'module' => 'objects', 'file' => 'objects', 'command' => 'list-by-city', 'id' => '$2'
		]);

		$route->get('(noclegi)\/:string\/:string-i:id', [
			'module' => 'objects', 'file' => 'objects', 'command' => 'view', 'id' => '$4'
		]);

		$route->get('(panel)\/(objects)\/:string' , [
			'module' => 'objects', 'file' => 'objects', 'command' => '$3'
		]);

		$route->get('(panel)\/(objects)\/(photos)\/:string' , [
			'module' => 'objects', 'file' => 'photos', 'command' => '$3'
		]);

		$route->get('(panel)\/(objects)\/(video)\/:string' , [
			'module' => 'objects', 'file' => 'video', 'command' => '$3'
		]);

		$route->get('(panel)\/(objects)\/(rooms)\/:string' , [
			'module' => 'objects', 'file' => 'rooms', 'command' => '$3'
		]);

		$route->get('(panel)\/(objects)\/(comments)\/:string' , [
			'module' => 'objects', 'file' => 'comments', 'command' => '$3'
		]);

		$route->get('(panel)\/(rooms)\/(photos)\/:string' , [
			'module' => 'objects', 'file' => 'photosroom', 'command' => '$3'
		]);

		$route->get('(search)', [
			'module' => 'objects', 'file' => 'objects', 'command' => 'search'
		]);

		$route->get('(search)\/:string', [
			'module' => 'objects', 'file' => 'objects', 'command' => 'search', 'id' => '$2'
		]);

		$route->get('(search)\/(special)\/:string', [
			'module' => 'objects', 'file' => 'objects', 'command' => 'list-by-special', 'id' => '$3'
		]);
	}

	public function get_for_map()
	{
		global $config;

		if( $config['announcement_create'] == "TRUE" ) {
			$o = Db::exec("id,name,short_description,address,postcode,city,city_rw,map_lat,map_lng,map_zoom,view_expire" , self::$table , "WHERE view_expire >= CURDATE()");
		} else {
			$o = Db::exec("id,name,short_description,address,postcode,city,city_rw,map_lat,map_lng,map_zoom,view_expire" , self::$table , "WHERE view_expire IS NULL");
		}
		if(!empty($o)) {
			foreach($o as $k=>$i ) {
				$o[$k]['link'] = '/noclegi/' . $i['city_rw'] . '/' . Kernel::rewrite( $i['name'] ) . '-i' . $i['id'];
			}
		}
		return json_encode( $o );
	}

	public function recommend( $object_id, $type )
	{
		if($type == "plus") {
			if(Db::update(self::$table, "plus=plus+1" , "id='" . $object_id . "'") == true) {
				return true;
			}
		}

		if($type == "minus") {
			if(Db::update(self::$table, "minus=minus+1" , "id='" . $object_id . "'") == true) {
				return true;
			}
		}
	}

	public static function stats()
	{
		return array(
			'all' => Db::count( self::$table ),
			'today' => Db::count( self::$table , "create_date='" . date('Y-m-d') . "'")
		);
	}

	public static function setMainExpire( $object_id, $promotion_id )
	{
		$days = Promotion::getDays( $promotion_id );
		$what = Promotion::getTypeOf( $promotion_id );
		$expire = date('Y-m-d' , strtotime('+' . $days . ' days'));
		Payment::log('Days +' . $days);
		Payment::log('Final date: ' . $expire);

		switch( $what )
		{
			case "VIEW":
				$result = Db::update( self::$table , "view_expire='" . $expire . "'" , "id='" . $object_id . "'");
			break;

			case "SEARCH":
				$result = Db::update( self::$table , "search_expire='" . $expire . "'" , "id='" . $object_id . "'");
			break;

			case "MAIN":
				$result = Db::update( self::$table , "main_expire='" . $expire . "'" , "id='" . $object_id . "'");
			break;
		}

		if($result == false) {
			Payment::log('Objects::setMainExpire - DB ERROR ?: ' . Db::error());
		}
		return $result;
	}

	public static function getName( $id )
	{
		$Result = Db::row("name" , self::$table , "WHERE id='" . $id . "'");
		if(!empty($Result)) {
			return $Result['name'];
		} else {
			die("ERROR While getting name [objects.class.php]");
		}

	}

	public static function _getStatusName( $id , $returnAsHTML = false )
	{
		if(!empty(self::$Status)) {
			foreach(self::$Status as $k=>$i) {
				if($i['id'] == $id) {
					if( $returnAsHTML == true && isset($i['label']) ) {
						return '<span class="label label-' . $i['label'] . '">' . $i['name'] . '</span>';
					} else {
						return $i['name'];
					}
				}
			}
		}
	}

/**
 * Zmiana statusu ogłoszenia - funkcja dostępna tylko w przypadku jeżeli włączone jest moderowanie
 * $config['accomodation_moderate'] = true
 */

	public static function setStatus( $id, $status )
	{
		global $request;

		// Jeżeli zmiana następuje z poziomu PA i status jest jako ACCEPT, pobiera datę update_date i sprawdza różnicę w dniach
		// pomiędzy update_date a display_expire, main_expire, search_expire
		// Dodaje dni, które minęły od czasu zablokowania ogłoszenia

		if(isset($request->get['row']) && $status == "ACTIVE") {
			$Row = Db::row("view_expire,search_expire,main_expire,update_date" , self::$table , "WHERE id='" . $id . "'");
			$update_date = strtotime($Row['update_date']);
			$diff = time() - $update_date;
			$days = floor($diff/(60*60*24));

			$now = date('Y-m-d');

			$view_expire = $Row['view_expire'];
			$view_expire_plus = strtotime($view_expire);
			$view_expire_plus = strtotime("+" . $days . " day" , $view_expire_plus);

			if($view_expire > $now || $view_expire_plus > strtotime($now)) {
				$view_expire = date('Y-m-d' , $view_expire_plus);
				if($days>0) { Kernel::setMessage("INFO" , "Wyświetlanie obiektu zostało zwiększone o " . $days . " dni"); }
			} else {
				unset($view_expire);
			}
			unset($view_expire_plus);

			$search_expire = $Row['search_expire'];
			$search_expire_plus = strtotime($search_expire);
			$search_expire_plus = strtotime("+" . $days . " day" , $search_expire_plus);

			if($search_expire > $now || $search_expire_plus > strtotime($now)) {
				$search_expire = date('Y-m-d' , $search_expire_plus);
				if($days>0) { Kernel::setMessage("INFO" , "Wyróżnienie w wyszukiwarce zostało zwiększone o " . $days . " dni"); }
			} else {
				unset($search_expire);
			}
			unset($search_expire_plus);

			$main_expire = $Row['main_expire'];
			$main_expire_plus = strtotime($main_expire);
			$main_expire_plus = strtotime("+" . $days . " day" , $main_expire_plus);

			if($main_expire > $now || $main_expire_plus > strtotime($now)) {
				$main_expire = date('Y-m-d' , $main_expire_plus);
				if($days>0) { Kernel::setMessage("INFO" , "Promocja na stronie głównej została zwiększona o " . $days . " dni"); }
			} else {
				unset($main_expire);
			}
			unset($main_expire_plus);

			if(!empty($view_expire) || !empty($search_expire) || !empty($main_expire)) {
				Db::$debug = true;

				$query = (!empty($view_expire) ? "view_expire='" . $view_expire . "'" : "") .
				(!empty($search_expire) ? ",search_expire='" . $search_expire . "'" : "") .
				(!empty($main_expire) ? ",main_expire = '" . $main_expire . "'" : "");

				Db::update(
					self::$table ,
					$query ,
					"id='" . $id. "'"
				);
			}
		}
		if( Db::update( self::$table , "status='" . $status . "', update_date = CURDATE()" , "id='" . $id. "'") == true ) {
			Kernel::setMessage("NOTICE" , "Status obiektu został zmieniony");
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("WARNING" , "Wystąpił nieoczekiwany błąd podczas operacji na bazie danych: " . self::$Error);
		}
		return true;
	}

	public function get( $id = NULL )
	{
		if(is_null($id)) {
			Paginate::$query = "SELECT * FROM " . self::$table . " ORDER BY id DESC";
			Paginate::$perpage = 25;
			return Paginate::make();
		} else {
			$Result = Db::row("*" , self::$table , "WHERE id='".$id."'");
			$Result['edit'] = true;
			if(!empty($Result['distance'])) {
				$Result['distance'] = json_decode($Result['distance'],true);
			}
			if(!empty($Result['improvements'])) {
				$Result['improvements'] = json_decode($Result['improvements'],true);
			}
			$Result['short_description'] = Kernel::html_decode($Result['short_description']);
			$Result['long_description'] = Kernel::html_decode($Result['long_description']);
			return $Result;
		}
	}

/**
 * Pobieranie rekordu do wyświetlania na stronie
 */

	public function getView( $id )
	{
		global $config;
		if( $config['announcement_moderate'] == "TRUE" ) {
			$query[] = "status='ACTIVE'";
		}

		if( $config['announcement_create'] == "FALSE" ) {
			 $Result = Db::row("*" , self::$table , "WHERE id='" . $id . "'" . (!empty($query) ? " AND " . implode(" AND " , $query) : ""));
		} else {
			$Result = Db::row("*" , self::$table , "WHERE id='".$id."' AND view_expire>=CURDATE()" . (!empty($query) ? " AND " . implode(" AND " , $query) : ""));
		}
		if(empty($Result)) {
			$Result = Db::row("*" , self::$table , "WHERE id='" . $id . "' AND uid='" . User::$user['id'] . "' " . (!empty($query) ? " AND " . implode(" AND " , $query) : ""));
			if(empty($Result)) {
				return false;
			}
		}

		$Result['edit'] = true;
		if(!empty($Result['distance'])) {
			$Result['distance'] = json_decode($Result['distance'],true);
		}
		if(!empty($Result['improvements'])) {
			$Result['improvements'] = json_decode($Result['improvements'],true);
		}
		$Result['short_description'] = Kernel::html_decode($Result['short_description']);
		$Result['long_description'] = Kernel::html_decode($Result['long_description']);
		Kernel::$facebook_image = ObjectsPhotos::getImage( $Result['id'], false );
		return $Result;
	}

	public static function _get( $id )
	{
		return Db::row("id,name,email,city_rw,uid" , self::$table , "WHERE id='" . $id . "'");
	}

	public static function getUidById( $id )
	{
		$Result = Db::row("uid" , self::$table , "WHERE id='" . $id . "'");
		return $Result['uid'];
	}

/**
 * Zwraca wszystkie numery ID obiektów zalogowanego użytkownika
 */

	public static function getObjectsIDS()
	{
		if( !empty(User::$user['id'])) {
			$Result = Db::exec("id" , self::$table , "WHERE uid='" . User::$user['id'] . "' ORDER BY id");
			if(!empty($Result)) {
				foreach($Result as $i) {
					$ids[] = $i['id'];
				}
				if(!empty($ids)) {
					return $ids;
				}
			}
		} else {
			return false;
		}
	}

	public function getByUser( $uid )
	{
		Paginate::$query = "SELECT * FROM " . self::$table . " WHERE uid='" . $uid . "' ORDER BY view_expire";
		Paginate::$perpage = 10;
		return Paginate::make();
	}

	public function getPromoted( $type, $limit = 3 )
	{
		global $config;
		if( $config['announcement_moderate'] == "TRUE" ) {
			$query[] = "status='ACTIVE'";
		}

		if( $config['announcement_create'] == "TRUE") {
			 $query[] = "view_expire>=CURDATE() ";
		}

		switch($type) {
			case "MAIN":
				$Result = Db::exec("*" , self::$table , "WHERE " . (!empty($query) ? implode(" AND " , $query) . " AND " : "") . "main_expire>=CURDATE() ORDER BY RAND() DESC LIMIT 0," . $limit);
			break;

			case "SEARCH":
				$Result = Db::exec("*" , self::$table , "WHERE " . (!empty($query) ? implode(" AND " , $query) . " AND " : "") . "search_expire>=CURDATE() ORDER BY RAND() DESC LIMIT 0," . $limit);
			break;
		}

		if(!empty($Result)) {
			if(!isset($config['basic'])) {
				foreach($Result as $k=>$i) {
					$Result[$k]['has_video'] = ObjectsVideos::hasVideo( $i['id'] );
				}
			}
			return $Result;
		}
	}

	public function verify()
	{
		global $request;

		if(empty($request->post['type'])) {
			self::$Error[] = Language::get("objects" , "objects_error_type");
		}
		if(empty($request->post['name'])) {
			self::$Error[] = Language::get("objects" , "objects_error_name");
		}
		if(empty($request->post['address'])) {
			self::$Error[] = Language::get("objects" , "objects_error_address");
		}
		if(empty($request->post['postcode'])) {
			self::$Error[] = Language::get("objects" , "objects_error_postcode");
		}
		if(empty($request->post['city'])) {
			self::$Error[] = Language::get("objects" , "objects_error_city");
		}
		if(empty($request->post['location'])) {
			self::$Error[] = Language::get("objects" , "objects_error_location");
		}
		if(empty($request->post['state'])) {
			self::$Error[] = Language::get("objects" , "objects_error_state");
		}
		if(strlen($request->post['www']) > 0) {
			if (filter_var($request->post['www'], FILTER_VALIDATE_URL) === false) {
				self::$Error[] = Language::get("objects" , "objects_error_www");
			}
		}
	}

	public function add( $admin_added = true )
	{
		global $config, $request;

		$this->verify();
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , Language::get("cms" , "msg_form_error") . ":<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		if($admin_added == true) {
			$uid = (empty($request->post['uid']) ? User::$admin['id'] : $request->post['uid']);
			$status = (isset($request->post['status']) ? $request->post['status'] : 'PENDING');
			$view_expire = (isset($request->post['view_expire']) ? $request->post['view_expire'] : null);
			$search_expire = (isset($request->post['search_expire']) ? $request->post['search_expire'] : null);
			$main_expire = (isset($request->post['main_expire']) ? $request->post['main_expire'] : null);
		} else {
			$uid = User::$user['id'];
			if( $config['announcement_create'] == "FALSE") {
				$view_expire = date('Y-m-d' , strtotime("+1 month"));
			} else {
				$view_expire = null;
			}
			$search_expire = null;
			$main_expire = null;
			if( $config['announcement_moderate'] == "TRUE" ) {
				$status = "PENDING";
			} else {
				$status = "ACTIVE";
			}
		}

		$distance = (isset($request->post['distance']) ? json_encode($request->post['distance']) : "");
		$improvement = (isset($request->post['improvements']) ? json_encode($request->post['improvements']) : "");

		$latlng = (isset($request->post['latlng']) ? $request->post['latlng'] : "");
		if(!empty($latlng)) {
			$data = explode("," , $latlng);
			$lat = str_replace("(" , "" , $data[0]);
			$lng = str_replace(")" , "" , $data[1]);
			$zoom = (isset($request->post['zoom']) ? $request->post['zoom'] : 12);
		}

		$result = Db::insert( self::$table , "null,
		'" . $uid . "',
		'" . $request->post['name'] . "',
		'" . $request->post['short_description'] ."',
		'" . (isset($request->post['long_description']) ? $request->post['long_description'] : "") ."',
		'" . $request->post['address'] ."',
		" . (isset($request->post['postcode']) ? "'" . $request->post['postcode'] . "'," : "NULL,") . "
		" . (isset($request->post['city']) ? "'" . $request->post['city'] . "'," : "NULL,") . "
		" . (isset($request->post['city']) ? "'" . Kernel::rewrite($request->post['city']) . "'," : "NULL,") . "
		" . (isset($request->post['state']) ? "'" . $request->post['state'] ."'," : "NULL,") . "
		" . (isset($request->post['country']) ? "'" . $request->post['country'] ."'," : "NULL,") . "
		NULL,
		'" . $request->post['type'] ."',
		'" . $request->post['location'] ."',
		'" . $distance ."',
		'" . $improvement ."',
		'" . (isset($lat) ? $lat : "") ."',
		'" . (isset($lng) ? $lng : "") ."',
		'" . (isset($zoom) ? $zoom : "") ."',
		'" . $request->post['phone'] ."',
		'" . (isset($request->post['email']) ? $request->post['email'] : "") ."',
		'" . (isset($request->post['www']) ? $request->post['www'] : "") ."',
		'" . (isset($request->post['meta_title']) ? $request->post['meta_title'] : "") ."',
		'" . (isset($request->post['meta_keywords']) ? $request->post['meta_keywords'] : "") ."',
		'" . (isset($request->post['meta_description']) ? $request->post['meta_description'] : "") ."',
		" . (isset($view_expire) ? "'" . $view_expire . "'" : "NULL") .",
		" . (isset($search_expire) ? "'" . $search_expire . "'" : "NULL") .",
		" . (isset($main_expire) ? "'" . $main_expire . "'" : "NULL") .",
		NOW(),
		NOW(),
		'" . $status ."',
		0,
		0,
		'" . (!empty($request->post['booking']) ? $request->post['booking'] : 'FALSE') . "'");

		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , Language::get("cms" , "msg_add_success"));
			ObjectsCities::quickAdd( $request->post['city'] , $request->post['state'] );
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , Language::get("cms" , "msg_db_error") . self::$Error);
			return false;
		}
	}

	public function save( $id, $admin_added = false )
	{
		global $config, $app_path, $request;

		$this->verify();
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , Language::get("cms" , "msg_form_error") . ":<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		if($admin_added == true) {
			$uid = (empty($request->post['uid']) ? null : $request->post['uid']);
			$status = (isset($request->post['status']) ? $request->post['status'] : 'PENDING');
			$view_expire = (isset($request->post['view_expire']) ? $request->post['view_expire'] : null);
			if($view_expire == "0000-00-00") { $view_expire = null; }
			$search_expire = (isset($request->post['search_expire']) ? $request->post['search_expire'] : null);
			if($search_expire == "0000-00-00") { $search_expire = null; }
			$main_expire = (isset($request->post['main_expire']) ? $request->post['main_expire'] : null);
			if($main_expire == "0000-00-00") { $main_expire = null; }

			Db::update( self::$table ,
			(!empty($view_expire) ? "view_expire = '" . $view_expire . "'" : "view_expire = NULL") .
			(!empty($search_expire) ? ",search_expire = '" . $search_expire . "'" : ",search_expire = NULL") .
			(!empty($main_expire) ? ",main_expire = '" . $main_expire . "'" : ", main_expire = NULL") , "id='".$id."'");
		}

		$distance = (isset($request->post['distance']) ? json_encode($request->post['distance']) : "");
		$improvements = (isset($request->post['improvements']) ? json_encode($request->post['improvements']) : "");

		$latlng = (isset($request->post['latlng']) ? $request->post['latlng'] : "");
		if(!empty($latlng)) {
			$data = explode("," , $latlng);
			$lat = str_replace("(" , "" , $data[0]);
			$lng = str_replace(")" , "" , $data[1]);
			$zoom = (isset($request->post['zoom']) ? $request->post['zoom'] : 12);
		}

		$result = Db::update( self::$table , (!empty($uid) ? "uid = '" . $uid . "'," : "") . "
		name = '" . $request->post['name'] ."',
		short_description = '" . addslashes($request->post['short_description']) ."',
		long_description = '" . (isset($request->post['long_description']) ? addslashes($request->post['long_description']) : "") ."',
		address = '" . $request->post['address'] ."',
		" . (isset($request->post['postcode']) ? "postcode = '" . $request->post['postcode'] ."'," : "") . "
		" . (isset($request->post['city']) ? "city = '" . $request->post['city'] ."'," : "") . "
		" . (isset($request->post['city']) ? "city_rw = '" . Kernel::rewrite($request->post['city']) ."'," : "") . "
		" . (isset($request->post['state']) ? "state = '" . $request->post['state'] ."'," : "") . "
		" . (isset($request->post['country']) ? "country = '" . $request->post['country'] ."'," : "") . "
		type = '" . $request->post['type'] ."',
		" . (isset($request->post['location']) ? "location = '" . $request->post['location'] ."'," : "") . "
		" . (isset($distance) ? "distance = '" . $distance ."'," : "") . "
		" . (isset($improvements) ? "improvements = '" . $improvements ."'," : "") . "
		map_lat = '" . (isset($lat) ? $lat : "") ."',
		map_lng = '" . (isset($lng) ? $lng : "") ."',
		map_zoom = '" . (isset($zoom) ? $zoom : "") ."',
		phone = '" . $request->post['phone'] ."',
		email = '" . (isset($request->post['email']) ? $request->post['email'] : "") ."',
		www = '" . (isset($request->post['www']) ? $request->post['www'] : "") ."',
		meta_title = '" . (isset($request->post['meta_title']) ? $request->post['meta_title'] : "") ."',
		meta_keywords = '" . (isset($request->post['meta_keywords']) ? $request->post['meta_keywords'] : "") ."',
		meta_description = '" . (isset($request->post['meta_description']) ? $request->post['meta_description'] : "") . "'" .
		(isset($status) ? ",status = '" . $status . "'" : "") .
		",update_date = CURDATE()" .
		(!empty($request->post['booking']) ? ",booking = '" . $request->post['booking'] . "'" : "") , "id='".$id."'");

		if($result == true) {
			if( $config['announcement_moderate'] == "TRUE" && $admin_added == false ) {
				Db::update( self::$table , "update_date = CURDATE(), status='PENDING'" , "id='" . $id . "'" );
			}
			Kernel::setMessage("NOTICE" , Language::get("cms" , "msg_save_success"));
			ObjectsCities::quickAdd( $request->post['city'] , $request->post['state'] );
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , Language::get("cms" , "msg_db_error") . ": " . self::$Error);
			return false;
		}
	}

	public function delete_by_uid( $uid )
	{
		$r = Db::exec("id" , self::$table , "WHERE uid='" . $uid . "'");
		if(empty($r)) {
			return false;
		}

		foreach($r as $i) {
			$this->delete( $i['id'] );
		}

		return true;
	}

	public function delete( $id )
	{
		if( Db::check( self::$table , "id='" . $id ."'") == true) {
			Db::delete( self::$table , "id= '" . $id . "'");
			ObjectsPhotos::deleteByObject( $id );
			ObjectsVideos::deleteByObject( $id );
			ObjectsComments::deleteByObject( $id );
			ObjectsRooms::deleteByObject( $id );
			Kernel::setMessage("NOTICE" , Language::get("cms" , "msg_del_success"));
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , Language::get("cms" , "msg_db_error") . self::$Error);
			return false;
		}
	}

	public static function readExpire( $object_id, $promotion_type, $expires, $no_buttons = false )
	{
		global $app_url, $config;

		if($no_buttons == false) {
			$update_btn_renew = ' <a class="btn btn-danger btn-xs pull-right" href="'.$app_url.'panel/objects/order/?object_id='.$object_id.'&what=' . $promotion_type . '"><em class="fa fa-shopping-basket"></em> ' . Language::get('objects' , 'btn_renew'). '</a>';
			$update_btn_buy = ' <a class="btn btn-warning btn-xs pull-right" href="'.$app_url.'panel/objects/order/?object_id='.$object_id.'&what=' . $promotion_type . '"><em class="fa fa-shopping-basket"></em> ' . Language::get('objects' , 'btn_buy'). '</a>';
		} else {
			$update_btn_renew = '';
			$update_btn_buy = '';
		}

		switch( $promotion_type ) {
			case "VIEW":

			if( $config['announcement_create'] == "TRUE") {

				if($no_buttons == false) {
					$help_btn = 'data-container="body" data-toggle="tooltip" data-title="' . Language::get("objects" , "order_display_expire") .  ':<br/>'.$expires.'"';
				} else {
					$help_btn = '>do ' . $expires . '<br/';
				}

				if($expires == "0000-00-00") {
					$html = '<span class="badge badge-success" '.$help_btn.'>' . self::$viewExpire['active'] . '</span>';
				}
				elseif( $expires > 0 AND $expires < date('Y-m-d') ) {
					$html = '<span class="badge badge-warning" '.$help_btn.'>' . self::$viewExpire['expired'] . '</span>' . $update_btn_renew;
				}
				elseif( $expires == date('Y-m-d') ) {
					$html = '<span class="badge badge-warning" '.$help_btn.'>' . self::$viewExpire['today'] . '</span>' .$update_btn_renew;
				}
				elseif( is_null($expires)) {
					$html = '<span class="badge badge-info" >' . self::$viewExpire['no'] . '</span>' . $update_btn_buy ;
				} else {
					$html = '<span class="badge badge-success" '.$help_btn.'>' . self::$viewExpire['active'] . '</span>';
				}
			} else {
				$html = '<span class="badge badge-success">' . self::$viewExpire['free'] . '</span>';
			}
			break;

			case "SEARCH":
				if($no_buttons == false) {
					$help_btn = 'data-container="body" data-toggle="tooltip" data-title="' . Language::get("objects" , "order_promoted_expire") .  ':<br/>'.$expires.'"';
				} else {
					$help_btn = '>do ' . $expires . '<br/';
				}

				if( $expires > 0 AND $expires < date('Y-m-d') ) {
					$html = '<span class="badge badge-warning" ' . $help_btn . '>' . self::$viewExpire['expired'] . '</span>' . $update_btn_renew;
				}
				elseif( $expires == date('Y-m-d') ) {
					$html = '<span class="badge badge-warning" ' . $help_btn . '>' . self::$viewExpire['today'] . '</span>' . $update_btn_renew;
				}
				elseif(is_null($expires) || $expires == "0000-00-00") {
					$html = '<span class="badge badge-info">' . self::$viewExpire['no'] . '</span>' . $update_btn_buy ;
				} else {
					$html = '<span class="badge badge-success" ' . $help_btn . '>' . self::$viewExpire['active'] . '</span>';
				}
			break;

			case "MAIN":
				if($no_buttons == false) {
					$help_btn = 'data-container="body" data-toggle="tooltip" data-title="' . Language::get("objects" , "order_main_expire") .  ':<br/>'.$expires.'"';
				} else {
					$help_btn = '>do ' . $expires . '<br/';
				}

				if( $expires > 0 AND $expires < date('Y-m-d') ) {
					$html = '<span class="badge badge-warning" '.$help_btn.'>' . self::$viewExpire['expired'] . '</span>'  . $update_btn_renew;
				}
				elseif( $expires == date('Y-m-d') ) {
					$html = '<span class="badge badge-warning" '.$help_btn.'>' . self::$viewExpire['today'] . '</span>' . $update_btn_renew;
				}
				elseif(is_null($expires) || $expires == "0000-00-00") {
					$html = '<span class="badge badge-info">' . self::$viewExpire['no'] . '</span>' .$update_btn_buy ;
				} else {
					$html = '<span class="badge badge-success" '.$help_btn.'>' . self::$viewExpire['active'] . '</span>';
				}

			break;
		}


		return $html;
	}

	public function getByCity( $name )
	{
		global $config;

		$spaced = explode("-" , $name);
		if(!empty($spaced)) {
			foreach($spaced as $i) {
				$q[] = "city LIKE '%" . $i . "%' OR city_rw LIKE '%" . $i . "%'";
			}
		}
		if( $config['announcement_moderate'] == "TRUE" ) {
			$query[] = "status='ACTIVE'";
		}

		Paginate::$query = "SELECT * FROM " . self::$table . " WHERE (city LIKE '" . $name . "' OR city_rw LIKE '" . $name . "'" . (!empty($q) ? " OR (" . implode(" AND " , $q) . ")" : "") . ")" . (!empty($query) ? " AND " . implode(" AND " , $query) : "");
		Paginate::$perpage = 25;
		$Result = Paginate::make();
		if(!empty($Result)) {
			foreach($Result as $k=>$i) {
				$Result[$k]['has_video'] = ObjectsVideos::hasVideo( $i['id'] );
			}
			return $Result;
		}
	}

	public function getSpecial( $special_name )
	{
		global $config;

		$special_ids = SpecialOffers::getPromoted( $special_name );

		if(!empty($special_ids)) {
			if( $config['announcement_moderate'] == "TRUE" ) {
				$query[] = "status='ACTIVE'";
			}

			foreach($special_ids as $i) {
				$sid[] = $i['object_id'];
			}

			if( $conig['announcement_create'] == "TRUE" ) {
				Paginate::$query = "SELECT * FROM " . self::$table . " WHERE id IN ('" . implode( "','" , $sid ) . "') AND view_expire >= CURDATE() " . (!empty($query) ? implode( " AND " , $query ) : "");
			} else {
				Paginate::$query = "SELECT * FROM " . self::$table . " WHERE id IN ('" . implode( "','" , $sid ) . "') " . (!empty($query) ? implode( " AND " , $query ) : "");
			}

			Paginate::$perpage = 25;
			$Result = Paginate::make();
			if(!empty($Result)) {
				foreach($Result as $k=>$i) {
					$Result[$k]['has_video'] = ObjectsVideos::hasVideo( $i['id'] );
				}
				return $Result;
			}
		}
	}

	public static $countedResults = 0;

	public function search( $id = null, $admin = false )
	{
		global $request, $config;

		if(isset($id)) {
			$LocationResult = ObjectsLocation::has( $id );
			if( $LocationResult !== false ) {
				$qry[] = "location='" . $LocationResult . "'";
			}
		}

		if(isset($request->get['quicksearch'])) {
			if((!empty($request->get['t'])) && !is_numeric($request->get['t'])) {
				switch( $request->get['t'] ) {
					case "state":
						$state_id = ObjectsStates::searchByName( $request->get['q'] );
						$qry[] = "state='" . $state_id . "'";
						Form::$post['s'] = $state_id;
						unset($request->get['q']);
					break;
					case "city":
						$qry[] = "(city LIKE '%" . $request->get['q'] . "%' OR city_rw LIKE '%" . $request->get['q'] . "%')";
						Form::$post['c'] = $request->get['q'];
						unset($request->get['q']);
					break;
					case "type":
						$type_id = ObjectsTypes::searchByName( $request->get['q'] );
						$qry[] = "type='" . $type_id . "'";
						$request->get['t'] = (int) $type_id;
						unset($request->get['q']);
					break;
					case "name":
						$qry[] = "name LIKE '%" . $request->get['q'] . "%'";
						Form::$post['q'] = $request->get['q'];
					break;
				}
			}
		} else {
			if(!empty($request->get['q'])) {
				if(!empty(User::$admin)) {
					$qry[] = "(id = '" . $request->get['q'] . "' OR name LIKE '%" . $request->get['q'] . "%' OR phone LIKE '%" . $request->get['q'] . "%' OR email LIKE '%" . $request->get['q'] . "%'  OR city LIKE '%" . $request->get['q'] . "%' OR city_rw LIKE '%" . $request->get['q'] . "%')";
				} else {
					$qry[] = "(name LIKE '%" . $request->get['q'] . "%' OR phone LIKE '%" . $request->get['q'] . "%')";
				}
			}
			if(!empty($request->get['t'])) {
				$qry[] = "type='" . $request->get['t'] . "'";
			}
			if(!empty($request->get['c'])) {
				$qry[] = "(city LIKE '%" . $request->get['c'] . "%' OR city_rw LIKE '%" . $request->get['c'] . "%')";
			}
			if(!empty($request->get['s'])) {
				$qry[] = "state='" . $request->get['s'] . "'";
			}
			if(!empty($request->get['status']) && $config['announcement_moderate'] == "TRUE") {
				$qry[] = "status='" . $request->get['status'] . "'";
			}
			if(!empty($request->get['l'])) {
				$qry[] = "location='" . $request->get['l'] . "'";
			}
			if(!empty($request->get['uid'])) {
				$qry[] = "uid='" . $request->get['uid'] . "'";
			}

			if( $admin == true ) {
				if(!empty($request->get['sid'])) {
					$ids = SpecialOffers::getPromoted( $request->get['sid'], true );
					if(!empty($ids)) {
						foreach($ids as $i) {
							$sids[] = $i['object_id'];
						}
					}
					if(!empty($sids)) {
						$qry[] = 'id IN ("' . implode('","', $sids) . '")';
					} else {
						return null;
					}
				}
			}

			if(!empty($request->get['distance'])) {
				$distance = explode(";" , $request->get['distance']);
				if(!empty($distance)) {
					$disquery = '(';
					foreach($distance as $k=>$i) {
						if(!empty($i)) {
							Form::$post['distance'][$i] = true;
							$disquery .= 'distance REGEXP \'\\"'.$i.'\\"\\:\\"([0-9]+)\\"\' AND ';
						}
					}
					$disquery = substr($disquery, 0, -4);
					$disquery .= ')';
				}

				$qry[] = $disquery;
			}
			if(!empty($request->get['improvement'])) {
				$improvement = explode(";" , $request->get['improvement']);
				if(!empty($improvement)) {
					$imprquery = '(';
					foreach($improvement as $k=>$i) {
						if(!empty($i)) {
							Form::$post['improvement'][$i] = true;
							$imprquery .= 'improvements REGEXP \'\\"'.$i.'\\"\\:\\"1\\"\' AND ';
						}
					}
					$imprquery = substr($imprquery, 0, -4);
					$imprquery .= ')';

					$qry[] = $imprquery;
				}
			}
		}
		if( $admin == false ) {
			if((!empty($request->get['cf'])) || (!empty($request->get['ct'])) || (!empty($request->get['rp']))) {
				if( $config['announcement_create'] == "TRUE" && $admin == false ) {
					$rooms = ObjectsRooms::search([
						'cf' => (isset($request->get['cf']) ? $request->get['cf'] : null),
						'ct' => (isset($request->get['ct']) ? $request->get['ct'] : null),
						'rp' => (isset($request->get['rp']) ? $request->get['rp'] : null),
						'query' => "SELECT id FROM " . self::$table . " WHERE view_expire >= CURDATE()"
					]);
				} else {
					$rooms = ObjectsRooms::search([
						'cf' => (isset($request->get['cf']) ? $request->get['cf'] : null),
						'ct' => (isset($request->get['ct']) ? $request->get['ct'] : null),
						'rp' => (isset($request->get['rp']) ? $request->get['rp'] : null),
						'query' => "SELECT id FROM " . self::$table . " "
					]);
				}
				if(empty($rooms)) {
					return null;
				}
			}
			if(!empty($rooms)) {
				$qry[] = "id IN (" . implode("," , $rooms) . ")";
			}

			if( $config['announcement_create'] == "TRUE" && $admin == false ) {
				$qry[] = "view_expire>=CURDATE()";
			}

			if( $config['announcement_moderate'] == "TRUE" && $admin == false) {
				$qry[] = "status='ACTIVE'";
			}
		}

		if(!empty($qry)) {
			$query = "WHERE " . implode(" AND " , $qry);
		}

		// photo
		if(!empty($request->get['photo'])) {
			$photo_search = "(SELECT COUNT(*) AS cp FROM " . ObjectsPhotos::$table . " WHERE object_id=`" . self::$table . "`.id HAVING cp>0 ) AS has_photo ";
			$having[] = "has_photo>0";
		}

		// video
		if(!empty($request->get['video'])) {
			$video_search = "(SELECT COUNT(*) AS cp FROM " . ObjectsVideos::$table . " WHERE object_id=`" . self::$table . "`.id HAVING cp>0 ) AS has_video ";
			$having[] = "has_video>0";
		}

		if($admin == true) {
			Paginate::$query = "SELECT * FROM " . self::$table . " " . (isset($query) ? $query : "") . " ORDER BY id DESC";
			Paginate::$perpage = 25;
			$Result = Paginate::make();

			if(!empty($Result)) {
				foreach($Result as $k=>$i) {
					$Result[$k]['num_comments'] = ObjectsComments::countComments( $i['id'], null );
					if(!isset($config['basic'])) {
						$Result[$k]['num_rooms'] = ObjectsRooms::countRooms( $i['id'] );
						$Result[$k]['num_videos'] = ObjectsVideos::countVideos( $i['id'] );
					}
					$Result[$k]['num_photos'] = ObjectsPhotos::countPhotos( $i['id'] );
				}
			}
		} else {
			self::$countedResults = Db::counter(self::$table . " " . (isset($query) ? $query : "") . " ORDER BY search_expire DESC, view_expire DESC");

			if(!empty($having) && count($having) == 2 ) {
				$having = " HAVING " . $having[0] . " AND " . $having[1] . " ";
			} elseif (!empty($having) && count($having) == 1 ) {
				$having = " HAVING " . implode("" , $having);
			}

			Paginate::$query = "SELECT * " . (!empty($photo_search) ? "," . $photo_search : "") .
				(!empty($video_search) ? ", " . $video_search : "") . " FROM " . self::$table . " " . (isset($query) ? $query : "") .
				(!empty($having) ? $having : "") .
				" ORDER BY search_expire DESC, view_expire DESC";

			Paginate::$perpage = (isset($config['announcement_search_perpage']) ? $config['announcement_search_perpage'] : 20);
			$Result = Paginate::make();
		}

		if(!empty($Result)) {
			if(!isset($config['basic'])) {
				foreach($Result as $k=>$i) {
					$Result[$k]['has_video'] = ObjectsVideos::hasVideo( $i['id'] );
				}
			}
			return $Result;
		}
	}

/**
 * Pobieranie obiektów wygasających na użytki wysyłki powiadomień o kończącej się ważności abonamentu
 *
 * @param $type
 * @param $days
 * @return array
 */

	public static function getExpire( $type = 'VIEW' , $days = 0 )
	{
		switch( strtoupper($type) ) {
			case "VIEW":
				return Db::exec("id,uid,name,address,postcode,city,city_rw,view_expire" , self::$table , "WHERE view_expire = CURDATE() + INTERVAL " . $days . " DAY");
			break;

			case "MAIN":
				return Db::exec("id,uid,name,address,postcode,city,city_rw,main_expire" , self::$table , "WHERE main_expire = CURDATE() + INTERVAL " . $days . " DAY");
			break;

			case "SEARCH":
				return Db::exec("id,uid,name,address,postcode,city,city_rw,search_expire" , self::$table , "WHERE search_expire = CURDATE() + INTERVAL " . $days . " DAY");
			break;
		}

	}

	public static function generateLoginPassword( $id, $regenerate = false )
	{
		$row = Db::row("*" , self::$table , "WHERE id='" . $id . "'");
		if(!empty($row)) {
			$login = Kernel::rewrite(strtolower($row['name']));
			$login = trim($login);
			$login = preg_replace('/\s+/', '', $login);
			if( $regenerate == true )
			{
				$login = $login . rand(1,100);
			}
			$account = [
				'login' => substr($login, 0,10),
				'email' => $row['email'],
				'phone' => $row['phone'],
				'password' => Kernel::generateIdent( 5 ),
				'street' => $row['address'],
				'postcode' => $row['postcode'],
				'city' => $row['city']
			];


			if(!empty($account)) {
				return $account;
			}
		}
	}

	public static function updateUID( $uid, $object_id )
	{
		Db::update( self::$table , "uid='" . $uid . "'" , "id='" . $object_id . "'");
	}

	public static function get_sitemap()
	{
		global $config;

		if( $config['announcement_moderate'] == "TRUE" ) {
			$query[] = "status='ACTIVE'";
		}

		if( $config['announcement_create'] == "FALSE" ) {
			$r = Db::exec("id,name,city" , self::$table , (!empty($query) ? "WHERE " . implode(" AND " , $query) : ""));
		} else {
			$r = Db::exec("id,name,city" , self::$table , "WHERE view_expire>=CURDATE()" . (!empty($query) ? " AND " . implode(" AND " , $query) : ""));
		}

		if(empty($r)) {
			return;
		}

		return $r;
	}
}
