<?php
/**
 * System Stats class
 *
 * @package		Modules
 * @subpackage	System/Stats
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


class Stats {
	protected static $data;

	public static function get( $filter = null )
	{
        global $config;

		self::$data = array(
			'objects' => Objects::stats(),
			'users' => User::stats(),
			'newsletter' => (!isset($config['basic']) ? Newsletter::stats() : null),
			'booking' => (!empty($config['exclusive']) ? Booking::getOwnerStats() : null)
		);
		if(is_null($filter)) {
			return self::$data;
		} else {
			$item = explode(";" , $filter);
			foreach($item as $value) {
				$b = explode("/" , $value);

				$module = strtolower($b[0]);
				$action = strtolower($b[1]);

				$result[$module] = $action;
			}

			return self::$data[$module][$action];
		}

	}
}
