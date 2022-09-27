<?php
use \Core\Backend\Navigation as Navigation;

/**
 * Business Notes PDF class
 *
 * @package		Modules
 * @subpackage	Business/NotesPDF
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

class NotesPDF {
	public static function generate( $i )
	{
		global $app_path, $config, $request;

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Program do faktur dla skryptu bazy noclegowej - www.avatec.pl');
		$pdf->SetTitle('Nota korygująca');
		$pdf->SetSubject('');
		$pdf->SetKeywords('');

		$pdf->SetMargins(12, 12, 5, 5);
		$pdf->SetHeaderMargin(0);
		$pdf->SetFooterMargin(0);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		$pdf->SetLineWidth(0.1);
		$pdf->SetDrawColor(230,230,230);
		$pdf->SetFillColor(230,230,230);
		$pdf->AddPage();

		$pdf->ImageSVG($app_path . "modules/business/images/invoice_logo.svg", 12, 10, 60, 14, 'PNG', null, '', false, 300, '', false, false, 0, false, false, false);

		$pdf->SetFont('freesans', 'B', 11);
		$pdf->MultiCell(95, 8, '' , 0, 'R', 0, 0);
		$pdf->MultiCell(95, 8, 'NOTA KORYGUJĄCA nr. ' . $i['note_number'] , 0, 'R', 0, 1, null, null, true, 0, $isHtml = false, true, 8, $valign = "M");

		$pdf->SetFont('freesans', '', 7);
		$pdf->MultiCell(95, 8, '<p><b>Telefon:</b> ' . $config['service_phone_1'] .
		(!empty($config['invoice_email']) ? ', <b>e-mail:</b> ' . $config['invoice_email'] . '<br/>' : '<br/>') .
		(!empty($config['invoice_www']) ? '<b>Strona www:</b> ' . $config['invoice_www'] : '') . '</p>' , 0, 'L', 0, 0, 12, 28, true, 0, $isHtml = true, true, 8, $valign = "M");

		$pdf->MultiCell(95, 4, 'Miejscowość i data wystawienia: ' . $i['note_city'] . ', ' . $i['note_create_date'] , 0, 'R', 0, 1, 107, 24, true, 0, $isHtml = false, true, 5, $valign = "M");
		$pdf->MultiCell(95, 4, 'Dotyczy faktury nr.: ' . $i['invoice_number'] , 0, 'R', 0, 1, 107, null, true, 0, $isHtml = false, true, 5, $valign = "M");
		$pdf->MultiCell(95, 4, 'Data wystawienia faktury: ' . $i['invoice_create_date'] , 0, 'R', 0, 1, 107, null, true, 0, $isHtml = false, true, 5, $valign = "M");

		$pdf->Ln();
		$pdf->Ln();

		// Wystawca i nabywca
		$pdf->SetFont('freesans', 'B', 8);
		$pdf->SetFillColor(230,230,230);
		$pdf->MultiCell(95, 6, 'WYSTAWCA / NABYWCA' , 0, 'L', 1, 0, null, null, true, 0, $isHtml = false, true, 6, $valign = "M");
		$pdf->MultiCell(95, 6, 'ADRESAT / SPRZEDAWCA' , 0, 'R', 1, 1, null, null, true, 0, $isHtml = false, true, 6, $valign = "M");

		$pdf->SetFont('freesans', '', 8);
		$pdf->MultiCell(95, 20, $i['s_name'] . PHP_EOL . $i['s_street'] . PHP_EOL . $i['s_postcode'] . ' ' . $i['s_city'] . PHP_EOL . 'NIP: ' . $i['s_pin'] , 0, 'L', 0, 0, null, null, true, 0, $isHtml = false, true, 20, $valign = "M");
		$pdf->MultiCell(95, 20, $i['b_name'] . PHP_EOL . $i['b_street'] . PHP_EOL . $i['b_postcode'] . ' ' . $i['b_city'] . PHP_EOL . 'NIP: ' . $i['b_pin'] , 0, 'R', 0, 1, null, null, true, 0, $isHtml = false, true, 20, $valign = "M");


		$pdf->SetFont('freesans', 'B', 8);
		$pdf->SetFillColor(230,230,230);
		$pdf->MultiCell(95, 6, 'TREŚĆ KORYGOWANA' , 0, 'L', 1, 0, null, null, true, 0, $isHtml = false, true, 6, $valign = "M");
		$pdf->MultiCell(95, 6, 'TREŚĆ PRAWIDŁOWA' , 0, 'R', 1, 1, null, null, true, 0, $isHtml = false, true, 6, $valign = "M");

		$pdf->SetFont('freesans', '', 8);
		$pdf->MultiCell(95, 20, $i['incorrect'] , 0, 'L', 0, 0, null, null, true, 0, $isHtml = false, true, 20, $valign = "M");
		$pdf->MultiCell(95, 20, $i['correct'] , 0, 'R', 0, 1, null, null, true, 0, $isHtml = false, true, 20, $valign = "M");

		$pdf->SetFont('freesans', 'B', 7);
		$pdf->MultiCell(95, 5 , 'Wystawił(a) ' . $config['invoice_sign_name'] , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
		$pdf->MultiCell(95, 5 , 'Odebrał(a) ' , 1 , 'C', 1, 1, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");

		$pdf->SetFont('freesans', '', 7);
		$pdf->MultiCell(95, 30 , '' , 1, 'C', 0, 0, null, null, true, 0, $isHtml = false, true, 25, $valign = "B");
		$pdf->MultiCell(95, 30 , '' , 1, 'C', 0, 0, null, null, true, 0, $isHtml = false, true, 25, $valign = "B");

		// druga

		$pdf->line(0,150,210,150);

		$pdf->ImageSVG($app_path . "modules/business/images/invoice_logo.svg", 12, 155, 60, 14, 'PNG', null, '', false, 300, '', false, false, 0, false, false, false);
		$pdf->setY(157);
		$pdf->SetFont('freesans', 'B', 11);
		$pdf->MultiCell(95, 8, '' , 0, 'R', 0, 0);
		$pdf->MultiCell(95, 8, 'NOTA KORYGUJĄCA nr. ' . $i['note_number'] , 0, 'R', 0, 1, null, null, true, 0, $isHtml = false, true, 8, $valign = "M");

		$pdf->setY(160);

		$pdf->SetFont('freesans', '', 7);
		$pdf->MultiCell(95, 8, '<p><b>Telefon:</b> ' . $config['service_phone_1'] .
		(!empty($config['invoice_email']) ? ', <b>e-mail:</b> ' . $config['invoice_email'] . '<br/>' : '<br/>') .
		(!empty($config['invoice_www']) ? '<b>Strona www:</b> ' . $config['invoice_www'] : '') . '</p>' , 0, 'L', 0, 0, 12, 173, true, 0, $isHtml = true, true, 8, $valign = "M");

		$pdf->MultiCell(95, 4, 'Miejscowość i data wystawienia: ' . $i['note_city'] . ', ' . $i['note_create_date'] , 0, 'R', 0, 1, 107, 167, true, 0, $isHtml = false, true, 5, $valign = "M");
		$pdf->MultiCell(95, 4, 'Dotyczy faktury nr.: ' . $i['invoice_number'] , 0, 'R', 0, 1, 107, null, true, 0, $isHtml = false, true, 5, $valign = "M");
		$pdf->MultiCell(95, 4, 'Data wystawienia faktury: ' . $i['invoice_create_date'] , 0, 'R', 0, 1, 107, null, true, 0, $isHtml = false, true, 5, $valign = "M");

		$pdf->Ln();
		$pdf->Ln();

		// Wystawca i nabywca
		$pdf->SetFont('freesans', 'B', 8);
		$pdf->SetFillColor(230,230,230);
		$pdf->MultiCell(95, 6, 'WYSTAWCA / NABYWCA' , 0, 'L', 1, 0, null, null, true, 0, $isHtml = false, true, 6, $valign = "M");
		$pdf->MultiCell(95, 6, 'ADRESAT / SPRZEDAWCA' , 0, 'R', 1, 1, null, null, true, 0, $isHtml = false, true, 6, $valign = "M");

		$pdf->SetFont('freesans', '', 8);
		$pdf->MultiCell(95, 20, $i['s_name'] . PHP_EOL . $i['s_street'] . PHP_EOL . $i['s_postcode'] . ' ' . $i['s_city'] . PHP_EOL . 'NIP: ' . $i['s_pin'] , 0, 'L', 0, 0, null, null, true, 0, $isHtml = false, true, 20, $valign = "M");
		$pdf->MultiCell(95, 20, $i['b_name'] . PHP_EOL . $i['b_street'] . PHP_EOL . $i['b_postcode'] . ' ' . $i['b_city'] . PHP_EOL . 'NIP: ' . $i['b_pin'] , 0, 'R', 0, 1, null, null, true, 0, $isHtml = false, true, 20, $valign = "M");


		$pdf->SetFont('freesans', 'B', 8);
		$pdf->SetFillColor(230,230,230);
		$pdf->MultiCell(95, 6, 'TREŚĆ KORYGOWANA' , 0, 'L', 1, 0, null, null, true, 0, $isHtml = false, true, 6, $valign = "M");
		$pdf->MultiCell(95, 6, 'TREŚĆ PRAWIDŁOWA' , 0, 'R', 1, 1, null, null, true, 0, $isHtml = false, true, 6, $valign = "M");

		$pdf->SetFont('freesans', '', 8);
		$pdf->MultiCell(95, 20, $i['incorrect'] , 0, 'L', 0, 0, null, null, true, 0, $isHtml = false, true, 20, $valign = "M");
		$pdf->MultiCell(95, 20, $i['correct'] , 0, 'R', 0, 1, null, null, true, 0, $isHtml = false, true, 20, $valign = "M");

		$pdf->SetFont('freesans', 'B', 7);
		$pdf->MultiCell(95, 5 , 'Wystawił(a) ' . $config['invoice_sign_name'] , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
		$pdf->MultiCell(95, 5 , 'Odebrał(a) ' , 1 , 'C', 1, 1, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");

		$pdf->SetFont('freesans', '', 7);
		$pdf->MultiCell(95, 30 , '' , 1, 'C', 0, 0, null, null, true, 0, $isHtml = false, true, 25, $valign = "B");
		$pdf->MultiCell(95, 30 , '' , 1, 'C', 0, 0, null, null, true, 0, $isHtml = false, true, 25, $valign = "B");



		$filename = str_replace("/" , "_" , $i['note_number']);
		$year = date('Y' , strtotime($i['note_create_date']));

		if(file_exists( $app_path . "archive/" . $year . "/nota_korygujaca_" . $filename . ".pdf" ) == false) {
			@mkdir( $app_path . "archive/" . $year . "/");
			$pdf->Output($app_path . "archive/" . $year . "/nota_korygujaca_" . $filename . ".pdf", "F");
		}

		$pdf->Output('nota_korygująca_' . $filename . '.pdf', 'D');
	}
}

class InvoicePDF {
	public static function generate( $i ) {
		global $app_path, $config, $request;

		$double_print = 1;

		if($request->get['print'] == "copy")  { $iv_print = "KOPIA"; }
		if($request->get['print'] == "oryginal")  { $iv_print = "ORYGINAŁ"; }
		if($request->get['print'] == "complete")  { $iv_print = array(0=>"ORYGINAŁ",1=>"KOPIA"); $double_print = 2; }

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Grzegorz Miśkiewicz');
		$pdf->SetTitle('Faktura VAT');
		$pdf->SetSubject('');
		$pdf->SetKeywords('');

		$pdf->SetMargins(12, 12, 5, 5);
		$pdf->SetHeaderMargin(0);
		$pdf->SetFooterMargin(0);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		$pdf->SetLineWidth(0.1);
		$pdf->SetDrawColor(230,230,230);
		$pdf->SetFillColor(230,230,230);

		do {
			$pdf->AddPage();

			// Header of invoice
			// Logo

			$pdf->ImageSVG($app_path . "modules/business/images/invoice_logo.svg", 12, 10, 60, 14, 'PNG', null, '', false, 300, '', false, false, 0, false, false, false);

			$pdf->SetFont('freesans', '', 8);
			$pdf->MultiCell(95, 4, '' , 0, 'R', 0, 0);

			if($request->get['print'] == "complete") {
				if($double_print == 2) {
					$pdf->MultiCell(95, 4, $iv_print[0] , 0, 'R', 0, 1);
				} else {
					$pdf->MultiCell(95, 4, $iv_print[1] , 0, 'R', 0, 1);
				}
			} else {
				$pdf->MultiCell(95, 4, $iv_print , 0, 'R', 0, 1);
			}

			$pdf->SetFont('freesans', 'B', 11);
			$pdf->MultiCell(95, 8, '' , 0, 'R', 0, 0);
			$pdf->MultiCell(95, 8, 'FAKTURA VAT nr. ' . $i['invoice_number'] , 0, 'R', 0, 1, null, null, true, 0, $isHtml = false, true, 8, $valign = "M");

			$pdf->SetFont('freesans', '', 7);
			$pdf->MultiCell(95, 8, '<p><b>Telefon:</b> ' . $config['service_phone_1'] .
			(!empty($config['invoice_email']) ? ', <b>e-mail:</b> ' . $config['invoice_email'] . '<br/>' : '<br/>') .
			(!empty($config['invoice_www']) ? '<b>Strona www:</b> ' . $config['invoice_www'] : '') . '</p>' , 0, 'L', 0, 0, 12, 28, true, 0, $isHtml = true, true, 8, $valign = "M");
			$pdf->MultiCell(95, 8, '<b>Numer konta:</b><br/>' . $config['bank_name'] . ' ' . $config['bank_account']  , 1, 'C', 1, 0, 12, 36, true, 0, $isHtml = true, true, 8, $valign = "M");

			$pdf->MultiCell(95, 4, 'Wystawiona dnia: ' . $i['create_date'] , 0, 'R', 0, 1, 107, 24, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(95, 4, 'Data sprzedaży: ' . $i['sell_date'] , 0, 'R', 0, 1, 107, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(95, 4, 'Termin płatności: ' . $i['payment_date'] , 0, 'R', 0, 1, 107, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(95, 4, 'Forma płatności: ' . $i['payment_label'] , 0, 'R', 0, 1, 107, null, true, 0, $isHtml = false, true, 5, $valign = "M");

			$pdf->Ln();
			$pdf->Ln();

			// Wystawca i nabywca
			$pdf->SetFont('freesans', 'B', 8);
			$pdf->SetFillColor(230,230,230);
			$pdf->MultiCell(95, 6, 'WYSTAWCA' , 0, 'L', 1, 0, null, null, true, 0, $isHtml = false, true, 6, $valign = "M");
			$pdf->MultiCell(95, 6, 'NABYWCA' , 0, 'R', 1, 1, null, null, true, 0, $isHtml = false, true, 6, $valign = "M");

			$pdf->SetFont('freesans', '', 8);
			$pdf->MultiCell(95, 20, $config['service_name'] . PHP_EOL . $config['service_address'] . PHP_EOL . $config['service_postcode'] . ' ' . $config['service_city'] . PHP_EOL . 'NIP: ' . $config['service_pin'] , 0, 'L', 0, 0, null, null, true, 0, $isHtml = false, true, 20, $valign = "M");
			$pdf->MultiCell(95, 20, BusinessContrahents::_getValue($i['contrahent_id'], "name") . PHP_EOL . BusinessContrahents::_getValue($i['contrahent_id'], "address") . PHP_EOL . BusinessContrahents::_getValue($i['contrahent_id'], "postcode") . ' ' . BusinessContrahents::_getValue($i['contrahent_id'], "city") . PHP_EOL . (is_numeric($i['contrahent_id']) ? 'NIP: ' . BusinessContrahents::_getValue($i['contrahent_id'], "pin") : '') , 0, 'R', 0, 1, null, null, true, 0, $isHtml = false, true, 20, $valign = "M");

			// Nagłówek dla listy produktów na fakturze (190)
			$pdf->SetDrawColor(200,200,200);
			$pdf->SetFont('freesans', 'B', 7);
			$pdf->MultiCell(10, 5, 'LP' , 1, 'L', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(70, 5, 'Nazwa' , 1, 'L', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(20, 5, 'Ilość' , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(20, 5, 'Cena netto' , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(20, 5, 'Wartość netto' , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(10, 5, 'VAT' , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(20, 5, 'Wartość VAT' , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(20, 5, 'Wartość brutto' , 1, 'C', 1, 1, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");

			$netto_all = 0;
			$brutto_all = 0;
			$vat_all = 0;
			$stats = [];
			$lp = 0;
			if(!empty($i['items'])) {
				$lp = 1;
				foreach( $i['items'] as $item ) {
					$str = strlen($item['name']);
					$defaultLineHeight = 4.5;

					if($str<=56) {
						$lh = $defaultLineHeight;
					} else {
						$lineBreak = ceil($str/57);
						$lh = $defaultLineHeight * $lineBreak;
					}

					$pdf->SetFont('freesans', '', 7);
					$pdf->SetFillColor(255,255,255);
					$pdf->MultiCell(10, $lh , $lp . '.' , 1, 'L', 1, 0,null, null, true, 0, $isHtml = false, true, $lh, $valign = "M");
					$pdf->MultiCell(70, $lh , $item['name'] , 1, 'L', 1, 0, null, null, true, 0, $isHtml = false, true, $lh, $valign = "M");
					$pdf->MultiCell(20, $lh , $item['num'] . ' ' . $item['num_type'] , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, $lh, $valign = "M");
					$pdf->MultiCell(20, $lh , money_format("%.2i", $item['price_netto']) , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, $lh, $valign = "M");
					$pdf->MultiCell(20, $lh , money_format("%.2i", $item['price_netto'] * $item['num']) , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, $lh, $valign = "M");
					$pdf->MultiCell(10, $lh , $item['vat'] . "%" , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, $lh, $valign = "M");
					$pdf->MultiCell(20, $lh , money_format("%.2i", $item['price_vat'] * $item['num']) , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, $lh, $valign = "M");
					$pdf->MultiCell(20, $lh , money_format("%.2i", $item['price_brutto_all']) , 1, 'C', 1, 1, null, null, true, 0, $isHtml = false, true, $lh, $valign = "M");

					$vat = $item['vat'];

					if(empty($stats[$vat])) {
						$stats[$vat] = array(
							"netto_all" => $item['num'] * $item['price_netto'],
							"vat_all" => $item['price_vat'] * $item['num'],
							"brutto_all" => $item['price_brutto_all']
						);
					} else {
						$stats[$vat]['netto_all'] += $item['num'] * $item['price_netto'];
						$stats[$vat]['vat_all'] += $item['price_vat'] * $item['num'];
						$stats[$vat]['brutto_all'] += $item['price_brutto_all'];
					}

					$netto_all += $item['num'] * $item['price_netto'];
					$brutto_all += $item['price_brutto_all'];
					$vat_all += $item['price_vat'] * $item['num'];

					$lp++;
				}
			}

			// Podsumowanie
			$pdf->SetFillColor(235,235,235);
			$pdf->SetFont('freesans', 'B', 7);
			$pdf->MultiCell(100, 5 , '' , 0, 'C', 0, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(20, 5 , 'SUMA' , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->SetFont('freesans', 'B', 7);
			$pdf->MultiCell(20, 5 , money_format("%.2i", $netto_all) , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(10, 5 , '-' , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(20, 5 , money_format("%.2i", $vat_all) , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(20, 5 , money_format("%.2i", $brutto_all) , 1, 'C', 1, 1, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");

			ksort($stats);
			// W tym
			foreach($stats as $vat=>$value) {
				$pdf->SetFillColor(255,255,255);
				$pdf->SetFont('freesans', 'B', 7);
				$pdf->MultiCell(100, 5 , '' , 0, 'C', 0, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
				$pdf->MultiCell(20, 5 , 'w tym' , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
				$pdf->SetFont('freesans', '', 7);
				$pdf->MultiCell(20, 5 , money_format("%.2i", $value['netto_all']) , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
				$pdf->MultiCell(10, 5 , $vat . "%" , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
				$pdf->MultiCell(20, 5 , money_format("%.2i", $value['vat_all']) , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
				$pdf->MultiCell(20, 5 , money_format("%.2i", $value['brutto_all']) , 1, 'C', 1, 1, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			}
			// Słownie
			$pdf->SetFillColor(235,235,235);
			$pdf->SetFont('freesans', 'B', 7);
			$pdf->MultiCell(100, 5 , '' , 0, 'C', 0, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(20, 5 , 'słownie' , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->SetFont('freesans', '', 7);
			$pdf->MultiCell(70, 5 , BusinessInvoice::num2words($brutto_all) , 1, 'L', 1, 1, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");

			$pdf->Ln();

			// Uwagi i Razem brutto
			$pdf->SetFont('freesans', '', 7);
			$pdf->MultiCell(95, 5 , (!empty($i['notice']) ? '<p><b>Uwagi</b><br/>' . $i['notice'] . '</p>': '') , 0, 'L', 0, 0, null, null, true, 0, $isHtml = true, true, 5, $valign = "T");
			$pdf->SetFont('freesans', '', 8);
			if($i['payment'] == "FALSE") {
				$pozostalo = $brutto_all - $i['payment_amount'];
			} else {
				$pozostalo = $brutto_all - $i['payment_amount'];
			}
			$pdf->MultiCell(95, 5 , '<b>RAZEM BRUTTO:</b> ' . money_format("%.2i", $brutto_all) . ' zł<br/><b>POZOSTAŁO DO ZAPŁATY:</b> ' . money_format("%.2i", $pozostalo) . " zł" , 0, 'R', 0, 1, null, null, true, 0, $isHtml = true, true, 5, $valign = "T");

			$pdf->Ln();

			// Wystawca faktury

			$pdf->SetFont('freesans', 'B', 7);
			$pdf->MultiCell(90, 5 , 'Wystawił(a) ' . $config['invoice_sign_name'] , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(10, 5 , '' , 0, 'R', 0, 1, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->SetFont('freesans', '', 7);
			$pdf->MultiCell(90, 30 , '' , 1, 'C', 0, 0, null, null, true, 0, $isHtml = false, true, 25, $valign = "B");

		$double_print--;
		} while( $double_print > 0 );

		$filename = str_replace("/" , "_" , $i['invoice_number']);
		$year = date('Y' , strtotime($i['create_date']));

		if(file_exists( $app_path . "archive/" . $year . "/faktura_vat_" . $filename . ".pdf" ) == false) {
			@mkdir( $app_path . "archive/" . $year . "/");
			$pdf->Output($app_path . "archive/" . $year . "/faktura_vat_" . $filename . ".pdf", "F");
		}

		$pdf->Output('faktura_vat_' . $filename . '.pdf', 'D');
	}

	public static function proforma( $i )
	{
		global $app_path, $config, $request;

		$double_print = 1;

		if($request->get['print'] == "copy")  { $iv_print = "KOPIA"; }
		if($request->get['print'] == "oryginal")  { $iv_print = "ORYGINAŁ"; }
		if($request->get['print'] == "complete")  { $iv_print = array(0=>"ORYGINAŁ",1=>"KOPIA"); $double_print = 2; }

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Grzegorz Miśkiewicz');
		$pdf->SetTitle('Faktura PROFORMA');
		$pdf->SetSubject('');
		$pdf->SetKeywords('');

		$pdf->SetMargins(12, 12, 5, 5);
		$pdf->SetHeaderMargin(0);
		$pdf->SetFooterMargin(0);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		$pdf->SetLineWidth(0.1);
		$pdf->SetDrawColor(230,230,230);
		$pdf->SetFillColor(230,230,230);

		do {
			$pdf->AddPage();

			// Header of invoice
			// Logo

			$pdf->ImageSVG($app_path . "modules/business/images/invoice_logo.svg", 12, 10, 60, 14, 'PNG', null, '', false, 300, '', false, false, 0, false, false, false);

			$pdf->SetFont('freesans', 'B', 11);
			$pdf->MultiCell(95, 4, '' , 0, 'R', 0, 0);
			$pdf->MultiCell(95, 4, 'FAKTURA PROFORMA nr. ' . $i['invoice_number'] , 0, 'R', 0, 1, null, null, true, 0, $isHtml = false, true, 8, $valign = "M");

			$pdf->SetFont('freesans', '', 7);
			$pdf->MultiCell(95, 8, '<p><b>Telefon:</b> ' . $config['service_phone_1'] .
			(!empty($config['invoice_email']) ? ', <b>e-mail:</b> ' . $config['invoice_email'] . '<br/>' : '<br/>') .
			(!empty($config['invoice_www']) ? '<b>Strona www:</b> ' . $config['invoice_www'] : '') . '</p>' , 0, 'L', 0, 0, 12, 28, true, 0, $isHtml = true, true, 8, $valign = "M");
			$pdf->MultiCell(95, 8, '<b>Numer konta:</b><br/>' . $config['bank_name'] . ' ' . $config['bank_account']  , 1, 'C', 1, 0, 12, 36, true, 0, $isHtml = true, true, 8, $valign = "M");

			$pdf->MultiCell(95, 4, 'Wystawiona dnia: ' . $i['create_date'] , 0, 'R', 0, 1, 107, 24, true, 0, $isHtml = false, true, 5, $valign = "M");
			/**$pdf->MultiCell(95, 4, 'Data sprzedaży: ' . $i['sell_date'] , 0, 'R', 0, 1, 107, null, true, 0, $isHtml = false, true, 5, $valign = "M");**/
			$pdf->MultiCell(95, 4, 'Termin płatności: ' . $i['payment_date'] , 0, 'R', 0, 1, 107, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(95, 4, 'Forma płatności: ' . $i['payment_label'] , 0, 'R', 0, 1, 107, null, true, 0, $isHtml = false, true, 5, $valign = "M");

			$pdf->Ln();
			$pdf->Ln();

			// Wystawca i nabywca
			$pdf->SetFont('freesans', 'B', 8);
			$pdf->SetFillColor(230,230,230);
			$pdf->MultiCell(95, 6, 'WYSTAWCA' , 0, 'L', 1, 0, null, null, true, 0, $isHtml = false, true, 6, $valign = "M");
			$pdf->MultiCell(95, 6, 'NABYWCA' , 0, 'R', 1, 1, null, null, true, 0, $isHtml = false, true, 6, $valign = "M");

			$pdf->SetFont('freesans', '', 8);
			$pdf->MultiCell(95, 20, $config['service_name'] . PHP_EOL . $config['service_address'] . PHP_EOL . $config['service_postcode'] . ' ' . $config['service_city'] . PHP_EOL . 'NIP: ' . $config['service_pin'] , 0, 'L', 0, 0, null, null, true, 0, $isHtml = false, true, 20, $valign = "M");
			$pdf->MultiCell(95, 20, BusinessContrahents::_getValue($i['contrahent_id'], "name") . PHP_EOL . BusinessContrahents::_getValue($i['contrahent_id'], "address") . PHP_EOL . BusinessContrahents::_getValue($i['contrahent_id'], "postcode") . ' ' . BusinessContrahents::_getValue($i['contrahent_id'], "city") . (!empty(BusinessContrahents::_getValue($i['contrahent_id'], "pin")) ? PHP_EOL . 'NIP: ' . BusinessContrahents::_getValue($i['contrahent_id'], "pin") : "") , 0, 'R', 0, 1, null, null, true, 0, $isHtml = false, true, 20, $valign = "M");

			// Nagłówek dla listy produktów na fakturze (190)
			$pdf->SetDrawColor(200,200,200);
			$pdf->SetFont('freesans', 'B', 7);
			$pdf->MultiCell(10, 5, 'LP' , 1, 'L', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(70, 5, 'Nazwa' , 1, 'L', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(20, 5, 'Ilość' , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(20, 5, 'Cena netto' , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(20, 5, 'Wartość netto' , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(10, 5, 'VAT' , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(20, 5, 'Wartość VAT' , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(20, 5, 'Wartość brutto' , 1, 'C', 1, 1, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");

			$netto_all = 0;
			$brutto_all = 0;
			$vat_all = 0;
			$stats = array();

			if(!empty($i['items'])) {
				$lp=1;
				foreach( $i['items'] as $item ) {
					$str = strlen($item['name']);
					$defaultLineHeight = 4.5;

					if($str<=56) {
						$lh = $defaultLineHeight;
					} else {
						$lineBreak = ceil($str/57);
						$lh = $defaultLineHeight * $lineBreak;
					}

					$pdf->SetFont('freesans', '', 7);
					$pdf->SetFillColor(255,255,255);
					$pdf->MultiCell(10, $lh , $lp . '.' , 1, 'L', 1, 0,null, null, true, 0, $isHtml = false, true, $lh, $valign = "M");
					$pdf->MultiCell(70, $lh , $item['name'] , 1, 'L', 1, 0, null, null, true, 0, $isHtml = false, true, $lh, $valign = "M");
					$pdf->MultiCell(20, $lh , $item['num'] . ' ' . $item['num_type'] , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, $lh, $valign = "M");
					$pdf->MultiCell(20, $lh , money_format("%.2i", $item['price_netto']) , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, $lh, $valign = "M");
					$pdf->MultiCell(20, $lh , money_format("%.2i", $item['price_netto'] * $item['num']) , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, $lh, $valign = "M");
					$pdf->MultiCell(10, $lh , $item['vat'] . "%" , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, $lh, $valign = "M");
					$pdf->MultiCell(20, $lh , money_format("%.2i", $item['price_vat'] * $item['num']) , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, $lh, $valign = "M");
					$pdf->MultiCell(20, $lh , money_format("%.2i", $item['price_brutto_all']) , 1, 'C', 1, 1, null, null, true, 0, $isHtml = false, true, $lh, $valign = "M");

					$vat = $item['vat'];

					if(empty($stats[$vat])) {
						$stats[$vat] = array(
							"netto_all" => $item['num'] * $item['price_netto'],
							"vat_all" => $item['price_vat'] * $item['num'],
							"brutto_all" => $item['price_brutto_all']
						);
					} else {
						$stats[$vat]['netto_all'] += $item['num'] * $item['price_netto'];
						$stats[$vat]['vat_all'] += $item['price_vat'] * $item['num'];
						$stats[$vat]['brutto_all'] += $item['price_brutto_all'];
					}

					$netto_all += $item['num'] * $item['price_netto'];
					$brutto_all += $item['price_brutto_all'];
					$vat_all = $brutto_all - $netto_all;

					$lp++;
				}
			}

			// Podsumowanie
			$pdf->SetFillColor(235,235,235);
			$pdf->SetFont('freesans', 'B', 7);
			$pdf->MultiCell(100, 5 , '' , 0, 'C', 0, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(20, 5 , 'SUMA' , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->SetFont('freesans', 'B', 7);
			$pdf->MultiCell(20, 5 , money_format("%.2i", $netto_all) , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(10, 5 , '-' , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(20, 5 , money_format("%.2i", $vat_all) , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(20, 5 , money_format("%.2i", $brutto_all) , 1, 'C', 1, 1, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");

			// W tym
			foreach($stats as $vat=>$value) {
				$pdf->SetFillColor(255,255,255);
				$pdf->SetFont('freesans', 'B', 7);
				$pdf->MultiCell(100, 5 , '' , 0, 'C', 0, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
				$pdf->MultiCell(20, 5 , 'w tym' , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
				$pdf->SetFont('freesans', '', 7);
				$pdf->MultiCell(20, 5 , money_format("%.2i", $value['netto_all']) , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
				$pdf->MultiCell(10, 5 , $vat . "%" , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
				$pdf->MultiCell(20, 5 , money_format("%.2i", $value['vat_all']) , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
				$pdf->MultiCell(20, 5 , money_format("%.2i", $value['brutto_all']) , 1, 'C', 1, 1, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			}
			// Słownie
			$pdf->SetFillColor(235,235,235);
			$pdf->SetFont('freesans', 'B', 7);
			$pdf->MultiCell(100, 5 , '' , 0, 'C', 0, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(20, 5 , 'słownie' , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->SetFont('freesans', '', 7);
			$pdf->MultiCell(70, 5 , BusinessInvoice::num2words($brutto_all) , 1, 'L', 1, 1, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");

			$pdf->Ln();

			// Uwagi i Razem brutto
			$pdf->SetFont('freesans', '', 7);
			$pdf->MultiCell(95, 5 , (!empty($i['notice']) ? '<p><b>Uwagi</b><br/>' . $i['notice'] . '</p>': '') , 0, 'L', 0, 0, null, null, true, 0, $isHtml = true, true, 5, $valign = "T");
			$pdf->SetFont('freesans', '', 8);
			$pdf->MultiCell(95, 5 , '<b>RAZEM BRUTTO:</b> ' . money_format("%.2i", $brutto_all) . ' zł' , 0, 'R', 0, 1, null, null, true, 0, $isHtml = true, true, 5, $valign = "T");

			$pdf->Ln();

			// Wystawca faktury

			$pdf->SetFont('freesans', 'B', 7);
			$pdf->MultiCell(90, 5 , 'Wystawił(a) ' . $config['invoice_sign_name'] , 1, 'C', 1, 0, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->MultiCell(10, 5 , '' , 0, 'R', 0, 1, null, null, true, 0, $isHtml = false, true, 5, $valign = "M");
			$pdf->SetFont('freesans', '', 7);
			$pdf->MultiCell(90, 30 , '' , 1, 'C', 0, 0, null, null, true, 0, $isHtml = false, true, 25, $valign = "B");

			unset($stats);
		$double_print--;
		} while( $double_print > 1 );

		$filename = str_replace("/" , "_" , $i['invoice_number']);
		$year = date('Y' , strtotime($i['create_date']));

		if(file_exists( $app_path . "archive/" . $year . "/faktura_proforma_" . $filename . ".pdf" ) == false) {
			@mkdir( $app_path . "archive/" . $year . "/");
			$pdf->Output($app_path . "archive/" . $year . "/faktura_proforma_" . $filename . ".pdf", "F");
		}

		$pdf->Output('faktura_proforma_' . $filename . '.pdf', 'D');
	}
}
