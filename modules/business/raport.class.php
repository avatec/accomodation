<?php
use \Core\Backend\Navigation as Navigation;

/**
 * Business Invoice Report class
 *
 * @package		Modules
 * @subpackage	Business/Invoice/Raport
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

class BusinessInvoiceRaport {

	public function __construct()
	{
		global $config;

		//Kernel::addAdminMenu("business", "Raporty", "admin/business/raport/list/", null, "business", false);
		Navigation::submenu('business' , 'Raporty' , 'business/raport/list/');
	}

	public function get()
	{
		global $request;

		if(empty($request->get['date_start'])) {
			$date_start = date('Y-m-01');
		} else {
			$date_start = $request->get['date_start'];
		}
		if(empty($request->get['date_end'])) {
			$date_end = date('Y-m-t');
		} else {
			$date_end = $request->get['date_end'];
		}

		return self::byDates( $date_start, $date_end );
	}

	public static function byDates( $date_start, $date_end )
	{
		return BusinessInvoice::getTaxesByDates( $date_start, $date_end );
	}

	public static function monthly( $month = null, $year = null )
	{
		if(is_null($month)) {
			$month = date('m');
		}
		if(is_null($year)) {
			$year = date('Y');
		}

		BusinessInvoice::getTaxes( $month, $year );
	}
}
