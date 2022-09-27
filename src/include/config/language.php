<?php
Language::$available = array(
	"pl" => "Język Polski",
	"en" => "English"
);

if(empty($_SESSION['lng']['code'])) {
	Language::change( (empty($request->get['language']) ? 'pl' : $request->get['language'] ));
} else {
	if(!empty($request->get['language'])) {
		Language::change( $request->get['language'] );
		if( (isset($request->get['admin'])) && ($request->get['admin'] == true) ) {
			if($request->get['redirect']) {
				Kernel::redirect( $request->get['redirect'] );
			} else {
				Kernel::redirect( $app_url . "/admin/start.html");
			}

		} else {
			Kernel::redirect( $app_url );
		}
	} else {
		Language::change( $_SESSION['lng']['code'] );
	}
}

Language::load('cms' , true);
