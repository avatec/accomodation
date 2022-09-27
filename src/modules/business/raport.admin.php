<?php
	
	if( (strstr( User::$admin['access'], "invoice;" ) != true) AND ( User::$admin['type'] !== "ADMIN") ) {
		Kernel::setMessage("ERROR" , "Dostęp zabroniony dla Twojego konta");
		Kernel::redirect( $app_url . "admin/start.html" );
	} 
	
	Kernel::module("business");
	
	switch( $command ) {
		case "list":
			Kernel::$ModuleName = "Raporty sprzedaży";
			Kernel::template("raport/search.smarty");
			$smarty->assign("raport" , $b_raport->get());
		break;			
	}
	
?>