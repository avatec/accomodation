<?php
use \Core\Backend\Navigation as Navigation;

/**
 * Booking class
 *
 * @package		Modules
 * @subpackage	Booking
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

class Booking
{
	public static $table = "booking";
	public static $Error = [];

	public $post, $get;

	protected static $monthName = array(
		1 => array("long" => "Styczeń", "short" => "Sty"),
		2 => array("long" => "Luty", "short" => "Lut"),
		3 => array("long" => "Marzec", "short" => "Mar"),
		4 => array("long" => "Kwiecień", "short" => "Kwi"),
		5 => array("long" => "Maj", "short" => "Maj"),
		6 => array("long" => "Czerwiec", "short" => "Cze"),
		7 => array("long" => "Lipiec", "short" => "Lip"),
		8 => array("long" => "Sierpień", "short" => "Sie"),
		9 => array("long" => "Wrzesień", "short" => "Wrz"),
		10 => array("long" => "Październik", "short" => "Paz"),
		11 => array("long" => "Listopad", "short" => "Lis"),
		12 => array("long" => "Grudzień", "short" => "Gru")
	);

	protected static $daysName = array(
		1 => array("long" => "Poniedziałek", "short" => "Pn"),
		2 => array("long" => "Wtorek", "short" => "Wt"),
		3 => array("long" => "Środa", "short" => "Śr"),
		4 => array("long" => "Czwartek", "short" => "Cz"),
		5 => array("long" => "Piątek", "short" => "Pt"),
		6 => array("long" => "Sobota", "short" => "So"),
		7 => array("long" => "Niedziela", "short" => "Nd")
	);

	public static $paymentStatus = array(
		[ 'id' => 'PENDING' , 'name' => 'Oczekuje na wpłatę', 'label' => 'info' ],
		[ 'id' => 'NEW' , 'name' => 'Wpłata nie potwierdzona', 'label' => 'warning' ],
		[ 'id' => 'CONFIRM' , 'name' => 'Wpłata potwierdzona', 'label' => 'success' ],
		[ 'id' => 'REFUSED' , 'name' => 'Wpłata odrzucona', 'label' => 'danger' ],
		[ 'id' => 'CANCEL' , 'name' => 'Rezerwacja anulowana', 'label' => 'danger' ]
	);

	public function __construct()
	{
		global $config, $request, $route;

		self::$table = $config['db_prefix'] . self::$table;

		$this->post = (!empty($request->post) ? $request->post : null);
		$this->get = (!empty($request->get) ? $request->get : null);

		if($route->isAdmin == true ) {
			Navigation::menu(11 , 'booking' , 'Rezerwacje' , null , 'fa-book');
			Navigation::submenu('booking' , 'przeglądaj' , 'booking/list/');

			//Kernel::addAdminMenu("booking", "Rezerwacje", null, "fa-book", null, false);
			//Kernel::addAdminMenu("booking", "przeglądaj", "admin/booking/list/", null, true);

			$this->install();
		} else {
			Language::load("booking");

		}

		$this->register( $route );
	}

	protected function register( $route )
	{
		$route->get('(panel)\/(booking)\/:string', [
			'module' => 'booking', 'file' => 'booking', 'command' => '$3'
		]);

		$route->get('(booking)\/(check)\/o:id' , [
			'module' => 'booking', 'file' => 'booking', 'command' => 'check', 'id' => '$3'
		]);

		$route->get('(booking)\/:string' , [
			'module' => 'booking', 'file' => 'booking', 'command' => '$2'
		]);

		$route->get('(booking)\/(check)\/o:id-r:id' , [
			'module' => 'booking', 'file' => 'booking', 'command' => 'check', 'id' => ['$3','$4']
		]);
	}

	protected function install()
	{
		if( Db::has_table( self::$table ) == false ) {
			Db::install("CREATE TABLE " . self::$table . " (
`id` int(11) UNSIGNED NOT NULL,
`uid` int(11) DEFAULT NULL,
`checkin` date NOT NULL,
`checkout` date NOT NULL,
`object_id` int(11) UNSIGNED NOT NULL,
`room_id` int(11) UNSIGNED NOT NULL,
`session_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
`first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
`last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
`phone` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
`email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
`comment` text COLLATE utf8_unicode_ci,
`amount` double(10,2) NOT NULL,
`advance_amount` double(10,2) NOT NULL,
`payment` enum('FALSE','TRUE') COLLATE utf8_unicode_ci NOT NULL,
`payment_amount` double(10,2) DEFAULT NULL,
`payment_date` datetime DEFAULT NULL,
`payment_status` enum('PENDING','NEW','CONFIRM','REFUSED','CANCEL') COLLATE utf8_unicode_ci NOT NULL,
`days` int(11) UNSIGNED NOT NULL,
`res_adult` int(11) UNSIGNED NOT NULL,
`res_child1` int(11) UNSIGNED NOT NULL,
`res_child2` int(11) UNSIGNED NOT NULL,
`settlement` enum('FALSE','TRUE') COLLATE utf8_unicode_ci NOT NULL,
`settlement_amount` double(10,2) DEFAULT NULL,
`settlement_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
			Db::install("ALTER TABLE " . self::$table . " ADD PRIMARY KEY (`id`);");
			Db::install("ALTER TABLE " . self::$table . " MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;");
			Kernel::setMessage("NOTICE" , "Instalacja tabeli " . self::$table . " zakończona pomyślnie");
			return true;
		}
	}

	public static function readPaymentStatus( $id, $with_label = false )
	{
		if(!empty( self::$paymentStatus)) {
			foreach( self::$paymentStatus as $i ) {
				if( $i['id'] == $id ) {
					if( $with_label == false ) {
						return $i['name'];
					} else {
						return '<span class="label label-' . $i['label'] . '">' . $i['name'] . '</span>';
					}
				}
			}
		}
	}

	public static function getOwnerStats()
	{
		$stats['new'] = 0;
		return $stats;
	}

	public static function monthName( $month_number, $short = false )
	{
		if( $short == true ) {
			return self::$monthName[(int) $month_number]['short'];
		} else {
			return self::$monthName[(int) $month_number]['long'];
		}
	}

	public static function dayName( $day_number, $short = false )
	{
		if( $short == true ) {
			return self::$daysName[$day_number]['short'];
		} else {
			return self::$daysName[$day_number]['long'];
		}
	}

/**
* Pobieranie rezerwacji zalogowanego właściciela obiektów
*/

	public function getOwners()
	{
 		$oids = Objects::getObjectsIDS();
 		if(!empty( $oids )) {
	 		return Db::exec("*" , self::$table , "WHERE object_id IN ('" . implode( "','", $oids) . "')");
 		}
	}

	public static function get($object_id = null, $room_id = null)
	{
		if((is_null($object_id)) AND (is_null($room_id))) {
			return Db::exec("*" , self::$table , "ORDER BY id DESC");
		}
		return Db::exec("*" , self::$table , "WHERE object_id='" . $object_id ."' AND room_id='" . $room_id . "' AND checkin>='".date('Y-m-d')."'");
	}

/**
 * Pobieranie rozliczenia z aktualnego miesiąca na podstawie
 * numeru identyfikacyjnego użytkownika
 */

	public function getByUser( $uid )
	{
		global $request;

		if( !empty($request->get['start_date']) && !empty($request->get['end_date'])) {
			$query = "payment_date BETWEEN '" . $request->get['start_date'] . "' AND '" . $request->post['end_date'] . "'";
		}
		if( !empty($request->get['start_date']) && empty($request->get['end_date'])) {
			$query = "payment_date >= '" . $request->get['start_date'] . "'";
		}
		if( empty($request->get['start_date']) && !empty($request->get['end_date'])) {
			$query = "payment_date <='" . $request->post['end_date'] . "'";
		}
		$Result = Db::exec("*" , self::$table , "WHERE " .
		(isset($query) ? $query : "payment_date LIKE '" . date('Y-m') . "-%'") .
		" AND uid='" . $uid . "'");

		if(!empty($Result)) {
			$CommissionAll = 0;
			$PaymentAmount = 0;
			foreach( $Result as $k=>$i ) {
				$CommissionAll += $i['settlement_amount'];
				$PaymentAmount += $i['payment_amount'];
			}
		}
		return array(
			'list' => $Result,
			'data' => array(
				'user_commission' => User::getField("commission", $uid),
				'payment_amount' => (isset($PaymentAmount) ? $PaymentAmount : 0),
				'commission_all' => (isset($CommissionAll) ? $CommissionAll : 0)
			)
		);
	}

	public function payoff_prepare_data()
	{
		global $request;

		if(!empty($request->post['selectid'])) {
			$data['user_commission'] = User::getField("commission", $request->post['uid']);
			$AdvanceAmount = 0;
			$PaymentAmount = 0;
			foreach( $request->post['selectid'] as $id=>$value ) {
				if( $value == "TRUE" ) {
					$row = Db::row( "*" , self::$table , "WHERE id='" . $id . "'");

					$AdvanceAmount += $row['advance_amount'];
					$PaymentAmount += $row['payment_amount'];
				}

				$PaymentAmount = $AdvanceAmount / (($data['user_commission']/100) + 1);

				$CommissionAmount = $AdvanceAmount - $PaymentAmount;

				$data['advance_amount'] = (!empty($AdvanceAmount) ? $AdvanceAmount : 0);
				$data['payment_amount'] = (!empty($PaymentAmount) ? $PaymentAmount : 0);
				$data['commission_amount'] = (!empty($CommissionAmount) ? $CommissionAmount : 0);
			}

			$user = array(
				'bank_account' => User::getField('bank_account' , $request->post['uid']),
				'first_name' => User::getField('first_name' , $request->post['uid']),
				'last_name' => User::getField('last_name' , $request->post['uid']),
				'company_name' => User::getField('company_name' , $request->post['uid']),
				'street' => User::getField('street' , $request->post['uid']),
				'postcode' => User::getField('postcode' , $request->post['uid']),
				'city' => User::getField('city' , $request->post['uid'])
			);


			return array('data' => $data, 'u' => $user);
		}
	}

	public function payoff_complete()
	{
		global $request;
		if(!empty($request->post['selectid'])) {
			foreach( $request->post['selectid'] as $id=>$value ) {

				$data['user_commission'] = User::getField("commission", $request->post['uid']);
				$row = Db::row( "*" , self::$table , "WHERE id='" . $id . "'");

				$settlement_amount = $row['advance_amount'] / (($data['user_commission']/100) + 1);
				$settlement_amount = $row['advance_amount'] - $settlement_amount;

				Db::update( self::$table , "settlement = 'TRUE', settlement_amount = '" . $settlement_amount . "', settlement_date = CURDATE()" , "id='" . $id . "'");
			}
		}
	}

	public function getSingle( $id )
	{
		$Result = Db::row("*" , self::$table, "WHERE id='" . $id . "'");
		$Result['edit'] = true;
		return $Result;
	}

	public static function checkStatusBySID( $sid )
	{
		$Result = Db::row("*" , self::$table , "WHERE payment_sid='" . $sid . "'");
		if($Result['payment'] == "TRUE") {
			return true;
		} else {
			return false;
		}
	}

	public static function payPrepare( $id )
	{
		$Row = Db::row("*" , self::$table , "WHERE MD5(id)='" . trim($id) . "'");
		if(!empty($Row)) {
			$PaymentData = [
				'booking_id' => $Row['id'],
				'session_id' => $Row['session_id'],
				'pid' => null,
				'payment' => 'online',
				'firstname' => $Row['first_name'],
				'lastname' =>$Row['last_name'],
				'email' => $Row['email'],
				'phone' => $Row['phone'],
				'amount' => $Row['advance_amount'],
				'description' => 'Rezerwacja terminu ' . $Row['checkin'] . ' do ' . $Row['checkout'] . ' | identyfikator rezerwacji: ' . $Row['id']. '/' . $Row['object_id'] . '/' . $Row['room_id'],
				'control' => 'bid=' . $Row['id']
			];

			return $PaymentData;
		}
	}

	public static function HTMLCalendar( $date = null, $object_id )
	{

		if( is_null( $date )) {
			$year = date('Y');
			$month = date('n');
			$day = date('d');
		} else {
			$year = date('Y' , strtotime( $date ));
			$month = date('n' , strtotime( $date ));
			$day = '1';
		}

		// Aktualna data na podstawie podanych danych
		$ac_date_time = date('Y-m-d' , strtotime( $year . '-' . $month . '-01'));

		// Ilość dni w miesiącu
		$days_in_month = date('t', strtotime($ac_date_time));

		// Nazwa pierwszego dnia tygodnia
		$first_day = date('N', strtotime($ac_date_time));

		// Pobieranie rezerwacji z zakresu dni podanego przez parametr
		$rooms = ObjectsRooms::_getByObject( $object_id );

		if(!empty($rooms)) {
			foreach($rooms as $item) {
				// Generowanie pustej listy dni
			 	for( $day=1; $day <= $days_in_month; $day++ ) {
					$calendar[$item['id']][$day] = [
						'status' => 'free',
						'info' => null
					];
				}

				$data = Db::exec("*" , self::$table , "WHERE object_id='" . $object_id . "' AND room_id='" . $item['id'] . "' AND checkin>='" . date('Y-m-d' , strtotime( $year . '-' . $month . '-' . $day )) . "' AND checkout <= '" . date('Y-m-d' , strtotime( $year . '-' . $month . '-' . $days_in_month )) . "' ORDER BY checkin");


				// Przypisywanie do $calendar zajętych dni
				if(!empty($data)) {
					foreach($data as $row) {
						$date_start = $row['checkin'];
						$date_days = $row['days'];
						$date_end = $row['checkout'];

						$start_date_day = date('j' , strtotime( $date_start ));
						$end_date_day = $row['days'] + $start_date_day;

						$info = $row['first_name'] . " " . $row['last_name'] . "<br/>" .
						"od " . $row['checkin'] . " do " . $row['checkout'];
						for( $days=$start_date_day; $days <= $end_date_day; $days++ ) {
							$calendar[$row['room_id']][$days] = [
								'status' => ($row['payment'] == "TRUE" ? 'reserved' : 'booked'),
								'info' => $info
							];
						}

						unset($date_start);
						unset($date_days);
						unset($date_end);
						unset($start_date_day);
						unset($info);
						unset($days);
					}
				}
			}
		}

		return array(
			"info" => self::monthName( ($month-1) ) . " " . $year,
			"next" => date('Y-m' , strtotime( '+1 month' , strtotime( $year . '-' . $month . '-01'))),
			"prev" => date('Y-m' , strtotime( '-1 month' , strtotime( $year . '-' . $month . '-01'))),
			"month_days" => $days_in_month,
			"reservations" => (!empty($calendar) ? $calendar : null),
			"rooms" => (!empty($rooms) ? $rooms : null)
		);
	}

	public static function calendar($year = null, $month = null, $num = 6, $data = null)
	{

		if(is_null($year)) {
			$year = date('Y');
		}
		if(is_null($month)) {
			$month = date('n');
		}
		if(is_null($num)) {
			$num = 6;
		}

		if($num>1) {
			$calendar[] = "<div class=\"item-row\">";
			for($i=1;$i<=$num;$i++) {
				$im = $i-1;
				$month = date('n', strtotime("+" . $im . " month", strtotime(date('Y-m-01'))));
				$calendar[] = self::drawCalendar($year, $month, $data);
				if($i%3==0) {
					$calendar[] = '<div class="clearfix"></div></div><div class="item-row">';
				}
			}
			$calendar[] = "</div>";
		}

		return $calendar;
	}

	public static function drawCalendar($year, $month, $data = null)
	{
		if(is_null($data)) {
			$data = date('Y-m-d');
		}
		$first_day = strtotime(date($year . '-' . $month . '-01'));
		$space_start = date('N' , $first_day);
		$end_day = date('t' , $first_day);
		$last_day = strtotime(date($year . '-' . $month . '-' . $end_day));
		$space_end = date('N' , $last_day);
		switch($space_end) {
			case 1: $space_end = 7; break;
			case 2: $space_end = 6; break;
			case 3: $space_end = 5; break;
			case 5: $space_end = 3; break;
			case 6: $space_end = 2; break;
			case 7: $space_end = 0; break;
		}

		$html[] = '<div class="item">';
		$html[] = '<h4>' . self::monthName( $month, false ) . ' ' . $year . '</h4><div class="month">';
		for($d=1;$d<=7;$d++) {
			$html[] = '<div class="weekname">' . self::dayName( $d, true ) . '</div>';
		}
		if($space_start>0) {
			for($s=1;$s<$space_start;$s++) {
				$html[] = "<div></div>";
			}
		}
		for($d=1;$d<=$end_day;$d++) {
			$check = $year."-".(($month<=9) ? "0" . $month : $month)."-".(($d<=9) ? "0" . $d : $d);
			if($check < date('Y-m-d')) {
				$html[] = "<div class=\"day day-disabled\">" . $d . "</div>";
			} else {
				$amount = ObjectsPrices::getAmount( $data['object_id'], $data['room_id'], $check);
				$html[] = "<div class=\"day\" data-date=\"".$check."\">" . $d . "<br/><small>" . $amount . " zł</small></div>";
				unset($amount);
			}

		}
		if($space_end>0) {
			for($s=1;$s<$space_end;$s++) {
				$html[] = "<div></div>";
			}
		}
		$html[] = '</div></div>';

		return implode("",$html);
	}

	public function verify( )
	{

	}

	public static $last_id;

	public function add()
	{
		global $request, $config, $app_url;

		$this->verify();
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Formularz zawiera błędy:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		$sid = session_id();
		session_regenerate_id();
		$sid = session_id();

		$Result = Db::insert(self::$table , "null,
		" . (!empty(User::$user['id']) ? "'" . User::$user['id'] . "'" : "NULL") . ",
		'" . $request->post['checkin'] . "',
		'" . $request->post['checkout'] . "',
		'" . $request->post['object_id'] . "',
		'" . $request->post['room_id'] . "',
		'" . $sid . "',
		'" . $request->post['first_name'] . "',
		'" . $request->post['last_name'] . "',
		'" . $request->post['phone'] . "',
		'" . $request->post['email'] . "',
		NULL,
		'" . $request->post['amount'] . "',
		'" . $request->post['advance_amount'] . "',
		'FALSE',
		NULL,
		NULL,
		'PENDING',
		'" . $request->post['days'] . "',
		'" . $request->post['res_adult'] . "',
		'" . $request->post['res_child1'] . "',
		'" . $request->post['res_child2'] . "',
		'FALSE',
		NULL,
		NULL");

		if($Result == true) {

			Mail::$address = $request->post['email'];
			Mail::$name = $config['default_email'];
			Mail::$subject = "Twoja rezerwacja ze strony " . str_replace("http://" , "" , $app_url);
			$msg = Emails::getByName("booking-new");
			$msg = str_replace("[first_name]" , $request->post['first_name'], $msg);
			$msg = str_replace("[last_name]" , $request->post['last_name'], $msg);
			$msg = str_replace("[email]" , $request->post['email'], $msg);
			$msg = str_replace("[phone]" , $request->post['phone'], $msg);
			$msg = str_replace("[days]" , $request->post['days'], $msg);
			$msg = str_replace("[res_adult]" , $request->post['res_adult'], $msg);
			$msg = str_replace("[res_child1]" , $request->post['res_child1'], $msg);
			$msg = str_replace("[res_child2]" , $request->post['res_child2'], $msg);
			$msg = str_replace("[checkin]" , $request->post['checkin'], $msg);
			$msg = str_replace("[checkout]" , $request->post['checkout'], $msg);
			$msg = str_replace("[amount]" , $request->post['amount'], $msg);
			$msg = str_replace("[advance_amount]" , $request->post['advance_amount'], $msg);
			$msg = str_replace("[payment-link]" , 'tutaj powinien się wstawić link do szybkiej płatności online', $msg);
			Mail::$text = $msg;

			$Result = Mail::send();
			if($Result !== true) {
				self::$Error[] = Mail::$error;
				Kernel::setMessage("ERROR" , "Błąd podczas próby wysłania maila:<br/>" . implode("<br/>" , self::$Error));
				return false;
			}

			self::$last_id = Db::last_id( self::$table );
			Kernel::setMessage("NOTICE" , "Rezerwacja została pomyślnie zapisana");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych:<br/>" . self::$Error);
			return false;
		}
	}

	public function save( $id )
	{
		global $request, $config, $app_url;

		$this->verify();
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Formularz zawiera błędy:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		$result = Db::update( self::$table , "first_name = '" . $request->post['first_name'] . "',
		last_name = '" . $request->post['last_name'] . "',
		phone = '".  $request->post['phone'] . "',
		email = '". $request->post['email'] . "'" , "id='" . $id . "'");

		if( $result == true ) {
			Kernel::setMessage("NOTICE" , "Pomyślnie zapisano zmiany");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych:<br/>" . self::$Error);
		}


	}

	public function delete( $id )
	{
		$Row = Db::row("object_id, room_id" , self::$table , "WHERE id='" . $id . "'");
		$this->cancel( $id, $Row['object_id'], $Row['room_id'] );
		return Db::delete( self::$table , "id='" . $id . "'");
	}

/**
 * Zmiana statusu płatności na podstawie ID rezerwacji
 * (int) $id
 * (string) $status
 */

	public static function _updatePayment( $id, $status = 'PENDING', $manual = false )
	{
		$amount = Db::row("advance_amount" , self::$table , "WHERE id='" . $id . "'");
		$amount = $amount['advance_amount'];

		if( $status == "CONFIRM" ) {
			Db::update( self::$table , "payment='TRUE', payment_date=NOW(), payment_amount = '" . $amount . "', payment_status='CONFIRM'" , "id='" . $id . "'");
			if ( $manual == false ) {
				die("OK");
			}
		} else {
			Db::update( self::$table , "payment_status='" . $status . "'" , "id='" . $id . "'");
		}

	}

	public function cancel( $id, $object_id, $room_id )
	{
		global $request;

		$Result = Db::row("*" , self::$table , "WHERE id='" . $id . "' AND object_id='" . $object_id . "' AND room_id='" . $room_id . "'");
		if(!empty($Result)) {
			$start_date = $Result['checkin'];
			$now = date('Y-m-d');
			$max_date = date('Y-m-d' , strtotime("+" . $Result['days'] . " days"));

			if($start_date >= $max_date) {
				//$res = Settlement::cancel($id, $object_id);
			}

			if($start_date < $max_date) {
				//$res = Settlement::cancel($id, $object_id);
			}

			Db::update(self::$table , "payment_status='CANCEL'" , "id='" . $id . "'");
			Kernel::setMessage("NOTICE" , "Pomyślnie anulowano rezerwację");
			return true;
		}

		return false;

	}
}
