<?php
/**
 * Payment class
 *
 * @package		Modules
 * @subpackage	Payment
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

class Payment
{
	protected static $table = "payment_history";
	public static $Error;
	public static $module;

	public function __construct()
	{
		global $config, $route;

		self::$module = $config['payments_module'];

		self::$table = $config['db_prefix'] . self::$table;

		$this->register( $route );
	}

	protected function register( $route )
	{
		$route->get('(payments)\/:string', [
			'module' => 'payments', 'file' => 'payments', 'command' => '$2'
		]);
	}

	public static function add_history($o)
	{
		if(empty($o)) {
			die("Payment::add_history parametr option is empty");
		}

		return Db::insert( self::$table , "null,
		NOW(),
		'" . $o['object_id'] . "',
		" . (!empty($o['special_id']) ? "'" . $o['special_id'] . "'" : "NULL") . ",
		'" . $o['promotion_id'] . "',
		'" . $o['type'] . "',
		'" . $o['status'] . "'");
	}

	public function prepare( $data = null )
	{
		global $request, $app_url, $config;

		if(is_null($data)) {
			if(empty($request->post['object_id'])) {
				self::$Error[] = "nie przekazano informacji odnośnie oferty do wyróżnienia";
			}
			if(empty($request->post['pid'])) {
				self::$Error[] = "nie przekazano informacji odnośnie wybranej opcji wyróżnienia";
			}
			if(empty($request->post['payment'])) {
				self::$Error[] = "nie przekazano informacji odnośnie wybranej opcji płatności";
			}

			if(!empty(self::$Error)) {
				Kernel::setMessage("ERROR" , "Wystąpiły błedy: " . implode("<br/>" , self::$Error));
				return false;
			}
		}
		switch(self::$module)
		{
			case "dotpay":
				$data = self::prepare_dotpay( $data );
			break;

			case "p24":
				$data = self::prepare_p24( $data );
			break;

			case "tpay":
				$data = self::prepare_tpay( $data );
			break;
		}

		return $data;
	}

	public static function prepare_dotpay( $form = null )
	{
		global $config, $request, $app_url;
		if(is_null($form)) {
			$form = $request->post;
		}

		if($form == "sms") {

			$payment = Promotion::getSMS( $form['pid'] );
			$vat = (double) ($config['vat'] * 0.01) + 1;
			$data = [
				"object_id" => (!empty($form['object_id']) ? $form['object_id'] : null),
				"special_id" => (!empty($form['special_id']) ? $form['special_id'] : null),
				"pid" => $form['pid'],
				"payment" => $form['payment'],
				"sms_number" => $payment['sms_number'],
				"sms_text" => $payment['sms_text'],
				"sms_amount" => (double) $payment['amount_sms'] * $vat
			];
		}

		if($form['payment'] == "online") {
			// Jeżeli jest to abonament
			if(!empty($form['pid'])) {
				$amount = Promotion::getAmount( $form['pid'], $form['payment'] );
				$control = 'oid=' . $form['object_id'] . ',' . 'pid=' . $form['pid'] . (!empty($form['special_id']) ? ',sid=' . $form['special_id'] : "");
				$description = Promotion::getName($request->post['pid']) . " na stronie " . $app_url;
				$url = $app_url . "payments/finish/";
				$urlc = $app_url . "payments/verify/";
				$back_url = $app_url . "payments/finish/";
			} else {
				$amount = $form['amount'];
				$control = $form['control'];
				$description = $form['description'];
				$url = $app_url . "booking/payment-finish/";
				$urlc = $app_url . "booking/payment-verify/";
				$back_url = $app_url . "booking/payments-finish/";
			}

			if(empty($form['phone'])) {
				$form['phone'] = null;
			}

			$data = [
				"api_version"	=> Dotpay::$api_version,
				"lang" 			=> "pl",
				"payment" 		=> $form['payment'],
				"firstname" 	=> (!empty(User::$user['first_name']) ? User::$user['first_name'] : $form['firstname']),
				"lastname" 		=> (!empty(User::$user['last_name']) ? User::$user['last_name'] : $form['lastname']),
				"email" 		=> (!empty(User::$user['email']) ? User::$user['email'] : $form['email']),
				"phone" 		=> (!empty(User::$user['phone']) ? User::$user['phone'] : $form['phone']),
				"amount" 		=> $amount,
				"currency"		=> "PLN",
				"description" 	=> $description,
				"control" 		=> $control,
				"p_info" 		=> $config['dotpay_pinfo'],
				"p_email" 		=> $config['dotpay_pemail'],
				"url" 			=> $url,
				"urlc" 			=> $urlc,
				"channel_groups"=> "K,T,P",
				"buttontext" 	=> "Powrót do serwisu",
				"type"			=> "0",
				"country"		=> "POL",
				"ignore_last_payment_channel" => "true",

				//"back_url" 		=> $back_url
			];
		}

		return $data;
	}

	public static function prepare_p24()
	{
		global $config, $request, $app_url;

		if($request->post['payment'] == "online") {
			$amount = (double) Promotion::getAmount( $request->post['pid'], $request->post['payment'] ) * 100;
			$control = (isset($request->post['control']) ? $request->post['control'] : 'oid=' . $request->post['object_id'] . ',' . 'pid=' . $request->post['pid']);

			$session_id = p24::registerSession();

			$data = [
				"payment" => $request->post['payment'],
				"control" => $control,
				"firstname" => (!empty(User::$user['first_name']) ? User::$user['first_name'] : null),
				"lastname" => (!empty(User::$user['last_name']) ? User::$user['last_name'] : null),
				"email" => (!empty(User::$user['email']) ? User::$user['email'] : null),
				"phone" => (!empty(User::$user['phone']) ? User::$user['phone'] : null),
				"p24" => [
					"p24_pos_id" => (isset($request->post['p24_pos_id']) ? $request->post['p24_pos_id'] : $config['p24_pos_id']),
					"p24_merchant_id" => (isset($request->post['p24_pos_id']) ? $request->post['p24_pos_id'] : $config['p24_pos_id']),
					"p24_session_id" => $session_id,
					"p24_client" => (!empty(User::$user['first_name']) ? User::$user['first_name'] : '') . ' ' . (!empty(User::$user['last_name']) ? User::$user['last_name'] : ''),
					"p24_email" => (isset($request->post['email']) ? $request->post['email'] : ""),
					"p24_phone" => (isset($request->post['phone']) ? $request->post['phone'] : ""),
					"p24_amount" => $amount,
					"p24_description" => Promotion::getName($request->post['pid']) . " na stronie " . $app_url,
					"p24_transfer_label" => $config['dotpay_pinfo'],
					"p24_languge" => "pl",
					"p24_currency" => "PLN",
					"p24_url_return" => $app_url . "payments/finish/",
					"p24_url_status" => $app_url . "payments/verify/",
					"p24_api_version" => "3.2",
					"p24_sign" => md5( $session_id . '|' . $config['p24_pos_id'] . '|' . $amount . '|PLN|' . $config['p24_crc_key'] ),
					"p24_name_1" => Promotion::getName($request->post['pid']) . " na stronie " . $app_url,
					"p24_quantity_1" => 1,
					"p24_price_1" => $amount,
					"p24_number_1" => $control

				]
			];
		}

		return $data;
	}

	public static function prepare_tpay()
	{
		global $request, $config, $app_url;

		$amount = (double) Promotion::getAmount( $request->post['pid'], $request->post['payment'] );
		$crc = (isset($request->post['control']) ? $request->post['control'] : 'oid=' . $request->post['object_id'] . ',' . 'pid=' . $request->post['pid']);

        $description = Promotion::getName($request->post['pid']) . " na stronie " . $app_url;
        $md5 = tpay::make_md5( $amount, $crc );

        $post = [
            'id' => $config['tpay_merchant_id'],
            'amount' => $amount,
            'description' => $description,
            'crc' => $crc,
            'return_url' => $app_url . 'payments/finish?status=OK',
            'result_url' => $app_url . 'payments/verify',
            'result_email' => $config['tpay_email'],
            'language' => 'pl',
            'currency' => 'PLN',
            'md5sum' => $md5,
            'name' => (!empty(User::$user['first_name']) ? User::$user['first_name'] : '') . ' ' . (!empty(User::$user['last_name']) ? User::$user['last_name'] : ''),
            'email' => (!empty(User::$user['email']) ? User::$user['email'] : null)
        ];

        //$this->insert( $amount, $crc, $md5, $this->post['packet'] );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://secure.tpay.com/groups-" . $config['tpay_merchant_id'] . "0.js?json");
        //curl_setopt($ch, CURLOPT_URL, "https://secure.tpay.com/groups-10100.js?json");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "json=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $bank_list = curl_exec($ch);
        curl_close($ch);

        return ['payment' => 'online', 'data' => $post, 'banklist' => json_decode($bank_list, true)];
	}

	public static function success( $result , $control )
	{
		$oid_pm = '/oid\=([0-9]+)/';
		$pid_pm = '/pid\=([0-9]+)/';
		$sid_pm = '/sid\=([0-9]+)/';
		$bid_pm = '/bid\=([0-9]+)/';

		preg_match_all($oid_pm, $control, $m);
		if((!empty($m)) && (!empty($m['1']['0']))) {
			$oid = $m['1']['0'];
			unset($m);
		}

		preg_match_all($pid_pm, $control, $m);
		if((!empty($m)) && (!empty($m['1']['0']))) {
			$pid = $m['1']['0'];
			unset($m);
		}

		preg_match_all($sid_pm, $control, $m);
		if((!empty($m)) && (!empty($m['1']['0']))) {
			$sid = $m['1']['0'];
			unset($m);
		}

		preg_match_all($bid_pm, $control, $m);
		if((!empty($m)) && (!empty($m['1']['0']))) {
			$bid = $m['1']['0'];
			unset($m);
		}

		if( !empty($sid) && (!is_numeric( $sid )) ) {
			unset($sid);
		}

		if((empty($oid)) OR (empty($pid))) {
			return false;
		}

		self::add_history([
			'object_id' => $oid,
			'promotion_id' => $pid,
			'special_id' => (!empty($sid) ? $sid : null),
			'booking_id' => (!empty($bid) ? $bid : null),
			'type' => 'ONLINE',
			'status' => $result
		]);

		Kernel::log('success.log' , print_r([
			'object_id' => $oid,
			'promotion_id' => $pid,
			'special_id' => (!empty($sid) ? $sid : null),
			'booking_id' => (!empty($bid) ? $bid : null),
			'type' => 'ONLINE',
			'status' => $result
		],true));

		switch( $result )
		{
			case "NEW":
			break;

			case "CONFIRM":
				if(!empty($sid)) {
					$result = SpecialOffers::addExpire($oid, $pid, $sid);
				} else {
					$result = Objects::setMainExpire($oid, $pid);
				}
			break;

			case "REFUSED":
			break;

			case "CANCEL":
			break;
		}
	}

	public static function verify( $only_verify = false )
	{
		global $app_path, $request;

		if(self::$module == "dotpay") {
			if(!empty($request->post['operation_status']))
			{
				$result = Dotpay::verify( "ONLINE" );
				if( $only_verify == false ) {
					self::success( $result , $request->post['control'] );
					ob_end_clean();
					die("OK");
				} else {
					return $result;
				}
			}
		}

		if( self::$module == "p24" ) {
			if( !empty($request->post['p24_session_id'])) {
				$control = p24::getBySession( $request->post['p24_session_id'] );
				//try {
					//self::log( print_r($request->post,true) );
					$result = p24::verify( "ONLINE", $request->post );
					if($result['error'] == 0) {
						$result = "CONFIRM";
						self::success( $result , $control['control'] );
					}
				//} catch (Exception $e) {
				//	self::log( 'Caught exception: ' . $e->getMessage() );
				//}
			}
		}

		if( self::$module == "tpay" ) {
			$result = tpay::verify( $request->post );
			/**Kernel::log('tpay.log' , 'Receive tpay payment');
			Kernel::log('tpay.log' , print_r($request->post, true));
			Kernel::log('tpay.log' , print_r($result, true));**/
			if(empty($result['result'])) {
				//Kernel::log('tpay.log' , 'WHY IT IS FALSE');
				die("FALSE");
			}

			self::success( "CONFIRM" , $result['result'] );

			die("TRUE");
		}

		die("OK");
	}

	public static function verify_sms()
	{
		global $request;

		$result = Dotpay::request('SMS');
		self::add_history([
			'object_id' => $request->post['object_id'],
			'promotion_id' => $request->post['pid'],
			'type' => 'SMS',
			'status' => (($result == true) ? 'CONFIRM' : 'REFUSED')
		]);

		if($result == true) {
			$result = Objects::setMainExpire($request->post['object_id'], $request->post['pid']);
			return true;
		} else {
			self::$Error = Dotpay::$Error;
			return false;
		}
	}

	public static function log( $text )
	{
		global $app_path, $request;

		file_put_contents($app_path . "logs/payment_verify.log", $text . PHP_EOL, FILE_APPEND);
	}
}
