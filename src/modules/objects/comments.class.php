<?php
use \Core\Backend\Navigation as Navigation;

/**
 * Objects comments class
 *
 * @package		Modules
 * @subpackage	Objects/Comments
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

class ObjectsComments
{
	protected static$table = "objects_comments";
	public static $Error;

	public static $StatusSelect = [
		["id" => "PENDING" , "name" => "oczekujący"],
		["id" => "ACTIVE" , "name" => "aktywny"],
		["id" => "MARK-TO-DELETE" , "name" => "oznaczony do usunięcia"],
		["id" => "DISABLED" , "name" => "wyłączony"]
	];

	public static $RankSelect = [
		["id" => 1, "name" => "fatalnie"],
		["id" => 2, "name" => "bardzo źle"],
		["id" => 3, "name" => "źle"],
		["id" => 4, "name" => "dobrze"],
		["id" => 5, "name" => "bardzo dobrze"],
		["id" => 6, "name" => "fantastycznie"]
	];

	public function __construct()
	{
		global $config;

		if($config['announcement_comments'] == "TRUE") {
			Navigation::submenu('objects' , 'Komentarze', "objects/comments/list/");
			//Kernel::addAdminMenu("objects", "Komentarze", "admin/objects/comments/list/", null, true);
		}

		self::$table = $config['db_prefix'] . self::$table;
	}

	public function recommend( $id, $type )
	{
		if($type == "plus") {
			if(Db::update(self::$table, "helpful=helpful+1" , "id='" . $id . "'") == true) {
				return true;
			}
		}

		if($type == "minus") {
			if(Db::update(self::$table, "unhelpful=unhelpful+1" , "id='" . $id . "'") == true) {
				return true;
			}
		}
	}

	public static function stars( $rating, $big = true )
	{
		$i=1;
		$html[] = "<div class=\"star-rating\" data-toggle=\"tooltip\" data-title=\"" . Language::get("objects" , "comments_rank_text") . ": " . number_format($rating, 1, '.', '') . " / 6\">";
		while($i<=6) {
			if($rating >= $i) {
				if($rating > ($i+1)) {
					$html[] = "<span class=\"fa fa-star " . ($big == true ? 'fa-2x' : '') . " text-primary\"></span>";
				} elseif( $rating == $i) {
					$html[] = "<span class=\"fa fa-star " . ($big == true ? 'fa-2x' : '') . " text-primary\"></span>";
				} else {
					if(substr_count($rating, ".") > 0) {
						$html[] = "<span class=\"fa fa-star " . ($big == true ? 'fa-2x' : '') . " text-primary\"></span>";
						$i++;
						$html[] = "<span class=\"fa fa-star-half-o " . ($big == true ? 'fa-2x' : '') . " text-primary\"></span>";
					} else {
						$html[] = "<span class=\"fa fa-star " . ($big == true ? 'fa-2x' : '') . " text-primary\"></span>";
					}
				}
			} elseif( $rating < $i) {
				if( ($rating > 0) AND ($rating < 1) AND $i==1) {
					$html[] = "<span class=\"fa fa-star-half-o " . ($big == true ? 'fa-2x' : '') . " text-primary\"></span>";
				} else {
					$html[] = "<span class=\"fa fa-star-o " . ($big == true ? 'fa-2x' : '') . " text-primary\"></span>";
				}

			}

			$i ++;
		}
		$html[] = "</div>";
		return implode("",$html);
	}

	public function getComments( $object_id, $with_count = false )
	{
		if( $with_count == false ) {
			return Db::exec("*" , self::$table , "WHERE object_id='" . $object_id . "' ORDER BY id DESC");
		} else {
			$Result = Db::exec("*" , self::$table , "WHERE object_id='" . $object_id . "' AND status='ACTIVE' ORDER BY id DESC");
			$Result['count'] = (int) Db::count(self::$table , "object_id='" . $object_id . "' AND status='ACTIVE'");
			$avg_rank = Db::query("SELECT AVG(rank) as avg_rank FROM " . self::$table . " WHERE object_id='" . $object_id . "' AND status='ACTIVE'");
			$Result['avg_rank'] = ($avg_rank['0']['avg_rank'] > 0 ? $avg_rank['0']['avg_rank'] : 0);
			$avg_rank = Db::query("SELECT MAX(rank) as best FROM " . self::$table . " WHERE object_id='" . $object_id . "' AND status='ACTIVE'");
			$Result['best'] = $avg_rank['0']['best'];
			$helpful = Db::query("SELECT SUM(helpful) as recomment FROM " . self::$table . " WHERE object_id='" . $object_id . "' AND status='ACTIVE'");
			$unhelpful = Db::query("SELECT SUM(unhelpful) as not_recommend FROM " . self::$table . " WHERE object_id='" . $object_id . "' AND status='ACTIVE'");
			$Result['recommend'] = $helpful['0']['recomment'];
			$Result['not_recommend'] = $unhelpful['0']['not_recommend'];
			if($Result['count'] > 0) {
				return $Result;
			}
		}
	}

	public function get( $id = NULL )
	{
		if(is_null($id)) {
			return Db::exec("*" , self::$table , "ORDER BY id");
		} else {
			$Result = Db::row("*" , self::$table , "WHERE id='".$id."'");
			$Result['edit'] = true;
			return $Result;
		}
	}

	public function verify( $admin = false )
	{
		global $request;

		if(empty($request->post['name'])) {
			self::$Error[] = "musisz podać swoje imię";
		}
		if(empty($request->post['text'])) {
			self::$Error[] = "musisz wyrazić swoją opinię, aby dodać komentarz";
		}
		if($admin == false) {
			if(empty($request->post['rank'])) {
				self::$Error[] = "wybierz ocenę obiektu w skali od 1 do 6 (1 - niepolecam, 6 - fantastycznie)";
			}
		}
	}

	public function add($admin=false)
	{
		global $request;

		$this->verify($admin);
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Znaleziono błędy w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		$Result = Db::insert(self::$table , "null,
		'" . $request->post['object_id'] . "',
		" . (!empty($request->post['uid']) ? "'" . $request->post['uid'] . "'" : "NULL") . ",
		'" . $request->post['name'] . "',
		'" . $request->post['text'] . "',
		NULL,
		NOW(),
		'" . $request->post['rank'] . "',
		'PENDING',
		'0',
		'0'");

		if(!empty($Result)) {
			return true;
		} else {
			self::$Error = Db::error();
			return false;
		}
	}

	public function save( $id, $admin = false )
	{
		global $request;

		$this->verify($admin);
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Znaleziono błędy w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}
		if($admin == true) {
			$Result = Db::update(self::$table, "name = '" . $request->post['name'] . "',
			text = '" . $request->post['text'] . "'" .
			(!empty($request->post['rank']) ? ",rank = '" . $request->post['rank'] . "'" : "") .
			(!empty($request->post['status']) ? ",status = '" . $request->post['status'] . "'" : "") , "id='" . $id . "'");
		} else {
			$Result = Db::update(self::$table, "name = '" . $request->post['name'] . "',
			text = '" . $request->post['text'] . "',
			rank = '" . $request->post['rank'] . "'" .
			(!empty($request->post['status']) ? ",status = '" . $request->post['status'] . "'" : "") , "id='" . $id . "'");
		}
		if($Result == true) {
			Kernel::setMessage("NOTICE" , "Pomyślnie zapisano zmiany");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas zapisywania zmian:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}
	}

	public static function deleteByObject( $object_id )
	{
		$Result = Db::exec("*" , self::$table , "WHERE object_id='" . $object_id . "'");
		if(!empty($Result)) {
			foreach($Result as $k=>$i) {
				self::delete( $i['id'], $object_id );
			}
		}
	}

	public static function delete( $id, $object_id )
	{
		$Result = Db::delete(self::$table , "id='" . $id . "' AND object_id='" . $object_id . "'");
		if(!empty($Result)) {
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto pozycję");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas usuwania pozycji. Najprawdopodobniej wybrana pozycja nie istnieje<br/>" . self::$Error);
			return false;
		}
	}

	public static function setHelpful( $object_id, $id )
	{
		Db::update(self::$table , "helpful = helpful+1" , "id='" . $id . "' AND object_id='" . $id . "'");
		return true;
	}

	public static function setUnhelpful( $object_id, $id )
	{
		Db::update(self::$table , "unhelpful = unhelpful+1" , "id='" . $id . "' AND object_id='" . $id . "'");
		return true;
	}

	public static function setStatus( $id, $object_id, $status = "ACTIVE" )
	{
		if( Db::update(self::$table , "status='" . $status . "'" , "id='" . $id . "' AND object_id='" . $object_id . "'") == true ) {
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
			return false;
		}

	}

	public static function countComments( $object_id, $status = 'ACTIVE' )
	{
		$Result = Db::counter( self::$table , "object_id='" . $object_id . "'" . (!empty($status) ? " AND status='" . $status . "'" : ""));
		return $Result;
	}

	public static function countRank( $object_id )
	{
		$Result = Db::exec("*" , self::$table , "WHERE object_id='" . $object_id . "'");
		$comments = count($Result);
		if(!empty($Result)) {
			$rank = 0;
			foreach($Result as $k=>$i) {
				$rank += $i['rank'];
			}
		}
		if(empty($rank)) {
			return 0;
		} else {
			return round($rank / $comments);
		}
	}
}
