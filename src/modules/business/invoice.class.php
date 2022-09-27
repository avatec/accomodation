<?php
use \Core\Backend\Navigation as Navigation;

/**
 * Business Invoice class
 *
 * @package		Modules
 * @subpackage	Business/Invoice
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

class BusinessInvoice {

	public static $Error;
	public static $Empty = array( array("id"=>0,"name"=>"Brak danych"));
	protected static $table = "business_invoice";

	public static $vat = array(
		array("id" => "23", "name" => "23%"),
		array("id" => "8", "name" => "8%"),
		array("id" => "5", "name" => "5%"),
		array("id" => "0", "name" => "0%")
	);

	public static $type = array(
		array("id" => "szt", "name" => "sztuka"),
		array("id" => "kpl", "name" => "komplet"),
		array("id" => "zes", "name" => "zestaw"),
		array("id" => "mb", "name" => "metr b."),
		array("id" => "m2", "name" => "metr kw."),
		array("id" => "g", "name" => "gram"),
		array("id" => "dk", "name" => "dekogram"),
		array("id" => "kg", "name" => "kilogram"),
		array("id" => "t", "name" => "tona"),
		array("id" => "ml", "name" => "mililitr"),
		array("id" => "l", "name" => "litr")
	);

	public function __construct()
	{
		global $config;

		Navigation::submenu('business' , 'Faktury VAT' , 'business/invoice/list/');
		//Kernel::addAdminMenu("business", "Faktury VAT", "admin/business/invoice/list/", null, "business", false);

		self::$table = $config['db_prefix'] . self::$table;
	}

	public static function countInvoicesByContrahent( $contrahent_id )
	{
		$Result = Db::query( "SELECT COUNT(id) as count FROM " . self::$table . " WHERE contrahent_id='" . $contrahent_id . "'");
		return $Result['0']['count'];
	}

	public static function getNewInvoiceNumber()
	{
		$Result = Db::row("invoice_number" , self::$table , "ORDER BY id DESC");
		if(!empty($Result)) {
			$Exploded = explode("/" , $Result['invoice_number']);
			if($Exploded[1] !== date('Y')) {
				$number = 1;
				$year = date('Y');
			} else {
				$number = $Exploded[0]+1;
				$year = $Exploded[1];
			}
		} else {
			$number = 1;
			$year = date('Y');
		}
		$Result = $number . "/" . $year;
		return $Result;
	}

	public static function getTaxesByDates( $date_start, $date_end )
	{
		$stats = array(
			"amount_netto" => 0,
			"amount_vat" => 0,
			"amount_tax" => 0,
			"amount_income" => 0,
			"amount_netto_notpay" => 0,
			"amount_vat_notpay" => 0,
			"amount_tax_notpay" => 0,
			"amount_income_notpay" => 0
		);

		$Data = Db::exec("*" , self::$table , "WHERE create_date BETWEEN '" . $date_start . "' AND '" . $date_end . "'");
		if(!empty($Data)) {
			foreach($Data as $i) {
				$json = json_decode($i['items'], true);
				if($i['payment'] == "TRUE") {
					if(!empty($json)) {
						foreach($json as $j) {
							$netto = $j['num'] * $j['price_netto'];
							$vat = $j['num'] * $j['price_vat'];

							$stats['amount_netto'] += $netto;
							$stats['amount_vat'] += $vat;
							$stats['amount_tax'] += ($netto * 0.18);
							$stats['amount_income'] += $netto - ($netto * 0.18);

							unset($netto);
							unset($vat);
						}
					}
				} else {
					if(!empty($json)) {
						foreach($json as $j) {
							$netto = $j['num'] * $j['price_netto'];
							$vat = $j['num'] * $j['price_vat'];

							$stats['amount_netto_notpay'] += $netto;
							$stats['amount_vat_notpay'] += $vat;
							$stats['amount_tax_notpay'] += $netto * 0.18;
							$stats['amount_income_notpay'] += $netto - ($netto * 0.18);
							unset($netto);
							unset($vat);
						}
					}
				}
			}
			return $stats;
		}
	}

	public static function _restore()
	{
		if(!empty($_SESSION['BI'])) {
			return $_SESSION['BI'];
		} else {
			return null;
		}
	}

	public static function _store( $data )
	{
		$_SESSION['BI'] = $data;
	}

	public static function _clear()
	{
		if(!empty($_SESSION['BI'])) {
			unset($_SESSION['BI']);
		}
	}

	public static function _lastNumber()
	{
		$Result = Db::row("*" , self::$table , "ORDER BY id DESC");
		if(empty($Result)) {
			return "1/" . date('Y');
		} else {
			$e = explode("/" , $Result['invoice_number']);
			$e[0]++;
			if( date('Y') > $e[1] ) {
				return "1/" . date('Y');
			}
			return $e[0] . "/" . date('Y');
		}
	}

	private static function countAmount( $id = null, $array = null )
	{
		if(is_null($array)) {
			$Result = Db::row("items" , self::$table , "WHERE id='" . $id . "'");

			if(!empty($Result)) {
				$Result = $Result['items'];
				$json = json_decode($Result, true);
			}
		} else {
			$json = $array;
			if(!is_array($json)) {
				$json = json_decode( $json, true );
			}
		}

		$amount = 0;
		if(!empty($json)) {
			foreach($json as $i) {
				$amount += $i['price_brutto_all'];
			}
		}
		return $amount;

	}

	public function get( $id = null )
	{
		global $app_path;

		if( is_null($id) ) {
			$Result = Db::exec("*" , self::$table, "ORDER BY id DESC");
			if(!empty($Result)) {
				foreach($Result as $k=>$i) {
					$year = date('Y' , strtotime($i['create_date']));
					$filename = str_replace("/" , "_" , $i['invoice_number']);
					$Result[$k]['pdf_file'] = file_exists( $app_path . "archive/" . $year . "/faktura_vat_" . $filename . ".pdf" );
					$Result[$k]['amount'] = self::countAmount( $i['id'] );
					$Result[$k]['payment_amount'] = doubleval($i['payment_amount']);
					$Result[$k]['payment_sum'] = round($Result[$k]['amount'] - $Result[$k]['payment_amount'],2);
				}
				return $Result;
			}
		} else {
			$Result = Db::row("*" , self::$table , "WHERE id='" . $id . "'");
			$Result['edit'] = true;
			self::_store( json_decode($Result['items'], true) );
			$Result['items'] = self::_restore();
			return $Result;
		}
	}

	private function verify()
	{
		global $request;

		$items = self::_restore();
		if(empty($items)) {
			self::$Error[] = "dodaj pozycje do faktury";
		}

		if(empty($request->post['create_date'])) {
			self::$Error[] = "wprowadź datę wystawienia faktury";
		}

		if(empty($request->post['contrahent_id'])) {
			self::$Error[] = "wybierz kontrahenta";
		}

		if(empty($request->post['sell_date'])) {
			self::$Error[] = "wprowadź datę sprzedaży";
		}
	}

	public function create()
	{
		global $request;
		$this->verify();
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , "Wykryto błędy w formularzu:<br/>" . implode("<br/>" , self::$Error));
			return false;
		}

		$items = self::_restore();
		$amount = (isset($request->post['payment_amount']) ? str_replace("," , "." , $request->post['payment_amount']) : self::countAmount(null, $items));
		if(!empty($items)) {
			$items = addslashes(json_encode($items));
		}

		$invoice_number = self::_lastNumber();

		$Result = Db::insert(self::$table , "null,
		'" . $request->post['contrahent_id'] . "',
		'" . $request->post['create_date'] . "',
		'" . $request->post['sell_date'] . "',
		'" . $request->post['payment_date'] . "',
		'" . $request->post['payment_label'] . "',
		'" . $request->post['place'] . "',
		'" . $items . "',
		'" . $invoice_number . "',
		'" . (empty($request->post['payment']) ? "FALSE" : "TRUE") . "',
		" . (empty($request->post['payment']) ? "NULL" : "NOW()") . ",
		" . (empty($request->post['payment']) ? "NULL" : "'" . $amount . "'") . ",
		'" . (!empty($request->post['notice']) ? $request->post['notice'] : "") . "'");

		if($Result == true) {
			self::_clear();
			Kernel::setMessage("NOTICE" , "Pomyślnie utworzono fakturę vat");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
			return false;
		}
	}

	public function save( $id )
	{
		global $request;

		$items = self::_restore();
		if(!empty($items)) {
			$items = addslashes(json_encode($items));
		}

		$amount = (!empty($request->post['payment_amount']) ? str_replace("," , "." , $request->post['payment_amount']) : self::countAmount(null, $items));

		$Result = Db::update(self::$table, "contrahent_id = '" . $request->post['contrahent_id'] . "',
		create_date = '" . $request->post['create_date'] . "',
		sell_date = '" . $request->post['sell_date'] . "',
		payment_date = '" . $request->post['payment_date'] . "',
		payment_label = '" . $request->post['payment_label'] ."',
		place = '" . $request->post['place'] ."',
		" . (!empty($items) ? "items = '" . $items . "'," : "") . "
		invoice_number = '" . $request->post['invoice_number'] . "',
		payment = '" . (!empty($request->post['payment']) ? "TRUE" : "FALSE") . "'," .
		(!empty($request->post['payment_amount']) ? "payment_amount='" . $amount ."'," : "") . "
		notice = '" . $request->post['notice'] . "'" , "id='" . $id . "'");

		if($Result == true) {
			self::_clear();
			return true;
		}
	}

	public function setPayment( $id, $amount = null )
	{
		if(empty($amount)) {
			self::$Error[] = "należy podać wpłaconą kwotę";
			return false;
		}

		$Result = Db::update(self::$table , "payment='TRUE', payment_create_date=NOW(), payment_amount='" . $amount . "'" , "id='" . $id . "'");
		if($Result == true) {
			return true;
		} else {
			self::$Error[] = Db::error();
			return false;
		}
	}

	public function changeStatus( $id, $status ) {

	}

	public static function itemAddSession()
	{
		global $request;

		if(empty($request->post['name'])) {
			$JSON['error'][] = "musisz podać nazwę";
		}
		if(empty($request->post['num'])) {
			$JSON['error'][] = "musisz podać ilość";
		}
		if(empty($request->post['num_type'])) {
			$JSON['error'][] = "musisz wybrać typ";
		}

		if(!empty( $JSON['error'])) {
			die( json_encode( $JSON['error'] ));
		}

		$data = self::_restore();
		if(!empty($data)) {
			$k = key($data) + 1;
		} else {
			$k = 0;
		}
		$data[] = array(
			"name" => $request->post['name'],
			"num" => $request->post['num'],
			"num_type" => $request->post['num_type'],
			"vat" => $request->post['vat'],
			"price_netto" => $request->post['price_netto'],
			"price_brutto" => $request->post['price_brutto'],
			"price_vat" => $request->post['price_vat'],
			"price_brutto_all" => $request->post['num'] * $request->post['price_brutto']
		);

		$data = array_values($data);
		//self::_clear();
		self::_store($data);
		return true;
	}

	public static function itemGetSession()
	{
		$Result = self::_restore();
		if(!empty($Result)) {
			return json_encode($Result);
		}
	}

	public static function itemDeleteEdited( $id, $key )
	{
		$Result = Db::row("*" , self::$table , "WHERE id='" . $id . "'");
		if(!empty($Result)) {
			$Items = json_decode($Result['items'], true);
			if(!empty($Items)) {
				foreach($Items as $k=>$i) {
					if($k == $key) {
						unset($Items[$k]);
					}
				}

				$Items = array_values($Items);

				if(count($Items) > 0) {
					Db::update(self::$table , "items='" . json_encode(addslashes($Items)) . "'" , "id='" . $id . "'");
				} else {
					Db::update(self::$table , "items='" . json_encode(array()) . "'" , "id='" . $id . "'");
				}
				return "OK";
			}
		}
		return "ERROR - but why ?";
	}

	public static function itemDeleteSession( $key )
	{
		$key = intval( $key );
		$Result = self::_restore();
		if(!empty($Result)) {
			unset($Result[$key]);
			self::_clear();
			if(!empty($Result)) {
				self::_store( $Result ) ;
			}
			return "OK";
		} else {
			return "EMPTY";
		}
	}

	public static function num2words( $liczba ) {
        $liczba = sprintf("%.2f", $liczba);

        $jednosci = Array( '', 'jeden', 'dwa', 'trzy', 'cztery', 'pięć', 'sześć', 'siedem', 'osiem', 'dziewięć' );
        $dziesiatki = Array( '', 'dziesięć', 'dwadzieścia', 'trzydzieści', 'czterdzieści', 'piećdziesiąt', 'sześćdziesiąt', 'siedemdziesiąt', 'osiemdziesiąt',  'dziewiećdziesiąt' );
        $setki = Array( '', 'sto', 'dwieście', 'trzysta', 'czterysta', 'piećset', 'sześćset', 'siedemset', 'osiemset', 'dziewiećset' );
        $nastki = Array( 'dziesieć', 'jedenaście', 'dwanaście', 'trzynaście', 'czternaście', 'piętnaście', 'szesnaście', 'siedemnaście', 'osiemnaście', 'dzięwietnaście' );
        $tysiace = Array( 'tysiąc', 'tysiące', 'tysięcy' );

        $string = null;
        $string1 = null;

        $grosze = round(($liczba-(int)$liczba)*100,2);
        $cyfra = (int)$liczba;
        $cyfra = strrev( $cyfra );
        $cyfra1 = strrev( $grosze );
        $i = strlen( $cyfra );
        $j = strlen( $cyfra1 );

        if ($i > 5)$string .= $setki[$cyfra[5]] . ' ';
        if ($i > 4){
          if ($cyfra[4] == 1)$string .= $nastki[$cyfra[3]]. ' ';
          if ($cyfra[4] != 1)$string .= $dziesiatki[$cyfra[4]] . ' '.$jednosci[$cyfra[3]]. ' ';}
          if ($i > 4){if($cyfra[3] == 1) $string .= $tysiace[2] . ' '; else $string .= $tysiace[2]. ' ';}
          if ($i == 4){
              if ($cyfra[3] == 1) $string .= $tysiace[0] . ' ' ;
                  if ($cyfra[3] > 1 AND $cyfra[3] < 5 ) $string .= $jednosci[$cyfra[3]]. ' ' . $tysiace[1] . ' ' ;
                      if ($cyfra[3] > 4 )$string .= $jednosci[$cyfra[3]]. ' ' . $tysiace[2] . ' ' ;}
                  if ($i > 2)$string .= $setki[$cyfra[2]] . ' ';
                   if ($i > 1){
                   if ($cyfra[1] == 1)$string .= $nastki[$cyfra[0]]. ' ';
                   if ($cyfra[1] != 1)$string .= $dziesiatki[$cyfra[1]] . ' '.$jednosci[$cyfra[0]]. ' ';}
                      if ($i == 1) $string .= $jednosci[$cyfra[0]]. ' ';

        if ($j > 1){
        if ($cyfra1[1] == 1)$string1 .= $nastki[$cyfra1[0]]. ' ';
        if ($cyfra1[1] != 1)$string1 .= $dziesiatki[$cyfra1[1]] . ' '.$jednosci[$cyfra1[0]]. ' ';}
        if ($j == 1) $string1 .= $jednosci[$cyfra1[0]]. ' ';{
        if ($grosze == 0) $string1 .= '0';}

        return ($string ."zł ". $grosze ."/100 gr");
	}
}
