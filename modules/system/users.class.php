<?php
/**
 * System Users class
 *
 * @package		Modules
 * @subpackage	System/Users
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


class User {

	public static $admin = false;
	public static $user = false;
	public static $Error;
	public static $table = "users";
	protected static $new_password;
	public static $types = [
		["id" => "ADMIN", "name" => "Administrator główny"],
		["id" => "MOD" , "name" => "Moderator strony"],
		["id" => "USER" , "name" => "Użytkownik strony"]
	];
	public static $account_types = [
		["id" => "USER", "name" => "Użytkownik"],
		["id" => "OWNER" , "name" => "Właściciel/zarządca obiektu"]
	];
	public static $account_person = [
		["id" => "PRIVATE" , "name" => "Osoba fizyczna"],
		["id" => "COMPANY" , "name" => "Firma"]
	];

	public function __construct()
	{
		global $config, $route;
		self::$table = $config['db_prefix'] . self::$table;

		Language::load("system");

		self::$account_types = Language::get('system' , 'users_class_account_types');
		self::$account_person = Language::get('system' , 'users_class_account_person');

		$this->register( $route );
	}

	protected function register( $route )
	{
		$route->get('(panel)\/:string' , [
			'module' => 'system', 'file' => 'users', 'command' => '$2'
		]);

		$route->get('(panel)\/(activate)\/:string' , [
			'module' => 'system', 'file' => 'users', 'command' => 'activate', 'id' => '$3'
		]);
	}

	public static function stats()
	{
		return array(
			'all' => Db::count(self::$table , "type='USER'"),
			'today' => Db::count(self::$table , "type='USER' AND create_date=NOW()")
		);
	}

	public static function getField($field, $id)
	{
		$Result = Db::row("*" , self::$table, "WHERE id='" . $id ."'");
		if(!empty($Result)) {
			return $Result[$field];
		}
	}

	public static function getAdminData()
	{
		if(!empty($_SESSION['admin'])) {
			$decoded = base64_decode($_SESSION['admin']);
			self::$admin = unserialize($decoded);
		}
	}

	public static function storeAdminData()
	{
		if(!empty(self::$admin)) {
			$_SESSION['admin'] = base64_encode(serialize(self::$admin));
		} else {
			unset($_SESSION['admin']);
		}
	}

	public static function getUserData()
	{
		if(!empty($_SESSION['user'])) {
			$decoded = base64_decode($_SESSION['user']);
			self::$user = unserialize($decoded);
		}
	}

	public static function storeUserData()
	{
		if(!empty(self::$user)) {
			$_SESSION['user'] = base64_encode(serialize(self::$user));
		} else {
			unset($_SESSION['user']);
		}
	}

	public static function getUserType( $uid )
	{
		$result = Db::row("type" , self::$table , "WHERE id='" . $uid . "'");
		if(!empty($result)) {
			return $result['type'];
		}
	}

	public static function isUserLogged($type = "OWNER")
	{
		global $app_url;

		if(!is_array($type)) {
			if( !empty(self::$user['id'] )) {
				if(self::$user['user_type'] == $type) {
					return true;
				}
			}
			if( self::$user['user_type'] == 'SELECT') {
				return true;
			}
			Kernel::redirect($app_url . "panel/login/");
		} else {
			if(in_array(self::$user['user_type'], $type) == true) {
				return true;
			}
			if( self::$user['user_type'] == 'SELECT') {
				return true;
			}
			Kernel::redirect($app_url . "panel/login/");
		}
	}

	public function userLogin()
	{
		global $request;

		if($request->post['token'] != $_SESSION['token']) {
			//self::$Error[] = "Nieprawidłowy token";
		}

		if(empty($request->post['login'])) {
			self::$Error[] = "Musisz podać login";
		}

		if(empty($request->post['password'])) {
			self::$Error[] = "Musisz podać hasło";
		}

		if(!empty(self::$Error)) {
			return false;
		}

		$password = $this->hashPassword($request->post['login'],$request->post['password']);

		$result = Db::check(self::$table , "login='" . $request->post['login'] . "' AND pass='" . $password . "' AND type='USER' AND status='TRUE'");

		if( $result == true ) {
			self::$user = Db::row("*" , self::$table , "WHERE login='" . $request->post['login'] . "' AND
			pass='" . $password . "'");
			self::storeUserData();

			Db::update( self::$table, "last_login_date = NOW()" , "login='" . $request->post['login'] ."'");
			return true;
		} else {
			Db::update( self::$table, "error_login_date = NOW()" , "login='" . $request->post['login'] ."'");
			self::$Error[] = "Błędne logowanie - dane są nieprawidłowe";
		}
	}

	public static function userLogout()
	{
		if(!empty(self::$user)) {
			self::$user = null;
			self::storeUserData();
			return true;
		}
	}

	public function adminLogin()
	{
		global $request;

		if($request->post['token'] != $_SESSION['token']) {
			//self::$Error[] = "Nieprawidłowy token";
		}

		if(empty($request->post['login'])) {
			self::$Error[] = "Musisz podać login";
		}

		if(empty($request->post['password'])) {
			self::$Error[] = "Musisz podać hasło";
		}

		if(is_array(self::$Error)) {
			return false;
		}

		$password = $this->hashPassword($request->post['login'],$request->post['password']);

		$result = Db::check(self::$table , "login='" . $request->post['login'] . "' AND pass='" . $password . "' AND (type='ADMIN' OR type='MOD')");

		if( $result == true ) {
			self::$admin = Db::row("*" , self::$table , "WHERE login='" . $request->post['login'] . "' AND pass='" . $password . "'");
			self::storeAdminData();

			Db::update( self::$table, "last_login_date = NOW()" , "login='" . $request->post['login'] ."'");
			return true;
		} else {
			Db::update( self::$table, "error_login_date = NOW()" , "login='" . $request->post['login'] ."'");
			self::$Error[] = "Błędne logowanie - dane są nieprawidłowe";
		}

		return false;

	}

	public static function adminLogout()
	{
		if(!empty(self::$admin)) {
			self::$admin = null;
			self::storeAdminData();
			return true;
		}
	}

	public function verify( $admin = false, $edit = false )
	{
		global $request, $config;

		if(empty($request->post['login'])) { self::$Error[] = "musisz wprowadzić login"; } else {
			if( strlen($request->post['login']) < 3 ) {
				self::$Error[] = "login musi się składać z minimum 3 znaków";
			}

			if($edit == false) {
				if( Db::check( self::$table , "login='" . $request->post['login'] . "'") == true ) {
					self::$Error[] = "podany login jest już zajęty";
				}
			}
		}

		if( $admin == false ) {
			if(empty($request->post['pass'])) { self::$Error[] = "musisz wprowadzić hasło"; } else {
				if( strlen($request->post['pass']) < 3 ) {
					self::$Error[] = "hasło musi się składać z minimum 3 znaków";
				}
			}

			if(empty($request->post['pass_repeat'])) {
				self::$Error[] = "musisz wprowadzić ponownie hasło";
			}

			if( !empty($request->post['pass']) && !empty($request->post['pass_repeat'])) {
				if( $request->post['pass'] !== $request->post['pass_repeat'] ) {
					self::$Error[] = "hasła się nie zgadzają - wprowadź poprawne hasło do pola powtórz hasło";
				}
			}

			if(empty($request->post['rules'])) {
				self::$Error[] = "musisz zaakceptować regulamin, aby utworzyć konto";
			}
			if( !empty($config['google_recaptcha_secretkey']) ) {
				$recaptcha = new \ReCaptcha\ReCaptcha($config['google_recaptcha_secretkey']);
				$resp = $recaptcha->verify($request->post['g-recaptcha-response'], Kernel::getIp());
				if (!$resp->isSuccess()) {
					self::$Error[] = implode("" , $resp->getErrorCodes());
				}
			}
		}
		if( $admin == true ) {
			if(empty($request->post['type'])) { self::$Error[] = "musisz wybrać profil konta"; }
		}

		if(empty($request->post['first_name'])) { self::$Error[] = "musisz podać imię użytkownika"; }
		if(empty($request->post['last_name'])) { self::$Error[] = "musisz podać nazwisko użytkownika"; }
		if(empty($request->post['email'])) { self::$Error[] = "musisz podać adres e-mail użytkownika"; }
		if(empty($request->post['user_type'])) { self::$Error[] = "musisz wybrać typ użytkownika"; }
		if(empty($request->post['user_account'])) { self::$Error[] = "musisz wybrać rodzaj konta"; }
	}

	protected static $search_query;
	protected function search()
	{
		global $request;
		if(!empty($request->get['q'])) {
			$qry[] = "id = '" . $request->get['q'] . "'";
			$qry[] = "login='%" . $request->get['q'] . "%'";
			$qry[] = "name LIKE '%" . $request->get['q'] . "%'";
			$qry[] = "email LIKE '%" . $request->get['q'] . "%'";
			$qry[] = "first_name LIKE '%" . $request->get['q'] . "%'";
			$qry[] = "last_name LIKE '%" . $request->get['q'] . "%'";
			$qry[] = "company_name LIKE '%" . $request->get['q'] . "%'";

			if(!empty($qry)) {
				self::$search_query[] = "(" . implode(" OR " , $qry) . ")";
				unset($qry);
			}
		}

		if(!empty($request->get['t'])) {
			self::$search_query[] = "type = '" . $request->get['t'] . "'";
		}

		if(!empty($request->get['at'])) {
			self::$search_query[] = "user_account = '" . $request->get['at'] . "'";
		}
	}

	public static function getEmailsByType( $type )
	{
		return Db::exec("email" , self::$table , "WHERE user_type='" . $type . "' GROUP BY email");
	}

	public static function getForSelect()
	{
		$Result = Db::exec("*" , self::$table , "WHERE user_type='OWNER'");
		if(!empty($Result)) {
			foreach($Result as $k=>$i) {
				$Select[] = array(
					'id' => $i['id'],
					'name' => $i['login'] . ' [' . (!empty($i['company_name']) ? $i['company_name'] . ' - ' : '') . $i['first_name'] . ' ' . $i['last_name'] . ']'
				);
			}
			if(!empty($Select)) {
				return $Select;
			}
		}
	}

	public function get( $id = null )
	{
		if(is_null($id)) {
			$this->search();
			Paginate::$query = "SELECT * FROM " . self::$table . (!empty(self::$search_query) ? " WHERE " . implode(" AND " , self::$search_query) : "") . " ORDER BY id DESC";
			Paginate::$perpage = 25;
			return Paginate::make();
		}

		$r = Db::row("*" , self::$table , "WHERE id='" . (int) $id ."'");
		if(empty($r)) {
			return;
		}

		$r['edit'] = true;
		$r['rules'] = json_decode( $r['rules'], true);
		return $r;
	}

	public function add_quick( $object_id, $regenerate = false )
	{
		global $app_url, $messages;

		$access = Objects::generateLoginPassword($object_id, $regenerate);
		if(!empty($access)) {
			$login = $access['login'];
			if( Db::check( self::$table , "login='" . $login . "'") == true ) {
				return $this->add_quick( $object_id, true );
			}
			$login = $access['login'];
			$password = $access['password'];
			$password = $this->hashPassword($login, $password);
			$email = $access['login'];
			$phone = $access['phone'];
			$street = $access['street'];
			$postcode = $access['postcode'];
			$city = $access['city'];

			$email_text = Emails::getByName("object-create-account");
			$email_text = str_replace("[url]" , $app_url , $email_text);
			$email_text = str_replace("[login]" , $login , $email_text);
			$email_text = str_replace("[password]" , $password , $email_text);

			$Result = Db::insert( self::$table , "null,
			null,
			'" . $login . "',
			'" . $password . "',
			'',
			'" . $email . "',
			'" . $phone . "',
			'USER',
			'FALSE',
			'OWNER',
			'PRIVATE',
			NULL,
			NULL,
			NULL,
			'',
			'',
			'" . $street . "',
			'" . $postcode . "',
			'" . $city . "',
			NULL,
			NULL,
			NOW(),
			NULL,
			NULL,
			NULL,
			NULL");

			$uid = Db::last_id(self::$table, "id");

			if( $Result == true ) {
				$messages->cronAddMessage( [
					'subject' => 'Twoje konto zostało utworzone',
					'text' => $email_text,
					'uid' => User::$admin['id']
				], $email);

				Objects::updateUID( $uid, $object_id );
				Kernel::setMessage("NOTICE" , "Utworzono konto użytkownika: " . $login . " i dodano e-mail do kolejki wysłania");
				return true;
			} else {
				self::$Error = Db::error();
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
				return false;
			}
		}
	}

	public function add( $admin = true )
	{
		global $app_url, $app_path, $request, $config;

		$this->verify( $admin );
		if( $admin == true ) {
			if(!empty($_FILES['photo']['name'])) {
				$photo = $this->_upload();
			}
		}
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Wykryto błędy w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		$password = $this->hashPassword( $request->post['login'], $request->post['pass'] );

		if(!empty($request->post['rules'])) {
			$rules = json_encode( $request->post['rules'] );
		} else {
			$rules = '';
		}

		if( $admin == true ) {
			// Tworzenie konta poprzez PA
			$result = Db::insert( self::$table , "null,
			null,
			'" . $request->post['login'] . "',
			'" . $password . "',
			'" . $request->post['name'] . "',
			'" . $request->post['email'] . "',
			'" . $request->post['phone'] . "',
			'" . $request->post['type'] . "',
			'FALSE',
			'" . $request->post['user_type'] . "',
			'" . $request->post['user_account'] . "',
			" . (isset($request->post['company_name']) ? "'" . $request->post['company_name'] . "'," : "NULL,") . "
			" . (isset($request->post['company_pin']) ? "'" . $request->post['company_pin'] . "'," : "NULL,") . "
			" . (isset($request->post['bank_account']) ? "'" . $request->post['bank_account'] . "'," : "NULL,") . "
			'" . $request->post['first_name'] . "',
			'" . $request->post['last_name'] . "',
			'" . $request->post['street'] . "',
			'" . $request->post['postcode'] . "',
			'" . $request->post['city'] . "',
			'" . $rules . "',
			NULL,
			NULL,
			NOW(),
			NULL,
			NULL,
			NULL,
			'" . (!empty($photo) ? $photo : '') . "'");

			if($result == true) {
				Mail::$address = $request->post['email'];
				Mail::$name = $request->post['name'];
				Mail::$subject = "Utworzono nowe konto użytkownika";
				$text = Emails::getByName("admin-new-account");
				$text = str_replace("[name]" , $request->post['name'], $text);
				$text = str_replace("[login]" , $request->post['login'], $text);
				$text = str_replace("[email]" , $request->post['email'], $text);
				$text = str_replace("[login-url]" , $app_url . "admin/login.html", $text);
				$text = str_replace("[password]" , $request->post['pass'], $text);
				$email_template = file_get_contents( $app_path . "include/email_templates/newsletter/template.html");
				$msg = str_replace("[app-url]" , $app_url, $email_template);
				$msg = str_replace("[sender-name]" , $config['service_meta_title'], $msg);
				$msg = str_replace("[subject]" , Mail::$subject, $msg);
				$msg = str_replace("[content]" , html_entity_decode($text), $msg);
				Mail::$text = $msg;
				if(Mail::send() == true) {
					return true;
				} else {
					Kernel::setMessage("ERROR" , "<b>Błąd wysyłki wiadomości e-mail:</b><br/>" . Mail::$error);
					return false;
				}
			} else {
				self::$Error = Db::error();
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
				return false;
			}
		} else {
			$Result = Db::insert( self::$table , "null,
			null,
			'" . $request->post['login'] . "',
			'" . $password . "',
			" . (isset($request->post['name']) ? "'" . $request->post['name'] . "'," : "NULL,") . "
			'" . $request->post['email'] . "',
			'" . $request->post['phone'] . "',
			'USER',
			'FALSE',
			'" . $request->post['user_type'] . "',
			'" . $request->post['user_account'] . "',
			" . (isset($request->post['company_name']) ? "'" . $request->post['company_name'] . "'," : "NULL,") . "
			" . (isset($request->post['company_pin']) ? "'" . $request->post['company_pin'] . "'," : "NULL,") . "
			" . (isset($request->post['bank_account']) ? "'" . $request->post['bank_account'] . "'," : "NULL,") . "
			'" . $request->post['first_name'] . "',
			'" . $request->post['last_name'] . "',
			'" . $request->post['street'] . "',
			'" . $request->post['postcode'] . "',
			'" . $request->post['city'] . "',
			'" . $rules . "',
			NULL,
			NULL,
			NOW(),
			NULL,
			NULL,
			NULL,
			NULL");

			if( $Result == false ) {
				self::$Error = Db::error();
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
				return false;
			}

			$token = md5($request->post['login'] . '|' . $request->post['email'] . '|' . $request->post['user_type'] . '|' . $request->post['user_account']);

			Mail::$address = $request->post['email'];
			Mail::$name = $request->post['first_name'] . " " . $request->post['last_name'];
			Mail::$subject = "Utworzono nowe konto - wymagana aktywacja";
			$text = Emails::getByName("user-new-account");
			$text = str_replace("[name]" , $request->post['first_name'] . " " . $request->post['last_name'], $text);
			$text = str_replace("[login]" , $request->post['login'], $text);
			$text = str_replace("[email]" , $request->post['email'], $text);
			$text = str_replace("[activate-url]" , $app_url . "panel/activate/" . $token, $text);
			$text = str_replace("[login-url]" , $app_url . "panel/login", $text);
			$text = str_replace("[password]" , $request->post['pass'], $text);

			$email_template = file_get_contents( $app_path . "include/email_templates/newsletter/template.html");
			$msg = str_replace("[app-url]" , $app_url, $email_template);
			$msg = str_replace("[sender-name]" , $config['service_meta_title'], $msg);
			$msg = str_replace("[subject]" , Mail::$subject, $msg);
			$msg = str_replace("[content]" , html_entity_decode($text), $msg);
			Mail::$text = $msg;

			if(Mail::send() == false) {
				Kernel::setMessage("ERROR" , "<b>Błąd wysyłki wiadomości e-mail:</b><br/>" . Mail::$error);
				return false;
			}
		}

		if($Result == true) {
			if(!empty($request->post['agree'])) {
				Newsletter::quickAdd( $request->post['email'], true );
			}
			Kernel::setMessage("NOTICE" , "Rejestracja przebiegła pomyślnie");
			return true;
		} else {
			(isset(Mail::$error) ? Kernel::setMessage("ERROR" , "<b>Błąd wysyłki wiadomości e-mail:</b><br/>" . Mail::$error) : "");
			if(!empty(self::$Error)) {
				foreach(self::$Error as $item) {
					Kernel::setMessage("ERROR" , $item);
				}
			}
			return false;
		}
	}

	protected static function parseEmailTemplate( $content, $email, $data = null )
	{
		global $app_url, $config;

		try {
			$replacements = [
				'[app-url]' 	=> $app_url,
				'[sender-name]'	=> (!empty($config['meta_title']) ? $cofig['meta_title'] : str_replace("http://", "" , $app_url)),
				'[subject]'	=> (!empty($data['subject']) ? $data['subject'] : ""),
				'[content]'	=> (!empty($data['content']) ? $data['content'] : "")
			];

			return strtr($content, $replacements);
		} catch (Exception $e) {
			Kernel::setMessage("ERROR" , 'Caught exception in newsletter.class.php@parseEmailTemplate:<br/>' . $e->getMessage());
			return false;
		}

	}

	private function checkFreeLogin( $login, $i=0 )
	{
		if( Db::check( self::$table , "login = '" . $login . "'") == false) {
			return $login;
		} else {
			$i++;
			$nl = self::checkFreeLogin( $login.$i , $i);
		}
	}

	public function addUsingFacebook( $fields )
	{
		$check = Db::check(self::$table , "fb_id = '" . $fields['id'] . "'");
		if( $check == false ) {
			$status = 'TRUE';
			$type = 'USER';
			$name = explode(" " , $fields['name']);

			$login = Kernel::rewrite($fields['name']);

			$login = self::checkFreeLogin( Kernel::rewrite( $login ));
			$password = $this->hashPassword( $fields['id'], $login );

			$Result = Db::insert( self::$table , "null,
			'" . $fields['id'] . "',
			'" . $login . "',
			'" . $password . "',
			'" . $fields['name'] . "',
			'" . $fields['email'] . "',
			'',
			'" . $type . "',
			'" . $status . "',
			'SELECT',
			'PRIVATE',
			NULL,
			NULL,
			NULL,
			'" . $name[0] . "',
			'" . $name[1] . "',
			'',
			'',
			'',
			NOW(),
			NULL,
			NOW(),
			NULL,
			NULL,
			NULL,
			NULL");

			if($Result == true) {
				self::$user = Db::row("*" , self::$table , "WHERE fb_id='" . $fields['id'] . "'");
				self::storeUserData();

				Db::update( self::$table, "last_login_date = NOW()" , "fb_id='" . $fields['id'] ."'");
				return true;
			} else {
				self::$Error = Db::error();
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
				return false;
			}

		} else {
			self::$user = Db::row("*" , self::$table , "WHERE fb_id='" . $fields['id'] . "'");
			self::storeUserData();

			Db::update( self::$table, "last_login_date = NOW()" , "fb_id='" . $fields['id'] ."'");
			return true;
		}
	}

	public function save( $id, $admin = false )
	{
		global $app_path, $request;

		$this->verify( true, true );
		if(!empty($_FILES['photo']['name'])) {
			$photo = $this->_upload();
			if(!empty($photo)) {
				if(!empty($request->post['old_photo'])) {
					unlink( $app_path . "userfiles/users/" . $request->post['old_photo']);
				}
			}
		}
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Wykryto błędy w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		if( $admin == false ) {
			$result = Db::save( self::$table, "login = '" . $request->post['login'] . "',
			name = '" . $request->post['name'] . "',
			email = '" . $request->post['email'] . "',
			type = '" . $request->post['type'] . "',
			" . (isset($request->post['bank_account']) ? "bank_account = '" . $request->post['bank_account'] . "'," : "") .
			(!empty($photo) ? ",icon = '" . $photo . "'" : '') , "id='" . $id . "'");
		} else {
			$result = Db::save( self::$table, "login = '" . $request->post['login'] . "',
			name = '" . $request->post['name'] . "',
			email = '" . $request->post['email'] . "',
			phone = '" . $request->post['phone'] . "',
			type = '" . $request->post['type'] . "',
			user_type = '" . $request->post['user_type'] . "',
			user_account = '" . $request->post['user_account'] . "',
			company_name = '" . $request->post['company_name'] . "',
			company_pin = '" . $request->post['company_pin'] . "',
			" . (!empty($request->post['bank_account']) ? "bank_account = '" . $request->post['bank_account'] . "'" : "bank_account=NULL") . ",
			first_name = '" . $request->post['first_name'] . "',
			last_name = '" . $request->post['last_name'] . "',
			street = '" . $request->post['street'] . "',
			postcode = '" . $request->post['postcode'] . "',
			city = '" . $request->post['city'] . "'
			" . (!empty($photo) ? ",icon = '" . $photo . "'" : '') , "id='" . $id . "'");
		}

		if(!empty($result)) {
			if(self::$admin['id'] == $id) {
				self::$admin = Db::row("*" , self::$table , "WHERE id='" . self::$admin['id'] . "'");
				self::storeAdminData();
			}
			Kernel::setMessage("NOTICE" , "Pomyślnie zapiano zmiany");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
			return false;
		}
	}

	public function saveProfile()
	{
		global $request;

		if(empty($request->post['email'])) {
			return false;
		}

		$rules = (!empty($request->post['rules']) ? json_encode( $request->post['rules'] ) : '');

		$result = Db::update( self::$table, "email = '" . $request->post['email'] . "',
		phone = '" . $request->post['phone'] . "',
		user_account = '" . $request->post['user_account'] . "'
		" . (isset($request->post['company_name']) ? ",company_name = '" . $request->post['company_name'] . "'" : "") . "
		" . (isset($request->post['company_pin']) ? ",company_pin = '" . $request->post['company_pin'] . "'" : "") . "
		" . (isset($request->post['bank_account']) ? ",bank_account = '" . $request->post['bank_account'] . "'" : "") . "
		,first_name = '" . $request->post['first_name'] . "',
		last_name = '" . $request->post['last_name'] . "',
		street = '" . $request->post['street'] . "',
		postcode = '" . $request->post['postcode'] . "',
		city = '" . $request->post['city'] . "',
		rules = '" . $rules . "'" , "id='" . User::$user['id'] . "'");

		if( User::$user['user_type'] == "SELECT") {
			if(!empty($request->post['user_type'])) {
				Db::update( self::$table, "user_type = '" . $request->post['user_type'] . "'" , "id='" . User::$user['id'] . "'");
			} else {
				Kernel::setMessage("ERROR" , "Prosimy wybrać rodzaj konta (pole jesteś)");
				return false;
			}
		}

		User::$user = Db::row("*" , self::$table , "WHERE id='" . User::$user['id'] . "'");
		self::storeUserData();

		return true;
	}

	public function delete( $id )
	{
		if( Db::check( self::$table , "id='" . $id ."'") == true) {
			Db::delete( self::$table , "id= '" . $id . "'");
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto użytkownika");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
			return false;
		}
	}

	public function activate ( $id = null, $as_token = false )
	{
		if($as_token == true) {
			global $request, $app_url, $app_path, $config;
			$token = $id;

			$u = Db::query( "SELECT id,login,email,user_type,user_account,first_name,last_name FROM " . self::$table . " WHERE '" . $token . "' = MD5(CONCAT(login,'|',email,'|',user_type,'|',user_account))");
			if(!empty($u)) {
				$Result = $this->activate( $u[0]['id'] );
				if($Result == true) {
					Mail::$address = $u[0]['email'];
					Mail::$name = $u[0]['first_name'] . " " . $u[0]['last_name'];
					Mail::$subject = "Aktywacja konta zakończona";
					$text = Emails::getByName("user-activated-account");
					$text = str_replace("[name]" , $u[0]['first_name'] . " " . $u[0]['last_name'], $text);
					$text = str_replace("[login-url]" , $app_url . "panel/login", $text);
					$email_template = file_get_contents( $app_path . "include/email_templates/newsletter/template.html");
					$msg = str_replace("[app-url]" , $app_url, $email_template);
					$msg = str_replace("[sender-name]" , $config['service_meta_title'], $msg);
					$msg = str_replace("[subject]" , Mail::$subject, $msg);
					$msg = str_replace("[content]" , html_entity_decode($text), $msg);

					Mail::$text = $msg;
					Mail::send();
					return true;
				}
			}
			return false;
		} else {
			if( Db::check( self::$table , "id='" . $id ."'") == true) {
				Db::update( self::$table , "status='TRUE'" , "id= '" . $id . "'");
				Kernel::setMessage("NOTICE" , "Pomyślnie aktywowano użytkownika");
				return true;
			} else {
				self::$Error = Db::error();
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
				return false;
			}
		}
	}

	public function deactivate ( $id )
	{
		if( Db::check( self::$table , "id='" . $id ."'") == true) {
			Db::update( self::$table , "status='FALSE'" , "id= '" . $id . "'");
			Kernel::setMessage("NOTICE" , "Pomyślnie deaktywowano użytkownika");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
			return false;
		}
	}

	public function adminChangePassword( $own = true )
	{
		global $request, $config, $app_path, $app_url;
		if(empty(self::$admin)) {
			die("Not logged");
		}

		// Change password to account acctually logged
		if( $own == true ) {
			$login = self::$admin['login'];
			$id = self::$admin['id'];
			$email = self::$admin['email'];
			$name = self::$admin['name'];
		} else {
			$u = $this->get($request->get['id']);
			if(!empty($u)) {
				$login = $u['login'];
				$id = $u['id'];
				$email = $u['email'];
				$name = $u['name'];
			}
		}

		if($request->post['new_pass'] != $request->post['new_pass_repeat']) {
			self::$Error[] = "Podane hasła różnią się od siebie";
			return false;
		}

		$password = $this->hashPassword( $login, $request->post['new_pass'] );
		$Result = Db::update( self::$table , "pass = '" . $password . "'" , "id='" . $id . "'");
		if($Result !== true) {
			self::$Error[] = Db::$Error;
		}
		Mail::$address = $email;
		Mail::$name = $name;
		Mail::$subject = "Zmiana hasła logowania do konta";
		$text = Emails::getByName("user-password-change");
		$text = str_replace("[login]" , $login, $text);
		$text = str_replace("[email]" , $email, $text);
		$text = str_replace("[password]" , $request->post['new_pass'], $text);

		$email_template = file_get_contents( $app_path . "include/email_templates/newsletter/template.html");
		$msg = str_replace("[app-url]" , $app_url, $email_template);
		$msg = str_replace("[sender-name]" , $config['service_meta_title'], $msg);
		$msg = str_replace("[subject]" , Mail::$subject, $msg);
		$msg = str_replace("[content]" , html_entity_decode($text), $msg);

		Mail::$text = $msg;
		$Result = Mail::send();

		if($Result == true) {
			return true;
		} else {
			self::$Error[] = "Wystąpił błąd podczas wysyłania powiadomienia o zmianie hasła.<br/>" .Mail::$error;
			return false;
		}
	}

	public function adminAccessSave( $id )
	{
		global $request;
		if(!empty($request->post['element'])) {
			$elem = $request->post['element'];
			$elem = implode(";" , $elem);
			$elem = $elem . ";";

			$result = Db::update(self::$table, "access='" . $elem . "'"  , "id='".$id."'");
			if(!empty($result)) {
				return true;
			} else {
				return false;
			}
		} else {
			$result = Db::update(self::$table, "access=NULL"  , "id='".$id."'");
		}
		return true;
	}

	public function userChangePassword()
	{
		global $request, $app_url, $app_path, $config;
		if(empty(self::$user)) {
			die("Not logged");
		}

		$login = self::$user['login'];
		$id = self::$user['id'];
		$email = self::$user['email'];
		$name = self::$user['first_name'] . ' ' . self::$user['last_name'];

		if($request->post['new_pass'] != $request->post['new_pass_verify']) {
			self::$Error[] = "Podane hasła różnią się od siebie";
			return false;
		}

		$password = $this->hashPassword( $login, $request->post['new_pass'] );

		Mail::$address = $email;
		Mail::$name = $name;
		Mail::$subject = "Zmiana hasła logowania do konta";
		$text = Emails::getByName("user-password-change");
		$text = str_replace("[login]" , $login, $text);
		$text = str_replace("[email]" , $email, $text);
		$text = str_replace("[password]" , $request->post['new_pass'], $text);

		$email_template = file_get_contents( $app_path . "include/email_templates/newsletter/template.html");
		$msg = str_replace("[app-url]" , $app_url, $email_template);
		$msg = str_replace("[sender-name]" , $config['service_meta_title'], $msg);
		$msg = str_replace("[subject]" , Mail::$subject, $msg);
		$msg = str_replace("[content]" , html_entity_decode($text), $msg);

		Mail::$text = $msg;

		if(Mail::send() == true) {
			$Result = Db::update( self::$table , "pass = '" . $password . "'" , "id='" . $id . "'");
			if($Result !== true) {
				self::$Error[] = Db::$Error;
				return false;
			}
			return true;
		} else {
			self::$Error[] = "Wystąpił błąd podczas wysyłania powiadomienia o zmianie hasła.<br/>" . Mail::$error;
			return false;
		}
	}
/*
|	Metoda odpowiedzialna za resetowanie hasła
| 	@param $email
*/
	public function generateNewPassword( $email )
	{
		global $app_path, $app_url, $app_path, $config, $request;

		if( !empty($config['google_recaptcha_secretkey']) ) {
			$recaptcha = new \ReCaptcha\ReCaptcha($config['google_recaptcha_secretkey']);
			$resp = $recaptcha->verify($request->post['g-recaptcha-response'], Kernel::getIp());
			if (!$resp->isSuccess()) {
				self::$Error[] = implode("" , $resp->getErrorCodes());
			}
			if(!empty(self::$Error)) {
				Kernel::setMessage("ERROR" , "Wykryto błędy w formularzu:<br/>" . implode("<br/>" , self::$Error));
				return false;
			}
		}
		if( Db::check( self::$table , "email = '" . $email . "' AND type = 'USER' AND status='TRUE'") == true ) {
			$Row = Db::row("*" , self::$table , "WHERE email='" . $email . "'");

			if(!empty($Row)) {
				$new_password = $this->hashPassword( $Row['login'] );

				Mail::$address = $email;
				Mail::$name = $Row['first_name'] . ' ' . $Row['last_name'];
				Mail::$subject = "Zmiana hasła logowania do konta";

				$text = Emails::getByName("user-password-change");
				$text = str_replace("[login]" , $Row['login'], $text);
				$text = str_replace("[email]" , $email, $text);
				$text = str_replace("[password]" , self::$new_password, $text);

				$email_template = file_get_contents( $app_path . "include/email_templates/newsletter/template.html");
				$msg = str_replace("[app-url]" , $app_url, $email_template);
				$msg = str_replace("[sender-name]" , $config['service_meta_title'], $msg);
				$msg = str_replace("[subject]" , Mail::$subject, $msg);
				$msg = str_replace("[content]" , html_entity_decode($text), $msg);

				Mail::$text = $msg;

				if( Mail::send() == true ) {
					Kernel::setMessage("NOTICE" , "Hasło zostało pomyślnie zresetowane. Sprawdź skrzynkę e-mail na którą została wysłana wiadomość z nowym hasłem do logowania");
					Db::update(self::$table , "pass='" . $new_password . "'" , "email='" . $email . "'");
					return true;
				} else {
					Kernel::setMessage("ERROR" , "Wystąpił błąd podczas wysyłki wiadomości. Spróbuj ponownie później, a w przypadku dalszych problemów zalecamy kontakt z obsługą tego serwisu. Twoje hasło nie zostało zmienione<br/>" . Mail::$error);
					return false;
				}

			}
		} else {
			Kernel::setMessage("ERROR" , "Nie udało się znaleźć konta dla podanego adresu e-mail");
			return false;
		}
	}

	private function hashPassword( $login = null, $password = null )
	{
		if(empty($login)) {
			die("Users::hashPassword - login required as first parametr");
		}
		if(empty($password)) {
			$password = Kernel::generateIdent( 5 );
			self::$new_password = $password;
		}

		global $config;
		$password = md5( $password . "|" . $login . "|" . $config['salt'] );
		return $password;
	}

	public function _upload()
	{
		if(is_array($_FILES['photo'])) {
			if($_FILES['photo']['error']==1) { self::$Error[] = "uploadowany plik przekracza dyrektywe upload_max_filesize w php.ini"; }
			if($_FILES['photo']['error']==2) { self::$Error[] = "uploadowany plik przekracza dyrektywe MAX_FILE_SIZE w formularzu HTML"; }
			if($_FILES['photo']['error']==3) { self::$Error[] = "uploadowany plik nie został poprawnie wgrany - błąd numer 3"; }
			if($_FILES['photo']['error']==4) { self::$Error[] = "brak pliku"; }
			if($_FILES['photo']['error']==6) { self::$Error[] = "brak dostępu do katalogu tymczasowego na serwerze - błąd numer 6"; }
			if($_FILES['photo']['error']==7) { self::$Error[] = "nie udało się zapisać na dysku - błąd numer 7"; }
			if($_FILES['photo']['error']==8) { self::$Error[] = "uploadowanie przerwane przez rozszerzenie - błąd numer 8 (UPLOAD_ERR_EXTENSION)"; }

			global $app_path;

			if(file_exists( $app_path . "classes/upload/class.upload.php") == true) {
				include_once $app_path . "classes/upload/class.upload.php";
			} else {
				throw new Error('Upload class not found');
			}

			$handle = new Upload( $_FILES['photo'] );
			if ($handle->uploaded)
			{
				$handle->file_new_name_body	= time();
				$handle->file_overwrite		= true;
				$handle->file_auto_rename	= false;
				$handle->jpeg_quality 		= 85;
				if($handle->image_src_x > 640) {
					$handle->image_resize		= true;
					$handle->image_x      		= 640;
					$handle->image_ratio_y		= true;
				}
				$handle->Process($app_path . "userfiles/users/");

				if ($handle->processed) {
					return $handle->file_dst_name;
				}

				self::$Error[] = "plik nie został załadowany na serwer";

			} else { self::$Error[] = "wystąpił nieoczekiwany błąd podczas uploadu"; }

		}
	}
}
