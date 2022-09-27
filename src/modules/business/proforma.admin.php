<?php
Kernel::access("invoice;");
Kernel::module("business");
Kernel::setCss("invoice.css" , "business");

switch( $command ) {
	case "list":
		Kernel::$ModuleName = "Przeglądanie listy faktur";
		Kernel::setJs("invoiceList.js", "business");
		Kernel::template("proforma/list.smarty");
		$smarty->assign("list" , $b_proforma->get());
	break;
	
	case "add":
		Kernel::$ModuleName = "Tworzenie nowej faktury";
		Kernel::template("proforma/add-edit.smarty");
		Kernel::setJs("proformaItem.js", "business");
		Kernel::setJs("contrahent.js" , "business");
		Form::$post = $request->post;
		$smarty->assign("now" , date('Y-m-d'));
		$smarty->assign("invoice_city" , (isset($config['invoice_city']) ? $config['invoice_city'] : "Jędrzejów"));
		$smarty->assign("invoice_number" , BusinessInvoiceProforma::getNewInvoiceNumber());
		
		if(!empty($request->post['module'])) {
			$result = $b_proforma->create();
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Dodano nowego fakturę");
				Kernel::redirect($app_url . "admin/business/proforma/list/");
			} else {
				$error = BusinessInvoiceProforma::$Error;
				print_r($error);
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas dodawania nowej faktury:<br/>" . (!empty($error) ? implode("<br/>" , $error) : ""));
			}
		} else {
			BusinessInvoiceProforma::_clear();
		}
	break;
	
	case "edit":
		Kernel::$ModuleName = "Edycja faktury";
		Kernel::template("proforma/add-edit.smarty");
		Kernel::setJs("proformaItem.js", "business");
		Kernel::setJs("contrahent.js" , "business");
		$smarty->assign("now" , date('Y-m-d'));
		$smarty->assign("invoice_city" , (isset($config['invoice_city']) ? $config['invoice_city'] : "Jędrzejów"));
		if(empty($request->post)) { BusinessInvoiceProforma::_clear(); Form::$post = $b_proforma->get($request->get['id']); } else { Form::$post = $request->post; }
		
		if(!empty($request->post['module'])) {
			$result = $b_proforma->save( $request->get['id'] );
			if(!empty($result)) {
				Kernel::setMessage("NOTICE" , "Pomyślnie zapisano zmiany");
				Kernel::redirect($app_url . "admin/business/proforma/list/");
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas zapisywania zmian:<br/>" . Db::error());
			}
		}
	break;
	
	case "delete":
		$result = $b_proforma->delete( $request->get['id'] );
		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto fakturę");
		} else {
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas usuwania faktury. Najprawdopodobniej wybrana pozycja nie istnieje<br/>" . Db::error());
		}
		Kernel::redirect($app_url . "admin/business/proforma/list/");
	break;
	
	case "cancel":
		$b_proforma->cancel( $request->get['id'] );
		Kernel::redirect($app_url . "admin/business/proforma/list/");
	break;
	
	case "download":
		$data = $b_proforma->get( $request->get['id'] );
		InvoicePDF::proforma( $data );
	break;	
	
	case "set-payment":
		$Result = $b_proforma->setPayment( $request->post['id'], $request->post['amount'] );
		if($Result == false) {
			Kernel::setMessage("ERROR" , "Nie zmieniono statusu płatności z powodu błędu: " . (!empty(BusinessProforma::$Error) ? implode("<br/>" , BusinessProforma::$Error) : "nieznany błąd"));
		}
		Kernel::redirect($app_url . "admin/business/proforma/list/");
	break;
	
}