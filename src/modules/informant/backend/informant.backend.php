<?php
use Modules\Informant\Backend\Informant as Informant;
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

Kernel::$ModuleName = "Informator";

if( empty( $command )) {
    http_response_code( 404 );
    die('Undefined path');
}

$app_module = $app_admin_url . 'informant/';
$app_return = $app_module . 'list/';

switch( $command )
{
    case "list":
        Kernel::$ModuleName = "Informator > lista";
        Kernel::template("informant/list.smarty");
        $smarty->assign("list" , $informant->get_list());
        break;

    case "add":
        Kernel::$ModuleName = "Informator > tworzenie nowego punktu";
        Kernel::template("informant/add-edit.smarty");

        if(!empty( $request->post['module'] )) {
            $r = $informant->create();
            if( $r == true ) {
                Kernel::redirect( $app_return );
            }
        }
        break;

    case "edit":
        Kernel::$ModuleName = "Informator > edycja punktu";
        Kernel::template("informant/add-edit.smarty");
        Form::$post = (empty( $request->post ) ? $informant->get_row( $request->get['informant_id'], true ) : $request->post );

        if(!empty( $request->post['module'] )) {
            $r = $informant->update( $request->get['informant_id'] );
            if( $r == true ) {
                Kernel::redirect( $app_return );
            }
        }
        break;

    case "delete":
        $informant->delete( $request->get['informant_id'] );
        Kernel::redirect( $app_return );
        break;
}

$smarty->assign([
    'app_module' => $app_module,
    'app_return' => $app_return,
    'states' => ObjectsStates::getSelect(),
    'category' => $category->get_select()
]);
