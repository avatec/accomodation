<?php
/**
 * Advertising class
 *
 * @package		Modules
 * @subpackage	Advertising
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

use \Core\Backend\Navigation as Navigation;

class Advertising
{
	protected static $table = "advertising";
	public static $Error;

	public static $UploadDir = "userfiles/ads";
	public static $UploadPath, $UploadUrl;

	public $post, $get, $files;

	public static $types = [
		[ 'id' => 'IMAGE', 'name' => 'Plik graficzny' ],
		[ 'id' => 'TEXT' , 'name' => 'Kod reklamy js lub html' ]
	];

	public static $places = [
		[ 'id' => 'MAIN', 'name' => 'Na stronie głównej obok aktualności (szerokość zalecana 768px)' ],
		[ 'id' => 'SEARCH' , 'name' => 'Pomiędzy wynikami wyszukiwania' ],
		[ 'id' => 'PAGE' , 'name' => 'Prawa strona na podstronach z treścią' ]
	];

	public function __construct()
	{
		global $config, $app_path, $app_url, $request, $route;

		Navigation::menu(12 , 'advertising' , 'Reklamy' , 'advertising/list/' , 'fa-newspaper-o');
		//Kernel::addAdminMenu("advertising", "Reklamy", "admin/advertising/list/", null, false);

		self::$table = $config['db_prefix'] . self::$table;

		$this->post = (!empty($request->post) ? $request->post : null);
		$this->get = (!empty($request->get) ? $request->get : null);
		$this->files = (!empty($request->files) ? $request->files : null);

		self::$UploadPath = $app_path . self::$UploadDir . '/';
		self::$UploadUrl = $app_url . self::$UploadDir . '/';

		System::create_dir( self::$UploadPath );

		/**
		$route->get(
			'(ads)-:string-i:id',
			[ 'schema' => '2,3', 'module' => 'advertising', 'file' => 'advertising', 'command' => 'decode-url' ]
		);
		**/
	}

	public static function get_place( $place )
	{
		if(!empty(self::$places)) {
			foreach( self::$places as $k=>$i ) {
				if( $i['id'] == $place ) {
					return $i['name'];
				}
			}
		}
	}

	public static function show( $place = "MAIN", $limit=3 )
	{
		global $app_url;

		$Result = Db::exec("*" , self::$table , "WHERE place='" . $place . "' AND date_start<='".date('Y-m-d')."' OR (date_end>'".date('Y-m-d')."' OR date_end='0000-00-00' OR date_end = NULL) ORDER BY RAND() LIMIT 0,".$limit);
		if(!empty($Result)) {
			$html[] = '<div class="a-block"><span class="head">' . Language::get("cms" , "adv_header") . '</span></div>';
			foreach($Result as $k=>$i) {
				if($i['type'] == "TEXT") {
					$html[] = '<div class="a-block">' . html_entity_decode($i['html']) . '</div>';
				}
				if($i['type'] == "IMAGE") {
					$html[] = '<div class="a-block"><a href="' . $i['link'] . '" target="_blank"><img class="img-responsive" src="' . self::$UploadUrl . $i['photo'] . '" alt="' . md5($i['id']) . '" /></a></div>';
				}
			}
		}

		if(!empty($html)) {
			return implode(PHP_EOL , $html);
		} else {
			return '<div class="a-block default"><p class="text-center">Miejsce na Twoją reklamę</p></div>';
		}
	}

	public function get_link($md5, $id)
	{
		Db::update( self::$table , "clicks=clicks+1" , "id='" . $id . "' AND md5(link) = '" . $md5 . "'");
		$r = Db::row("link" , self::$table , "WHERE id='" . $id . "' AND md5(link) = '" . $md5 . "'");
		return $r['link'];
	}

	private static function link( $id, $link )
	{
		global $app_url;
		return $app_url . 'ads-' . md5($link) .'-i'. $id;
	}

	public function get( $id = null )
	{
		if(!empty($this->get['t'])) {
			$query[] = "type='".$this->get['t']."'";
		}

		if(!empty($this->get['p'])) {
			$query[] = "place='".$this->get['p']."'";
		}

		if((!empty($this->get['ds'])) AND (!empty($this->get['de']))) {
			$query[] = "(date_start>='".$this->get['ds']."' AND date_end<='".$this->get['de']."')";
		}

		if((empty($this->get['ds'])) AND (!empty($this->get['de']))) {
			$query[] = "date_end<='".$this->get['de']."'";
		}

		if((!empty($this->get['ds'])) AND (empty($this->get['de']))) {
			$query[] = "date_start>='".$this->get['ds']."'";
		}


		if(is_null($id)) {
			return Db::exec("*" , self::$table , (!empty($query) ? "WHERE " . implode(" AND " , $query) : '') . " ORDER BY priority,date_end");
		} else {
			$Result = Db::row("*" , self::$table , "WHERE id='".$id."'");
			$Result['edit'] = true;
			return $Result;
		}
	}

	private function validate()
	{
		if(empty($this->post['type'])) {
			self::$Error[] = "nie wybrano typu reklamy";
		} else {
			switch($this->post['type'])
			{
				case "IMAGE":
					if(empty($_FILES['photo'])) {
						self::$Error[] = "nie wybrano pliku do uploadu";
					}
				break;

				case "TEXT":
					if(empty($this->post['html'])) {
						self::$Error[] = "nie wprowadzono kodu reklamy";
					}
				break;
			}
		}
		if(empty($this->post['name'])) {
			self::$Error[] = "nie wprowadzono opisu reklamy";
		}
		if(empty($this->post['date_start'])) {
			self::$Error[] = "nie podano daty rozpoczęcia emisji reklamy";
		}
		if(empty($this->post['place'])) {
			self::$Error[] = "nie wybrano miejsca wyświetlania reklamy";
		}
	}

	public function add()
	{
		$this->validate();
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Wykryto błędy w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		if(!empty($_FILES['photo']['name'])) {
			$photo = System::upload( $_FILES['photo'], [
				'upload_dir' => self::$UploadPath
			]);
		}

		$result = Db::insert(self::$table , "null,
		'" . $this->post['priority'] . "',
		'" . $this->post['name'] . "',
		" . (!empty($this->post['description']) ? "'".$this->post['description']."'" : "NULL") . ",
		'" . $this->post['type'] . "',
		'" . $this->post['place'] . "',
		'" . $this->post['date_start'] . "',
		" . (!empty($this->post['date_end']) ? "'".$this->post['date_end']."'" : "NULL") . ",
		" . (!empty($this->post['html']) ? "'".$this->post['html']."'" : "NULL") . ",
		" . (empty($photo) ? "NULL" : "'" . $photo . "'") . ",
		" . (!empty($this->post['link']) ? "'".$this->post['link']."'" : "NULL") . ",
		" . (!empty($this->post['state']) ? "'".$this->post['state']."'" : "NULL"));

		if( $result == true ) {
			Kernel::setMessage("NOTICE" , "Pomyślnie utworzono pozycję");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
			return false;
		}
	}

	public function save( $id )
	{
		$this->validate();
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Wykryto błędy w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		if(!empty($this->post['delete_photo'])) {
			System::delete_file( self::$UploadPath . $this->post['delete_photo']);
			Db::update( self::$table , "photo=NULL" , "id='" . $id . "'");
		}

		if(!empty($_FILES['photo']['name'])) {
			$photo = System::upload( $_FILES['photo'], [
				'upload_dir' => self::$UploadPath
			]);
			if(!empty($photo)) {
				Db::update( self::$table , "photo='" . $photo . "'" , "id='" . $id . "'");
			}
		}

		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Wykryto błędy w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		$result =  Db::update(self::$table , "priority		= '".$this->post['priority']."',
		name = '".$this->post['name']."',
		" . (!empty($this->post['description']) ? "description = '".$this->post['description']."'" : "description = NULL") . ",
		type = '".$this->post['type']."',
		place = '".$this->post['place']."',
		date_start = '".$this->post['date_start']."',
		" . (!empty($this->post['date_end']) ? "date_end = '".$this->post['date_end']."'" : "date_end = NULL") . ",
		" . (!empty($this->post['html']) ? "html = '".$this->post['html']."'" : "html = NULL") . ",
		" . (!empty($this->post['link']) ? "link = '".$this->post['link']."'" : "link = NULL") . ",
		" . (!empty($this->post['state']) ? "state = '".$this->post['state']."'" : "state = NULL") , "id='".$id."'");

		if( $result == true ) {
			Kernel::setMessage("NOTICE" , "Zmiany zostały zapisane");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
			return false;
		}
	}

	public function delete( $id )
	{
		global $app_path;

		$Row = $this->get( $id );
		System::delete_file( self::$UploadPath . $Row['photo']);

		if( Db::delete(self::$table , "id='".$id."'") == true ) {
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto pozycję");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
			return false;
		}
	}

	protected function clear_old_files()
	{
		global $app_path;

		$to_delete = [];

		$files = scandir( self::$UploadPath );
		if(!empty($files)) {
			foreach( $files as $file )
			{
				if( in_array($file, ['.','..']) == false ) {
					if( Db::check( self::$table , "photo='" . $file . "'") == false ) {
						$to_delete[] = self::$UploadPath . $file;
					}
				}
			}
		}

		if(!empty($to_delete)) {
			foreach( $to_delete as $file ) {
				unlink($file);
			}
		}
	}
}
