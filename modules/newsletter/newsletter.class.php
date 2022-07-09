<?php
use \Core\Backend\Navigation as Navigation;

/**
 * Newsletter class
 *
 * @package		Modules
 * @subpackage	Newsletter
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

class Newsletter {

	protected static $log = "newsletter.log";
	protected static $table = "newsletter_emails";
	public static $Error;

	protected static $messages = [
		"added" => "include/email_templates/newsletter/added.html",
		"confirm" => "include/email_templates/newsletter/confirm.html",
		"delete" => "include/email_templates/newsletter/delete.html"
	];

	public static $frequency = [
		[ "id" => "0" , "name" => "brak wysyłki" ],
		[ "id" => "1" , "name" => "wysyłka raz dziennie ofert z ostatnich 24 godzin" ],
		[ "id" => "2" , "name" => "wysyłka ofert z ostatnich 3 dni" ],
		[ "id" => "3" , "name" => "wysyłka ofert z ostatnich 7 dni" ],
		[ "id" => "4" , "name" => "wysyłka ofert z ostatnich 14 dni" ]
	];

	public static $status = [
		[ "id" => "PENDING", "name" => "niepotwierdzony" ],
		[ "id" => "CONFIRM", "name" => "potwierdzony" ]
	];

	public static $source = [
		[ "id" => "import", "name" => "zaimportowane" ],
		[ "id" => "added", "name" => "dodane z formularza" ]
	];

	public static $popup = [
		[ "id" => "TRUE", "name" => "wyświetlaj" ],
		[ "id" => "FALSE", "name" => "nie wyświetlaj" ]
	];

	public function __construct()
	{
		global $config, $route;
		self::$table = $config['db_prefix'] . self::$table;

		//TODO: Dodać pobieranie wiadomości ze skrzynki nadawczej i wiadomości wysłanych
		$stats = NewsletterMessages::stats();

		Navigation::menu( 13, 'newsletter' , 'Newsletter', null, 'fa-envelope');
		Navigation::submenu('newsletter' , 'Ustawienia', "system/config/newsletter/");
		Navigation::submenu('newsletter' , 'Stwórz mailing', "newsletter/messages/add/");
		Navigation::submenu('newsletter' , 'Przeglądaj subskybentów', "newsletter/list");
		Navigation::submenu('newsletter' , 'Szablony wiadomości', "newsletter/messages/list/");
		Navigation::submenu('newsletter' , 'Skrzynka nadawcza <span class="badge badge-info">' . $stats['outbox'] . '</span>', "newsletter/messages/outbox/");
		Navigation::submenu('newsletter' , 'Wysłane <span class="badge badge-info">' . $stats['sent'] . '</span>', "newsletter/messages/sended/");


		//Kernel::addAdminMenu("newsletter", "Newsletter", "#", "fa-envelope", null, false);
			//Kernel::addAdminMenu("newsletter", "Ustawienia", "admin/system/config/newsletter/", null, true);
			//Kernel::addAdminMenu("newsletter", "stwórz mailing", "admin/newsletter/messages/add/", null, true);
			//Kernel::addAdminMenu("newsletter", "przeglądaj subskrybentów", "admin/newsletter/list/", null, true);
			//Kernel::addAdminMenu("newsletter", "importuj subskrybentów", "admin/newsletter/import/", null, true);
			//Kernel::addAdminMenu("newsletter", "eksportuj subskrybentów", "admin/newsletter/export/", null, true);
			//Kernel::addAdminMenu("newsletter", "szablony maili", "admin/newsletter/messages/list/", null, true);
			//Kernel::addAdminMenu("newsletter", "skrzynka nadawcza <span class=\"badge badge-info\">" . $stats['outbox'] . "</span>", "admin/newsletter/messages/outbox/", null, true);
			//Kernel::addAdminMenu("newsletter", "wysłane <span class=\"badge badge-info\">" . $stats['sent'] . "</span>", "admin/newsletter/messages/sended/", null, true);

		$this->register( $route );
	}

	protected function register( $route )
	{
		$route->get('(newsletter)\/:string\/:string', [
			'module' => 'newsletter', 'file' => 'newsletter', 'command' => '$2', 'id' => '$3'
		]);
	}

	public static function stats()
	{
		return [
			'today' => Db::count( self::$table , "create_date = CURDATE()"),
			'active' => Db::count( self::$table , "status='CONFIRM'"),
			'all' => Db::count( self::$table )
		];
	}

	public static function _readStatus( $id )
	{
		if(!empty(self::$status)) {
			foreach(self::$status as $k=>$i) {
				if( $i['id'] == $id ) {
					return $i['name'];
				}
			}
		}
	}

	private $query;
	private function search()
	{
		global $request;

		if(!empty($request->get['email'])) {
			$this->query[] = "email='" . $request->get['email'] . "'";
		}

		if(!empty($request->get['status'])) {
			$this->query[] = "status='" . $request->get['status'] . "'";
		}
	}

	public function get( $id = null, $edit = false )
	{
		if(is_null($id)) {
			$this->search();

			Paginate::$query = "SELECT * FROM " . self::$table . " " . (!empty($this->query) ? "WHERE " . implode(" AND " , $this->query) : "") . " ORDER BY id DESC";
			Paginate::$perpage = 25;
			return Paginate::make();
			//return Db::exec("*" , self::$table , "ORDER BY create_date DESC");
		} else {
			$Row = Db::row("*" , self::$table , "WHERE id='" . $id . "'");
			if($edit == true) {
				$Row['edit'] = true;
			}
			return $Row;
		}
	}

	public function verify( $admin = true )
	{
		global $request;
		if( $admin == true ) {

		} else {
			if( Db::check( self::$table , "email='" . $request->post['email'] . "'") == true) {
				self::$Error[] = "wybrany adres e-mail już istnieje istnieje";
			}
		}
	}

	public function add( $admin = true )
	{
		global $request;

		$this->verify($admin);
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Wystąpił błąd w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		if( $admin == true ) {
			$city = (!empty($request->post['cities']) ? json_encode( $request->post['cities'] ) : NULL);
			$category = (!empty($request->post['category']) ? json_encode( $request->post['category'] ) : NULL);

			$status = $request->post['status'];
		} else {
			$status = "PENDING";
		}

		$result = Db::insert( self::$table , "null,
		'" . $request->post['email'] . "',
		'" . $status . "',
		NULL,
		'" . Kernel::getIp() . "',
		'" . $request->post['source'] . "',
		NOW()");

		if( $result == true ) {
			if(!empty($city)) {
				GrupoweCities::setToDefault( $city );
			}
			return self::sendConfirmEmail( $request->post['email'] );
		} else {
			$error = Db::error();
			if(!empty($error)) {
				Kernel::setMessage("ERROR" , "Wystąpił błąd w zapytaniu SQL:<br/>" . $error);
			}
			return false;
		}

	}

	public static function quickAdd($email, $ifExistsSilent = false)
	{
		global $request;

		if( Db::check( self::$table , "email='" . $request->post['email'] . "'") == true) {
			if($ifExistsSilent == false) {
				self::$Error[] = "wybrany adres e-mail już istnieje istnieje";
			} else {
				return true;
			}
		}

		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Wystąpił błąd w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		$result = Db::insert( self::$table , "null,
		'" . $email . "',
		'PENDING',
		NULL,
		'" . Kernel::getIp() . "'," .
		(!empty($request->post['source']) ? "'" . $request->post['source'] . "'" : "'added'") . ",
		NOW()");

		if( $result == true ) {
			return self::sendConfirmEmail( $email );
		} else {
			$error = Db::error();
			if(!empty($error)) {
				Kernel::setMessage("ERROR" , "Wystąpił błąd w zapytaniu SQL:<br/>" . $error);
			}
			return false;
		}
	}

	public function save( $id, $admin = true )
	{
		global $request;

		$this->verify($admin);
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Wystąpił błąd w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		$result = Db::update(self::$table, "email = '" . $request->post['email'] . "',
		status = '" . $request->post['status'] . "',
		source = '" . $request->post['source'] . "'" , "id='" . $id . "'");

		if( $result == true ) {
			Kernel::setMessage("NOTICE" , "Pomyślnie zapisano zmiany");
			return true;
		} else {
			$error = Db::error();
			if(!empty($error)) {
				Kernel::setMessage("ERROR" , "Wystąpił błąd w zapytaniu SQL:<br/>" . $error);
			}
			return false;
		}
	}

	public function delete( $id )
	{
		if( Db::check( self::$table , "id='" . $id ."'") == true) {
			Db::delete( self::$table , "id= '" . $id . "'");
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto wybrany adres e-mail z subskrypcji");
			return true;
		} else {
			Kernel::setMessage("ERROR" , "Wybrany adres e-mail najprawdopodobniej już nie istnieje");
			return false;
		}
	}

	public function import()
	{

	}

	public function export( $status = null )
	{
		global $app_path;

		if(!empty( $status )) {
			$query = "status = '" . $status . "'";
		}
		$Rows = Db::exec("*" , self::$table , (!empty($query) ? "WHERE " . $query : ""));
		if(!empty($Rows)) {
			$csv[] = '"email","status";';
			foreach( $Rows as $k=>$i ) {
				$csv[] = '"' . $i['email'] . '","'.$i['status'].'"';
			}
		}

		if(!empty($csv)) {
			file_put_contents( $app_path . "cache/newsletter_exported.csv" , implode(";".PHP_EOL, $csv) . ";" . PHP_EOL );

			header('Content-Type: application/download');
		    header('Content-Disposition: attachment; filename="newsletter_exported.csv"');
		    header("Content-Length: " . filesize($app_path . "cache/newsletter_exported.csv"));

		    $fp = fopen($app_path . "cache/newsletter_exported.csv", "r");
		    fpassthru($fp);
		    fclose($fp);
			// TODO: Zrobić wysyłkę eksportu do przeglądarki
		}
	}

	public function activate( $email )
	{
		if( Db::check( self::$table , "md5(email) = '" . $email . "' AND status = 'PENDING'") == true ) {
			Db::update( self::$table , "status='CONFIRM', confirm_date = NOW(), confirm_ip = '" . Kernel::getIp() . "'" , "md5(email) = '" . $email . "'");
			return true;
		} else {
			return false;
		}
	}

	public function unsubscribe( $email )
	{
		if( Db::check( self::$table , "md5(email) = '" . $email . "'") == true ) {
			$Result = Db::row("*" , self::$table , "WHERE md5(email) = '" . $email . "'");
			Db::delete( self::$table , "md5(email) = '" . $email . "'");
			self::sendDeleteEmail( $Result['email'] );
			return true;
		} else {
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
				'[content]'	=> (!empty($data['content']) ? $data['content'] : ""),
				'[activate-link]' 	=> $app_url . "newsletter/activate/" . md5( $email ),
				'[delete-link]' 	=> $app_url . "newsletter/unsubscribe/" . md5( $email ),
			];

			return strtr($content, $replacements);
		} catch (Exception $e) {
			Kernel::setMessage("ERROR" , 'Caught exception in newsletter.class.php@parseEmailTemplate:<br/>' . $e->getMessage());
			return false;
		}

	}

	public static function sendConfirmEmail( $email )
	{
		global $app_path;

		if( file_exists( $app_path . self::$messages['added'] ) == true ) {
			$msg = file_get_contents( $app_path . self::$messages['added'] );
			$msg = self::parseEmailTemplate( $msg, $email, [
				'subject' => 'Czy ten adres e-mail jest Twój',
				'content' => '<p>Witaj,<br/><br/>Otrzymujesz tą wiadomość, ponieważ zapisałeś(aś) się do newslettera w naszym serwisie.<br/>Prosimy o kliknięcie na poniższy link w celu potwierdzenia subskrypcji:<br/><a href="[activate-link]">[activate-link]</a></p>'
			]);
			return self::sendMessage( $email, "Prośba o potwierdzenie adresu e-mail" , $msg);
		} else {
			Kernel::createLog( self::$log , "[ERROR] " . self::$messages['added'] . " file not found [newsletter.class.php@sendConfirmEmail" );
			return false;
		}
	}

	public static function sendDeleteEmail( $email )
	{
		global $app_path;

		if( file_exists( $app_path . self::$messages['delete'] ) == true ) {
			$msg = file_get_contents( $app_path . self::$messages['delete'] );
			$msg = self::parseEmailTemplate( $msg, $email, [
				'subject' => 'Twój adres e-mail został usunięty',
				'content' => '<p>Witaj,<br/><br/>Twój adres e-mail został pomyślnie usunięty z naszej bazy danych</p>'
			]);
			return self::sendMessage( $email, "Rezygnacja z subskrypcji newslettera" , $msg);
		} else {
			Kernel::createLog( self::$log , "[ERROR] " . self::$messages['delete'] . " file not found [newsletter.class.php@sendDeleteEmail" );
			return false;
		}
	}

	public static function getEmails()
	{
		global $request;

		return Db::exec("email" , self::$table , "WHERE status='CONFIRM' ");
	}

	protected static function sendMessage( $email, $subject, $content )
	{
		global $app_url;
		try {
			$replacements = [
				'[activate-link]' 	=> $app_url . "newsletter/activate/" . md5( $email ),
				'[delete-link]' 	=> $app_url . "newsletter/unsubscribe/" . md5( $email )
			];

			$content = strtr($content, $replacements);

			Mail::$address = $email;
			Mail::$name = "Subskrybent newslettera";
			Mail::$subject = $subject;
			Mail::$text = $content;

			$result = Mail::send();
			if( $result == true ) {
				Kernel::setMessage("NOTICE" , "Powiadomienie zostało nadane na adres " . $email);
				return true;
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas wysyłania powiadomienia<br/>" . Mail::$error);
				return false;
			}
		} catch (Exception $e) {
			Kernel::setMessage("ERROR" , 'Caught exception in newsletter.class.php@sendMessage:<br/>' . $e->getMessage());
			return false;
		}
	}
}
