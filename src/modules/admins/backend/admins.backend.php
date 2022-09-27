<?php

use Core\Kernel;
use Core\Views;
use Core\Assets;
use Core\Form;
use Core\Backend\Panel;

Kernel::access("advanced");
Views::module("admins");
Panel::setTitle("Administratorzy");

$app_return = $app_admin_url . 'admins/list';

switch($command) {
    case "list":
        Views::set("list");
        $smarty->assign("list", $Admins->get_list());
        break;

    case "add":
        Views::set("add-edit");
        Assets::js("generate-password.min.js", "admins");

        if (!empty($request->post['module'])) {
            if ($Admins->add() == true) {
                Request::redirect($app_return);
            }
        }
        break;

    case "edit":
        Views::set("add-edit");
        Assets::js("generate-password.min.js", "admins");

        Form::$post = (!empty($request->post) ? $request->post : $Admins->get_row($request->get['id'], true));

        if (!empty($request->post['module'])) {
            if ($Admins->update($request->get['id']) == true) {
                Request::redirect($app_return);
            }
        }
        break;

    case "change-password":
        Views::set("change-password");
        Assets::js("generate-password.min.js", "admins");

        if (!empty($request->post['module'])) {
            $Admins->change_password($request->get['id']);
            Request::redirect($app_return);
        }
        break;

    case "activate":
        $Admins->activate($request->get['id']);
        Request::redirect($app_return);
        break;

    case "disactivate":
        $Admins->disactivate($request->get['id']);
        Request::redirect($app_return);
        break;

    case "access":
        Views::set("access");

        $smarty->assign("access_default", $Admins->get_access($request->get['id']));
        $smarty->assign("nav_default", Core\Backend\Navigation::$menu);

        if (!empty($request->post['module'])) {
            if ($Admins->update_access($request->get['id']) == true) {
                Request::redirect($app_return);
            }
        }
        break;

    case "notify":
        Views::set("notify");

        $smarty->assign("notify_default", $Admins->get_notify($request->get['id']));

        if (!empty($request->post['module'])) {
            if ($Admins->update_notify($request->get['id']) == true) {
                Request::redirect($app_return);
            }
        }
        break;

    case "delete":
        $Admins->delete($request->get['id']);
        Request::redirect($app_return);
        break;
}
