<?php
/**
 * Generowanie faktur VAT dla rozliczonych transakcji bookingu
 * Copyright (c) 2017 Grzegorz Miśkiewicz
 * Wszystkie prawa zastrzeżone
 *
 * Modyfikowanie tego pliku może spowodować błędne funkcjonowanie funkcji automatycznego
 * wystawiania faktur VAT do rozliczonych transakcji rezerwacji noclegów
 *
 * Uruchomienie:
 * Wywołanie tego pliku powinno nastąpić w pierwszym dniu miesiąca np. 1 stycznia
 */
 
 	$a = Kernel::compress($app_url . "templates/website/css/style.css", "templates/website/css/style.min.css");
 	echo $a;

 exit;
 	$ac_date = date('Y-m-d', strtotime('-1 month'));
 	$first_day = date('Y-m-01', strtotime('-1 month'));
 	$last_day = date('Y-m-t', strtotime('-1 month'));
 	
 	echo '[' . $ac_date . '] - ' . $first_day . ' / ' . $last_day;
 	
 	// Pobieranie wszystkich rozliczeń z zakresu
 	$b = Booking::getSettlementedByDates( $first_day, $last_day );
 	if(!empty($b)) {
	 	foreach($b as $i) {
		 	$inv_amount[$i['uid']] = $i['settlement_amount'];
	 	}
 	}
 	
 	// Generowanie faktury
 	// Rozliczenie prowizyjne za okres $first_day do $last_day
 	
 
 	
 ?>