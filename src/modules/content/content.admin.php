<?php
Kernel::$ModuleName = LA::get("system" , "menu_content");
Kernel::access("content;");
Kernel::module('content');

$parent = (isset($request->get['parent']) ? $request->get['parent'] : 0);
$section = (!empty($request->get['section']) ? $request->get['section'] : null);

$return_url = $app_url . "admin/content/list/" . (!empty($section) ? "?section=" . $section . "&" : "?") . "parent=" . $parent;
$url_query = (!empty($section) ? "?section=" . $section . "&" : "?") . "parent=" . $parent;
$smarty->assign("section_id" , (!empty($section) ? $section : 0));
$smarty->assign("return_url" , $return_url);

switch($command) {

	case "list":
		$parent = (isset($request->get['parent']) ? $request->get['parent'] : 0);
		if( $parent == 0 ) {
			Kernel::template("content/list-section.smarty");
		} else {
			Kernel::template("content/list.smarty");
		}
		$smarty->assign("list" , $content->getBySection( $section, $parent ));
	break;

	case "add":
		Kernel::$CkEditor = true;
		Kernel::setJs("admin/components.js" , "content");
		Kernel::template("content/add-edit.smarty");
		if(!empty($request->get['parent'])) {
			$smarty->assign("parent" , $content->getParents( $parent ));
		}
		if(!empty($request->post)) {
			Form::$post = $request->post;
		} else {

			Form::$post['priority'] = $content->priority( $parent );
		}

		if(!empty($request->post['module'])) {
			if( $content->add() == true ) {
				Kernel::redirect( $return_url );
			}
		}
	break;

	case "edit":
		Kernel::$CkEditor = true;
		Kernel::setJs("admin/components.js" , "content");
		Kernel::template("content/add-edit.smarty");
		if(!empty($request->get['parent'])) {
			$smarty->assign("parent" , $content->getParents($request->get['parent']));
		}

		if(empty($request->post)) { Form::$post = $content->get($request->get['id']); } else { Form::$post = $request->post; }

		if(!empty($request->post['module'])) {
			if( $content->save( $request->get['id'] ) == true ) {
				Kernel::redirect( $return_url );
			}
		}
	break;

	case "delete":
		$content->delete( $request->get['id'] );
		Kernel::redirect( $return_url );
	break;

}
