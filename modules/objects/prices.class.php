<?php
use \Core\Backend\Navigation as Navigation;

/**
 * Objects prices class
 *
 * @package		Modules
 * @subpackage	Objects/Prices
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

	class ObjectsPrices {

		static protected $table = "rooms_prices";
		public static $Error;

		public function __construct()
		{
			global $config;

			self::$table = $config['db_prefix'] . self::$table;
		}

		public function filter($object_id, $room_id)
		{
			$Result = Db::exec("*" , self::$table, "WHERE object_id = '" . $object_id ."' AND room_id = '" . $room_id . "' ORDER BY date");
			if(!empty($Result)) {
				foreach($Result as $k=>$i) {
					$y = date('Y' , strtotime($i['date']));
					$m = date('m' , strtotime($i['date']));
					$Filter['years'][$y] = $y;
					$Filter['months'][$m] = $m;
				}

				if(!empty($Filter)) {
					return $Filter;
				}
			}
		}

		public static function getAmount($object_id, $room_id, $date, $child = false)
		{
			$Result = Db::row("*" , self::$table , "WHERE object_id='" . $object_id. "' AND room_id = '" . $room_id . "' AND date = '" . $date . "'");
			if(!empty($Result)) {
				if($child == true) {
					return $Result['amount_child'];
				} else {
					return $Result['amount'];
				}
			} else {
				return ObjectsRooms::getAmount($object_id, $room_id);
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

		public function verify()
		{
			global $request;

			if(empty($request->post['date_start'])) {
				self::$Error[] = "wybierz datę początkową";
			}

			if(isset($reqeust->post['amount'])) {
				$request->post['amount'] = str_replace("," , "." , $request->post['amount']);
			}
		}

		public function add()
		{
			global $request;

			$this->verify();
			if(!empty(self::$Error)) {
				return false;
			}

			if(!empty($request->post['date_end'])) {
				$days = strtotime($request->post['date_end']) - strtotime($request->post['date_start']);
				$days = floor($days/(60*60*24));

				for($i=1;$i<=$days;$i++) {
					$date = date('Y-m-d' , strtotime("+" . $i . " days" , strtotime($request->post['date_start'])));

					if(!empty($request->post['week'])) {
						$date_week = date('N' , strtotime($date));
						if(in_array($date_week, $request->post['week']) == true) {
							$cont = true;
						}
					} else {
						$cont = true;
					}


					if($cont == true) {
						if(Db::check(self::$table , "object_id='".$request->post['object_id']."' AND room_id='".$request->post['room_id']."' AND date='".$date."'") == true) {
							Db::update(self::$table , "amount = '".str_replace("," , "." , $request->post['amount'])."'" , "object_id='".$request->post['object_id']."' AND room_id='".$request->post['room_id']."' AND date_start='".$date."'");
						} else {
							Db::insert(self::$table , "null,
							'".$request->post['object_id']."',
							'".$request->post['room_id']."',
							'".$date."',
							'".str_replace("," , "." , $request->post['amount'])."'");
						}
					}

					$cont = false;
				}
				return true;
			} else {
				$Result = Db::insert(self::$table , "null,
				'".$request->post['object_id']."',
				'".$request->post['room_id']."',
				'".$request->post['date_start']."',
				'".str_replace("," , "." , $request->post['amount'])."'");
			}

			if(!empty($Result)) {
				return true;
			} else {
				self::$Error = Db::error();
				return false;
			}

		}

		public function save( $id )
		{
			global $app_path, $request;

			$this->verify();
			if(!empty(self::$Error)) {
				return false;
			}

			$result = Db::update( self::$table , "name = '" . $request->post['name'] ."',
			persons = '" . $request->post['persons'] ."',
			amount = '" . $request->post['amount'] ."',
			equipment = '" . (isset($request->post['equipment']) ? Kernel::array2csv($request->post['equipment']) : "") . "',
			description = '" . $request->post['description'] ."'" , "id='".$id."'");

			if(!empty($result)) {
				return true;
			} else {
				$db_error = Db::error();
				if(!empty($db_error)) {
					self::$Error = $db_error;
				}
				return false;
			}

		}

		public function delete( $id )
		{
			if( Db::check( self::$table , "id='" . $id ."'") == true) {
				Db::delete( self::$table , "id= '" . $id . "'");
				return true;
			} else {
				$db_error = Db::error();
				if(!empty($db_error)) {
					self::$Error = $db_error;
				}
				return false;
			}
		}
	}
?>
