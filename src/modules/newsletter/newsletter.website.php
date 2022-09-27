<?php
Kernel::module("newsletter");
Kernel::schema("content");

$config['disable_newsletter_popup'] = true;
switch( $command ) {
	case "subscribe":
		Kernel::template("subscribe.smarty");
		if(!empty($request->post['module'])) {
			if( $newsletter->add( false ) == true ) {
				$smarty->assign("ok" , true);
			} 
		}
	break;

	case "unsubscribe":
		Kernel::template("unsubscribe.smarty");
		$smarty->assign("result" , $newsletter->unsubscribe( $id ));
	break;

	case "activate":
		Kernel::template("activate.smarty");
		$smarty->assign("result" , $newsletter->activate( $id ));
	break;

	case "msg-readed":
		if((!empty($request->get['i'])) AND (!empty($request->get['e']))) {
			NewsletterMessages::_setMsgToReaded( $request->get['i'], $request->get['e'] );
			die();
		}
	break;
}