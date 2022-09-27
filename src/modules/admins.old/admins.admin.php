<?php
Kernel::access("admins");
Kernel::module("admins");
Kernel::$ModuleName = "Administratorzy";

$app_return = $app_admin_url . 'admins/list';

switch( $command )
{
	case "list":
		Kernel::template("list.smarty");
		$smarty->assign("list" , $Admins->get_list());
	break;

	case "add":
		Kernel::template("add-edit.smarty");
		Kernel::setJs("generate-password.js" , "admins");
		Kernel::$TopButtons[] = [
			'icon' => 'chevron-left',
			'name' => LA::get('admins' , 'btn_back'),
			'link' => "admins/list/"
		];

		if(!empty($request->post['module'])) {
			if( $Admins->add() == true ) {
				Kernel::redirect( $app_return );
			}
		}
	break;

	case "edit":
		Kernel::template("add-edit.smarty");
		Kernel::setJs("generate-password.js" , "admins");
		Kernel::$TopButtons[] = [
			'icon' => 'chevron-left',
			'name' => LA::get('admins' , 'btn_back'),
			'link' => "admins/list/"
		];

		Form::$post = (!empty($request->post) ? $request->post : $Admins->get_row($request->get['id'],true));

		if(!empty($request->post['module'])) {
			if( $Admins->update( $request->get['id'] ) == true ) {
				Kernel::redirect( $app_return );
			}
		}
	break;

	case "change-password":
		Kernel::template("change-password.smarty");
		Kernel::setJs("generate-password.js" , "admins");
		Kernel::$TopButtons[] = [
			'icon' => 'chevron-left',
			'name' => LA::get('admins' , 'btn_back'),
			'link' => "admins/list/"
		];

		if(!empty($request->post['module'])) {
			$Admins->change_password( $request->get['id'] );
			Kernel::redirect( $app_return );
		}
	break;

	case "activate":
		$Admins->activate( $request->get['id'] );
		Kernel::redirect( $app_return );
	break;

	case "disactivate":
		$Admins->disactivate( $request->get['id'] );
		Kernel::redirect( $app_return );
	break;

	case "access":
		Kernel::template("access.smarty");
		Kernel::$TopButtons[] = [
			'icon' => 'chevron-left',
			'name' => LA::get('admins' , 'btn_back'),
			'link' => "admins/list/"
		];

		$smarty->assign("access_default" , $Admins->get_access( $request->get['id'] ));

		if(!empty($request->post['module'])) {
			if( $Admins->update_access( $request->get['id'] ) == true ) {
				Kernel::redirect( $app_return );
			}
		}
	break;

	case "notify":
		Kernel::template("notify.smarty");
		Kernel::$TopButtons[] = [
			'icon' => 'chevron-left',
			'name' => LA::get('admins' , 'btn_back'),
			'link' => "admins/list/"
		];

		$smarty->assign("notify_default" , $Admins->get_notify( $request->get['id'] ));

		if(!empty($request->post['module'])) {
			if( $Admins->update_notify( $request->get['id'] ) == true ) {
				Kernel::redirect( $app_return );
			}
		}
	break;

	case "delete":
		$Admins->delete( $request->get['id'] );
		Kernel::redirect( $app_return );
	break;
}
