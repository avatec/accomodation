<?php
	switch( $command ) {

		case "searchContrahentByPin":
			/**
			$DumpResult = array(
				array("id" => "6562177333", "name" => "<b>6562177333</b><br/>AVATEC Miśkiewicz Grzegorz")
			);
			return json_encode($DumpResult);
			**/
		break;
		
		case "invoiceAddItem":
			$Result = BusinessInvoice::itemAddSession();
			return $Result;
		break;
		
		case "invoiceGetItem":
			$Result = BusinessInvoice::itemGetSession();
			if(!empty($Result)) {
				echo $Result;
				exit;
			}
		break;
		
		case "invoiceDeleteItem":
			if(!empty($request->post['id'])) {
				$Result = BusinessInvoice::itemDeleteEdited( intval($request->post['id']), intval($request->post['key']) );
				echo $Result;
				exit;
			} else {
				$Result = BusinessInvoice::itemDeleteSession( intval($request->post['key']) );
				echo $Result;
				exit;
			}
		break;
		
		case "proformaAddItem":
			$Result = BusinessInvoiceProforma::itemAddSession();
			return $Result;
		break;
		
		case "proformaGetItem":
			$Result = BusinessInvoiceProforma::itemGetSession();
			if(!empty($Result)) {
				echo $Result;
				exit;
			}
		break;
		
		case "proformaDeleteItem":
			if(!empty($request->post['id'])) {
				$Result = BusinessInvoiceProforma::itemDeleteEdited( intval($request->post['id']), intval($request->post['key']) );
				echo $Result;
				exit;
			} else {
				$Result = BusinessInvoiceProforma::itemDeleteSession( intval($request->post['key']) );
				echo $Result;
				exit;
			}
		break;
	}
	
	exit;
	
?>