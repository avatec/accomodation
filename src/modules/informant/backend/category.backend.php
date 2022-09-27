<?php
use Modules\Informant\Backend\Category as Category;

/**
 *  Informant module for backend controller
 *
 *  @copyright (c) 2019 Grzegorz Miśkiewicz
 *  @version 1.0
 *  @package Avatec Framework
 */

Kernel::module('informant');
Kernel::access('informant');

Kernel::$ModuleName = "Kategorie";

if( empty( $command )) {
    http_response_code( 404 );
    die('Undefined path');
}

$app_module = $app_admin_url . 'informant/category/';
$app_return = $app_module . 'list/';

switch( $command )
{
    case "list":
        Kernel::$ModuleName = "Informator > kategorie > lista";
        Kernel::template("category/list.smarty");
        $smarty->assign("list" , $category->get_list());
        break;

    case "add":
        Kernel::$ModuleName = "Informator > kategorie > tworzenie nowej kategorii";
        Kernel::template("category/add-edit.smarty");

        if(!empty( $request->post['module'] )) {
            $r = $category->create();
            if( $r == true ) {
                Kernel::redirect( $app_return );
            }
        }
        break;

    case "edit":
        Kernel::$ModuleName = "Informator > kategorie -> edycja kategorii";
        Kernel::template("category/add-edit.smarty");
        Form::$post = (empty( $request->post ) ? $category->get_row( $request->get['category_id'], true ) : $request->post );

        if(!empty( $request->post['module'] )) {
            $r = $category->update( $request->get['category_id'] );
            if( $r == true ) {
                Kernel::redirect( $app_return );
            }
        }
        break;

    case "delete":
        $category->delete( $request->get['category_id'] );
        Kernel::redirect( $app_return );
        break;
}

$smarty->assign([
    'app_module' => $app_module,
    'app_return' => $app_return,
    'category' => [

    ]
]);
