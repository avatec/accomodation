<?php
use Modules\Attractions\Backend\Attractions as Attractions;
use Modules\Attractions\Backend\Category as Category;

/**
 *  Attractions module for backend controller
 *
 *  @copyright (c) 2019 Grzegorz Miśkiewicz
 *  @version 1.0
 *  @package Avatec Framework
 */

Kernel::module('attractions');
Kernel::access('attractions');

Kernel::$ModuleName = "Atrakcje";

if( empty( $command )) {
    http_response_code( 404 );
    die('Undefined path');
}

$app_module = $app_admin_url . 'attractions/';
$app_return = $app_module . 'list/';

switch( $command )
{
    case "list":
        Kernel::$ModuleName = "Atrakcje > lista";
        Kernel::template("attractions/list.smarty");
        $smarty->assign("list" , $attractions->get_list());
        break;

    case "add":
        Kernel::$ModuleName = "Atrakcje > tworzenie nowego punktu";
        Kernel::template("attractions/add-edit.smarty");

        if(!empty( $request->post['module'] )) {
            $r = $attractions->create();
            if( $r == true ) {
                Kernel::redirect( $app_return );
            }
        }
        break;

    case "edit":
        Kernel::$ModuleName = "Atrakcje > edycja punktu";
        Kernel::template("attractions/add-edit.smarty");
        Form::$post = (empty( $request->post ) ? $attractions->get_row( $request->get['attractions_id'], true ) : $request->post );

        if(!empty( $request->post['module'] )) {
            $r = $attractions->update( $request->get['attractions_id'] );
            if( $r == true ) {
                Kernel::redirect( $app_return );
            }
        }
        break;

    case "delete":
        $attractions->delete( $request->get['attractions_id'] );
        Kernel::redirect( $app_return );
        break;
}

$smarty->assign([
    'app_module' => $app_module,
    'app_return' => $app_return,
    'states' => ObjectsStates::getSelect(),
    'category' => $att_category->get_select()
]);
