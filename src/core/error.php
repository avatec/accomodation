<?php namespace Core;
/**
 * CMS Error handling class
 *
 * @package		Classes
 * @subpackage	self
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

class Error {

	public static $errors = [];

	public static function CustomErrorHandler($errno, $errstr, $errfile, $errline)
	{
	    if (!(error_reporting() & $errno)) {
	        return;
	    }

	    switch ($errno) {
		    case E_ERROR:
		        echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
		        echo "  Fatal error on line $errline in file $errfile";
		        echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
		        echo "Aborting...<br />\n";
		        exit(1);
		        break;

		    case E_WARNING:
		        self::show("WARNING - [$errno] $errstr" , "on line $errline in file $errfile<br />\n");
		        exit(1);
		        break;

		    case E_NOTICE:
		    	self::show("NOTICE [$errno] $errstr" , "Error on line $errline in file $errfile<br/>\n");
		    	exit(1);
		        break;

		    case E_USER_WARNING:
		    	self::show("FRAMEWORK WARNING [#$errno]" , "<b>" . $errstr . "</b><br/>Error on line $errline in file $errfile<br/>\n");
		    	exit(1);
		        break;

		    /**
			default:
	    		self::show("Unknown error type" , "<b>Error #$errno</b><br/><br/>$errstr<br /><br/><em>Found in file " . $errfile . " on line " . $errline . "</em>\n");
				exit(1);
	        	break;
			**/
	    }
	    return true;
	}

	public static function show( $title, $text )
	{
		@ob_end_clean();
		echo '<html><head><meta charset="utf-8"><title>Error occured</title>';
		echo '<style type="text/css">';
		echo 'html, body { margin: 0; padding: 0; font-family: Sans-serif; width: 100%; height: 100%; background: #efefef;color: #232323; text-align: center; }';
		echo '.main { position: relative; top: 0; left: 0; z-index: 9999;width: 768px; min-height: 100px; padding: 10px; border: 1px solid #cccccc; margin: 5% auto 0 auto; background: #ffffff; color: #222222; word-break: break-all; font-size: 14pt; font-weight: 300; line-height: 150%; }';
		echo '.main h1 { font-size: 1.3em; font-weight: 300; color: #ffffff; background: #a10000; padding-top: 0; margin-top: 0; text-align: center; padding: 20px 10px; }';
		echo '</style>';
		echo '</head><body>' . PHP_EOL;
		echo '<div class="main">' . PHP_EOL . '<h1>'.$title.'</h1>' . PHP_EOL . '<p>'.$text.'</p></div><br/><br/>' . PHP_EOL;
		echo '<p align="center"><small>Avatec Framework - Copyright &copy; 2016-' . date('Y') . ' Avatec.pl</small></p>';
		echo '</body></html>';
		exit;
	}

	public static function set( $text )
	{
		self::$errors[] = $text;
	}

	public static function setInput( $input, $text )
	{
		self::$errors[$input] = $text;
	}

	public static function get()
	{
		if(!empty(self::$errors)) {
			return self::$errors;
		} else {
			return false;
		}
	}
}
