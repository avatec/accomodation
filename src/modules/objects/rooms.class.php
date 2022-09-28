<?php
use \Core\Backend\Navigation as Navigation;

/**
 * Objects rooms class
 *
 * @package		Modules
 * @subpackage	Objects/Rooms
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

class ObjectsRooms {

	protected static$table = "rooms";
	public static $Error;

	public static $AmountTypes = [
		['id' => '1', 'name' => 'za osobę'],
		['id' => '2', 'name' => 'za dobę']
	];

	public function __construct()
	{
		global $config;

		self::$table = $config['db_prefix'] . self::$table;
	}

	public static function getAmountTypeName( $id )
	{
		if(!empty(self::$AmountTypes)) {
			foreach( self::$AmountTypes as $i) {
				if($i['id'] == $id) {
					return $i['name'];
				}
			}
		}
	}

	public function get( $id = NULL )
	{
		if(is_null($id)) {
			$r = Db::exec("*" , self::$table , "ORDER BY id");
			if(!empty($r)) {
				foreach($r as $k=>$i) {
					$r[$k]['amount_type'] = (int) $i['amount_type'];
				}
				return $r;
			}
		} else {
			$Result = Db::row("*" , self::$table , "WHERE id='".$id."'");
			$Result['edit'] = true;
			if(isset($Result['equipment'])) {
				$Result['equipment'] = json_decode($Result['equipment'], true);
			}
			return $Result;
		}
	}

	public static function getName( $id )
	{
		$Result = Db::row("name" , self::$table , "WHERE id='" . $id . "'");
		if(!empty($Result)) {
			return $Result['name'];
		} else {
			return "ERROR While getting name [rooms.class.php]";
		}

	}

	public static function getAmount($object_id, $room_id = null)
	{
		if(is_null($room_id)) {
			$Result = Db::row("*" , self::$table , "WHERE object_id='" . $object_id. "' ORDER BY amount ASC");
			if(!empty($Result)) {
				return $Result['amount'];
			} else {
				return false;
			}
		} else {
			$Result = Db::row("*" , self::$table , "WHERE id = '" . $room_id . "' AND object_id='" . $object_id. "'");
			if(!empty($Result)) {
				return $Result['amount'];
			} else {
				return false;
			}
		}

	}

	public function getByObject( $object_id )
	{
		$Results = Db::exec("*" , self::$table , "WHERE object_id='" . $object_id . "'");
		if(!empty($Results)) {
			foreach($Results as $k=>$i) {
				$Results[$k]['equipment'] = json_decode($i['equipment'], true);
				$Results[$k]['photos'] = ObjectsPhotosRoom::_get( $i['id'] , false );
			}
			return $Results;
		}
	}

	public static function _getByObject( $object_id )
	{
		$Results = Db::exec("*" , self::$table , "WHERE object_id='" . $object_id . "'");
		if(!empty($Results)) {
			foreach($Results as $k=>$i) {
				$Results[$k]['equipment'] = json_decode($i['equipment'], true);
			}
			return $Results;
		}
	}

	public function verify()
	{
		global $request;

		if(empty($request->post['name'])) {
			self::$Error[] = "podaj nazwę pokoju";
		}
		if(empty($request->post['persons'])) {
			self::$Error[] = "podaj ilość osób";
		}
		if(empty($request->post['amount'])) {
			self::$Error[] = "podaj podstawową cenę za pokój";
		}
		if(empty($request->post['amount_type'])) {
			self::$Error[] = "wybierz typ ceny";
		}
	}

	public function add()
	{
		global $request;

		$this->verify();
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , Language::get("cms" , "msg_form_error") . ":<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		$result = Db::insert( self::$table , "null,
		'" . $request->post['object_id'] . "',
		'" . $request->post['name'] ."',
		'" . $request->post['persons'] . "',
		'" . $request->post['amount'] . "',
		'" . $request->post['amount_type'] . "',
		'" . (isset($request->post['equipment']) ? json_encode($request->post['equipment']) : "") . "',
		'" . $request->post['description'] . "'");

		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , Language::get("cms" , "msg_add_success"));
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
			return false;
		}

	}

	public function save( $id )
	{
		global $app_path, $request;

		$this->verify();
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , Language::get("cms" , "msg_form_error") . ":<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		$result = Db::update( self::$table , "name = '" . $request->post['name'] ."',
		persons = '" . $request->post['persons'] ."',
		amount = '" . $request->post['amount'] ."',
		amount_type = '" . $request->post['amount_type'] . "',
		equipment = '" . (isset($request->post['equipment']) ? json_encode($request->post['equipment']) : "") . "',
		description = '" . $request->post['description'] ."'" , "id='".$id."'");

		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , Language::get("cms" , "msg_save_success"));
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , Language::get("cms" , "msg_db_error") . self::$Error);
			return false;
		}

	}

	public static function deleteByObject( $object_id )
	{
		$Result = Db::exec("*" , self::$table , "WHERE object_id='" . $object_id . "'");
		if(!empty($Result)) {
			foreach($Result as $k=>$i) {
				self::delete( $i['id'] );
			}
		}
	}

	public static function delete( $id )
	{
		if( Db::check( self::$table , "id='" . $id ."'") == true) {
			Db::delete( self::$table , "id= '" . $id . "'");
			Kernel::setMessage("NOTICE" , Language::get("cms" , "msg_del_success"));
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , Language::get("cms" , "msg_db_error") . self::$Error);
			return false;
		}
	}

	public static function search( $o )
	{
		if(!empty($o['rp'])) {
			$query[] = "persons REGEXP '(" . $o['rp'] . ")'";
		}

		if((!empty($o['cf'])) && (!empty($o['ct']))) {
			$query[] = "amount BETWEEN '" . $o['cf'] . "' AND '" . $o['ct'] . "'";
		}

		if((empty($o['cf'])) && (!empty($o['ct']))) {
			$query[] = "amount <= '" . $o['ct'] . "'";
		}

		if((!empty($o['cf'])) && (empty($o['ct']))) {
			$query[] = "amount >= '" . $o['cf'] . "'";
		}

		if(!empty($query)) {
			$result = Db::query("SELECT * FROM " . self::$table . " WHERE " .implode(" AND " , $query) . " AND object_id IN (" . $o['query'] . ") GROUP BY object_id");
			unset($query);
		}

		if(!empty($result)) {
			$ids = [];
			foreach($result as $i) {

				if(in_array($i['object_id'], $ids) === false) {
					$ids[] = $i['object_id'];
				}

			}
			if(!empty($ids)) {
				return $ids;
			}
		} else {
			if(!empty($o)) {
				return null;
			}
		}
	}

	public static function countRooms( $object_id )
	{
		return Db::count( self::$table , "object_id='" . $object_id . "'");
	}
}
?>
