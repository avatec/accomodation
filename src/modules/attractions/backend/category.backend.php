<?php
use Modules\Attractions\Backend\Category as Category;

/**
 *  Attractions Category module for backend controller
 *
 *  @copyright (c) 2019 Grzegorz Miśkiewicz
 *  @version 1.0
 *  @package Avatec Framework
 */

Kernel::module('attractions');
Kernel::access('attractions');

Kernel::$ModuleName = "Kategorie";

if( empty( $command )) {
    http_response_code( 404 );
    die('Undefined path');
}

$app_module = $app_admin_url . 'attractions/category/';
$app_return = $app_module . 'list/';

switch( $command )
{
    case "list":
        Kernel::$ModuleName = "Atrakcje > kategorie > lista";
        Kernel::template("category/list.smarty");
        $smarty->assign("list" , $att_category->get_list());
        break;

    case "add":
        Kernel::$ModuleName = "Atrakcje > kategorie > tworzenie nowej kategorii";
        Kernel::template("category/add-edit.smarty");

        if(!empty( $request->post['module'] )) {
            $r = $att_category->create();
            if( $r == true ) {
                Kernel::redirect( $app_return );
            }
        }
        break;

    case "edit":
        Kernel::$ModuleName = "Atrakcje > kategorie -> edycja kategorii";
        Kernel::template("category/add-edit.smarty");
        Form::$post = (empty( $request->post ) ? $att_category->get_row( $request->get['category_id'], true ) : $request->post );

        if(!empty( $request->post['module'] )) {
            $r = $att_category->update( $request->get['category_id'] );
            if( $r == true ) {
                Kernel::redirect( $app_return );
            }
        }
        break;

    case "delete":
        $att_category->delete( $request->get['category_id'] );
        Kernel::redirect( $app_return );
        break;
}

$smarty->assign([
    'app_module' => $app_module,
    'app_return' => $app_return
]);
