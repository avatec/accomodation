<?php
Kernel::module("special");
Kernel::schema("panel");

switch( $command ) {
	
	case "list":
		User::isUserLogged("OWNER");
		Kernel::template("list.smarty");
		$smarty->assign("list" , $special->get());
	break;
	
}