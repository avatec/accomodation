<?php
Kernel::access("invoice;");
Kernel::module("business");
Kernel::setCss("invoice.css" , "business");

switch( $command ) {
	case "list":
		Kernel::$ModuleName = "Przeglądanie listy faktur";
		Kernel::setJs("invoiceList.js", "business");
		Kernel::template("invoice/list.smarty");
		$smarty->assign("list" , $b_invoice->get());
	break;
	
	case "add":
		Kernel::$ModuleName = "Tworzenie nowej faktury";
		Kernel::template("invoice/add-edit.smarty");
		Kernel::setJs("invoiceItem.js", "business");
		Kernel::setJs("contrahent.js" , "business");
		Form::$post = $request->post;
		$smarty->assign("invoice_number" , $a = BusinessInvoice::getNewInvoiceNumber());
		$smarty->assign("now" , date('Y-m-d'));
		$smarty->assign("invoice_city" , (isset($config['invoice_city']) ? $config['invoice_city'] : "Jędrzejów"));
		
		//BusinessInvoice::_clear();
		
		if(!empty($request->post['module'])) {
			if($b_invoice->create() == true) {
				BusinessInvoice::_clear();
				Kernel::redirect($app_url . "admin/business/invoice/list/");
			}
		} else {
			BusinessInvoice::_clear();
		}
	break;
	
	case "edit":
		Kernel::$ModuleName = "Edycja faktury";
		Kernel::template("invoice/add-edit.smarty");
		Kernel::setJs("invoiceItem.js", "business");
		Kernel::setJs("contrahent.js" , "business");
		
		if(empty($request->post)) { BusinessInvoice::_clear(); Form::$post = $b_invoice->get($request->get['id']); } else { Form::$post = $request->post; }
		if(!empty($request->post['module'])) {
			$result = $b_invoice->save( $request->get['id'] );
			if(!empty($result)) {
				BusinessInvoice::_clear();
				Kernel::setMessage("NOTICE" , "Pomyślnie zapisano zmiany");
				Kernel::redirect($app_url . "admin/business/invoice/list/");
			} else {
				Kernel::setMessage("ERROR" , "Wystąpił błąd podczas zapisywania zmian:<br/>" . Db::error());
			}
		}
	break;
	
	case "delete":
		$result = $b_invoice->delete( $request->get['id'] );
		if(!empty($result)) {
			Kernel::setMessage("NOTICE" , "Pomyślnie usunięto fakturę");
		} else {
			Kernel::setMessage("ERROR" , "Wystąpił błąd podczas usuwania faktury. Najprawdopodobniej wybrana pozycja nie istnieje<br/>" . Db::error());
		}
		Kernel::redirect($app_url . "admin/business/invoice/list/");
	break;
	
	case "download":
		$data = $b_invoice->get( $request->get['id'] );
		InvoicePDF::generate( $data );
	break;
	
	case "set-payment":
		$Result = $b_invoice->setPayment( $request->post['id'], $request->post['amount'] );
		if($Result == false) {
			Kernel::setMessage("ERROR" , "Nie zmieniono statusu płatności z powodu błędu: " . (!empty(BusinessInvoice::$Error) ? implode("<br/>" , BusinessProforma::$Error) : "nieznany błąd"));
		}
		Kernel::redirect($app_url . "admin/business/invoice/list/");
	break;
	
	case "note-correction":
		Kernel::$ModuleName = "Nota korygująca";
		Kernel::template("invoice/note-correction.smarty");
		$smarty->assign("invoice" , $b_invoice->get($request->get['id']));
		if(empty($request->post)) { Form::$post = $b_invoice->get($request->get['id']); } else { Form::$post = $request->post; }
	break;
	
	case "correction":
		Kernel::$ModuleName = "Faktura korygująca";
		Kernel::template("invoice/correction.smarty");
		$smarty->assign("invoice" , $b_invoice->get($request->get['id']));
	break;
		
}