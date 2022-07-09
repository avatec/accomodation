<?php
/**
 * Request class parses POST, GET, REQUEST, COOKIE, FILES and SERVER
 *
 * @package		Classes
 * @subpackage	Request
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

class Request {
/**
 *	Handling $_GET, $_POST, $_COOKIE, $_FILES, $SERVER
 * 	@var $get|$post|$cookie|$files|$sever array
 */
	public $get, $post, $cookie, $files, $server = [];

  	public function __construct()
  	{
		$_GET = $this->clean($_GET);
		$_POST = $this->clean($_POST);
		$_REQUEST = $this->clean($_REQUEST);
		$_COOKIE = $this->clean($_COOKIE);
		$_FILES = $this->clean($_FILES);
		$_SERVER = $this->clean($_SERVER);

		$this->get = $_GET;
		$this->post = $_POST;
		$this->any = $_REQUEST;
		$this->cookie = $_COOKIE;
		$this->files = $_FILES;
		$this->server = $_SERVER;
	}

  	protected function clean($data)
  	{
    	if (is_array($data)) {
	  		foreach ($data as $key => $value) {
				unset($data[$key]);

	    		$data[$this->clean($key)] = $this->clean($value);
	  		}
		} else {
	  		$data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
		}

		return $data;
	}
}
