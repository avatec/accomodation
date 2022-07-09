<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail {

	public static $address, $name;
	public static $subject;
	public static $text;
	public static $reply_to;
	public static $bcc;
	public static $error;
	public static $attachment;

	public static function send()
	{
		global $app_path, $config;

		$m = new Phpmailer();
		// Debug
		//$m->SMTPDebug = 3;
		//$m->Debugoutput = "html";
		$m->CharSet = "UTF-8";
		$m->SetLanguage("pl", $app_path . "classes/phpmailer/language/");
		$m->AddReplyTo( (!empty(self::$reply_to) ? self::$reply_to : $config['smtp_email']) );
		$m->From = $config['smtp_email'];
		$m->FromName = $config['smtp_from'];

		if($config['smtp']=="TRUE") {
			$m->Host = $config['smtp_host'];
			$m->Port = $config['smtp_port'];

			$m->IsSMTP();
			$m->Username = $config['smtp_username'];
			$m->Password = $config['smtp_password'];
			if($config['smtp_auth'] == "TRUE") {
				$m->SMTPAuth = true;
			} else {
				$m->SMTPAuth = false;
			}
			if($config['smtp_ssl'] == "TRUE") {
				$m->SMTPSecure = "ssl";
			}
		}

		if(!empty(self::$attachment)) {
			foreach(self::$attachment as $item) {
				$m->AddAttachment($item[0], $item[1]);
			}
		}

		$m->SMTPOptions = array(
		    'ssl' => array(
		        'verify_peer' => false,
		        'verify_peer_name' => false,
		        'allow_self_signed' => true
		    )
		);

		$m->CharSet = "UTF-8";
		$m->AddReplyTo( (!empty(self::$reply_to) ? self::$reply_to : $config['smtp_email']) );
		$m->AddAddress( self::$address, self::$address );
		if(!empty(self::$bcc)) {
			$m->AddBCC( self::$bcc, self::$bcc);
		}
		$m->Subject = self::$subject;

		if($config['smtp_html']=="TRUE") {
			$m->AltBody = "Aby obejrzeć tą wiadomość użyj klienta poczty e-mail obsługującego format HTML";
			$m->MsgHTML(self::$text);
		} else {
			$m->Body = self::$text;
		}

		$result = $m->send();
		if(!empty($m->ErrorInfo)) {
			self::$error = $m->ErrorInfo;
		}
		return $result;

	}

	public static function attachment( $file )
	{
		if( $file['error'] == 0 ) {
			self::$attachment[] = array($file['tmp_name'], $file['name']);
		} else {
			self::$error[] = "nie udało się załączyć pliku do wiadomości " . $file['name'];
		}
	}

	public static function attachment_path( $file )
	{
		if(!empty($file)) {
			self::$attachment[] = array($file, $file);
		}
	}
}
