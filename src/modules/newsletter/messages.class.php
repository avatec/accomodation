<?php
/**
 * Newsletter messages class
 *
 * @package		Modules
 * @subpackage	Newsletter/Messages
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

class NewsletterMessages {

	public static $Error;
	public static $stats;
	protected static $table = "messages";
	protected static $table_outbox = "messages_outbox";

	public static $status = [
		[ "id" => "PENDING", "name" => "w kolejce" ],
		[ "id" => "SENT", "name" => "wysłany" ],
		[ "id" => "ERROR" , "name" => "błąd podczas wysyłki" ]
	];

	public static $outbox_status = [
		[ "id" => "PENDING", "name" => "oczekujące", "label" => "success" ],
		[ "id" => "FINISH" , "name" => "wysłane", "label" => "info" ],
		[ "id" => "ERROR" , "name" => "wystąpił błąd przy wysyłce", "label" => "danger" ]
	];

	public static $type = [
		[ "id" => "AUTOMATIC", "name" => "wysyłka automatyczna", "label" => "primary" ],
		[ "id" => "MANUAL" , "name" => "wysyłka ręczna", "label" => "warning" ]
	];

	public function __construct()
	{
		global $config;
		self::$table = $config['db_prefix'] . self::$table;

		if(!empty($config['db_prefix'])) {
			if( strpos( self::$table_outbox, $config['db_prefix'] ) === false) {
				self::$table_outbox = $config['db_prefix'] . self::$table_outbox;
			}
		}
	}

	public static function stats()
	{
		global $config;
		if( strpos( self::$table_outbox, $config['db_prefix'] ) === false) {
			self::$table_outbox = $config['db_prefix'] . self::$table_outbox;
		}
		$outbox = Db::exec("COUNT(id) as num"  , self::$table_outbox , "WHERE (status='PENDING' OR status='ERROR')");
		if(empty($outbox)) {
			$outbox['num'] = 0;
		}
		$sent = Db::exec("COUNT(id) as num"  , self::$table_outbox , "WHERE status='SENT'");
		if(empty($sent)) {
			$sent['num'] = 0;
		}
		self::$stats['outbox'] = (!empty($outbox['num']) ? $outbox['num'] : 0);
		self::$stats['sent'] = (!empty($sent['num']) ? $sent['num'] : 0);

		if(!empty(self::$stats)) {
			return self::$stats;
		}
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

	public static function _readOutboxStatus( $id )
	{
		if(!empty(self::$outbox_status)) {
			foreach(self::$outbox_status as $k=>$i) {
				if( $i['id'] == $id ) {
					return '<span class="label label-' . $i['label'] . '">'.$i['name'].'</span>';
				}
			}
		}
	}

	public static function _readType( $id )
	{
		if(!empty(self::$type)) {
			foreach(self::$type as $k=>$i) {
				if( $i['id'] == $id ) {
					return '<span class="label label-' . $i['label'] . '">'.$i['name'].'</span>';
				}
			}
		}
	}

	public static function _updateMsgStatus( $status, $id, $msg_id = null )
	{
		if(!is_null($msg_id)) {
			Db::update( self::$table , "status='FINISH'" , "id='" . $msg_id . "'");
		}
		Db::update( self::$table_outbox , "status='" . $status . "', sent_date = NOW()" , "id='" . $id . "'");
		return true;
	}

	public static function _setMsgToReaded( $id, $email )
	{
		Db::update( self::$table_outbox , "readed='TRUE'" , "id='" . $id . "' AND email='" . $email . "'");
		return true;
	}

	public function get( $id = null )
	{
		if(is_null($id)) {
			return Db::exec("*" , self::$table, "ORDER BY create_date DESC");
		} else {
			return Db::row("*" , self::$table , "WHERE id='" . $id . "'");
		}
	}

	public function getOutbox( $limit = null )
	{
		return Db::exec("*" , self::$table_outbox , "WHERE status != 'SENT'" . (isset($limit) ? " LIMIT 0," . $limit : ""));
	}

	public function getAutomatic()
	{
		return Db::exec("*" , self::$table, "WHERE send_date <= NOW() AND type='AUTOMATIC' AND status='PENDING'");
	}

	public function getSended()
	{
		return Db::exec("*" , self::$table_outbox , "WHERE status = 'SENT'");
	}

	public function getMessage( $id, $subject = false )
	{
		$Row = Db::row("subject,text" , self::$table , "WHERE id='" . $id . "' ");
		if( $subject == true ) {
			return $Row['subject'];
		} else {
			if(!empty($Row['text'])) {
				return $Row['text'];
			}
		}
	}

	private function verify()
	{
		global $request;

		if(empty($request->post['subject'])) {
			self::$Error[] = "podaj tytuł wiadomości";
		}

		if(empty($request->post['text'])) {
			self::$Error[] = "wprowadź treść wiadomości";
		}
	}

	public function add()
	{
		global $request;

		$this->verify();
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Wystąpiły błędy w formularzu<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		$result = Db::insert( self::$table , "null,
		" . (empty($request->post['send_date']) ? "NOW()" : "'" . $request->post['send_date'] . "'") . ",
		'" . $request->post['subject'] . "',
		'" . $request->post['text'] . "',
		'MANUAL',
		'PENDING',
		NOW(),
		NULL,
		'" . User::$admin['id'] . "'");

		if( $result == true ) {
			Kernel::setMessage("NOTICE" , "Pomyślnie utworzono nowy szablon wiadomości");
			return true;
		} else {
			self::$Error = Db::error();
			if(!empty(self::$Error)) {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas zapisywania danych w bazie<br/>" . self::$Error);
				return false;
			}
		}
	}

	public function save( $id )
	{
		$this->verify();
		if(!empty(self::$Error)) {
			Kernel::setMessages("ERROR" , "Wystąpiły błędy w formularzu<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		$result = Db::update( self::$table , "send_date = " . (empty($request->post['send_date']) ? "NOW()" : "'" . $request->post['send_date'] . "'") . ",
		subject = '" . $request->post['subject'] . "',
		text = '" . $request->post['text'] . "',
		type = '" . $request->post['type'] . "',
		last_edit_date = NOW()", "id='" . $id . "'");

		if( $result == true ) {
			Kernel::setMessage("NOTICE" , "Pomyślnie utworzono nowy szablon wiadomości");
			return true;
		} else {
			self::$Error = Db::error();
			if(!empty(self::$Error)) {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas zapisywania danych w bazie<br/>" . self::$Error);
				return false;
			}
		}
	}

	public function delete( $id )
	{
		if( Db::check( self::$table , "id='" . $id ."'") == true) {
			Db::delete( self::$table , "id= '" . $id . "'");
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto wybraną wiadomość");
			return true;
		} else {
			Kernel::setMessage("ERROR" , "Wybrana wiadomość najprawdopodobniej już nie istnieje");
			return false;
		}
	}

	public $cron_msg_id;
	public function cronAddMessage( $data, $email )
	{
		$result = Db::insert( self::$table , "null,
		NOW(),
		'" . $data['subject'] . "',
		'" . $data['text'] . "',
		'AUTOMATIC',
		'PENDING',
		NOW(),
		NULL,
		'" . (!empty($data['uid']) ? $data['uid'] : '0') . "'");

		if( $result == true ) {
			$this->cron_msg_id = Db::last_id(self::$table , "id");
			$this->send( $this->cron_msg_id, $email );
			return true;
		} else {
			die("Error");
			return false;
		}
	}

	public static function isAutomatic( $msg_id )
	{
		$result = Db::check(self::$table, "id='" . $msg_id . "' AND type='AUTOMATIC'");
		if($result == true) {
			return true;
		} else {
			return false;
		}
	}

	public function send( $msg_id, $email = null )
	{
		global $request;

		$Emails = [];

		if(!empty($request->post['type'])) {
			if(in_array("OWNER" , $request->post['type']) == true) {
				$owner_emails = User::getEmailsByType('OWNER');
				if(!empty( $owner_emails )) {
					$Emails = array_merge( $owner_emails , $Emails );
				}
			}
			if(in_array("USER" , $request->post['type']) == true) {
				$user_emails = User::getEmailsByType('USER');
				if(!empty($user_emails)) {
					$Emails = array_merge($user_emails , $Emails);
				}
			}
			if(in_array("NEWSLETTER" , $request->post['type']) == true) {
				$newsletter_emails = Newsletter::getEmails();
				if(!empty( $newsletter_emails )) {
					$Emails = array_merge( $newsletter_emails, $Emails );
				}
			}
		} else {
			if(!empty($request->post['test_email'])) {
				$Emails[] = ['email' => $request->post['email']];
			}
			if(!empty($email)) {
				$Emails[] = array( 'email' => $email );
			}
		}


		if(!empty($Emails)) {
			foreach( $Emails as $i ) {

				$result = Db::insert( self::$table_outbox , "null,
				'" . $msg_id . "',
				'" . $i['email'] . "',
				'PENDING',
				NOW(),
				" . (!empty($request->post['send_date']) ? "'" . $request->post['send_date'] . "'" : "NOW()") . ",
				'FALSE'");

				if($result == false) {
					self::$Error = Db::error();
					if(!empty(self::$Error)) {
						Kernel::setMessage("ERROR" , "Wystąpił błąd podczas zapisywania danych w bazie dla adresu " . $i['email'] . "<br/>" . self::$Error);
					}
				}
			}

			if( $result == true ) {
				Kernel::setMessage("NOTICE" , "Wiadomość została przekazana do wysyłki");
				return true;
			}
		}
	}
}

?>
