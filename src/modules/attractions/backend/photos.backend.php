<?php
use Modules\Attractions\Backend\Photos as Photos;

/**
 *  Attractions photos module for backend controller
 *
 *  @copyright (c) 2019 Grzegorz Miśkiewicz
 *  @version 1.0
 *  @package Avatec Framework
 */

Kernel::module('attractions');
Kernel::access('attractions');

Kernel::$ModuleName = "Zdjęcia";

if( empty( $command )) {
    http_response_code( 404 );
    die('Undefined path');
}

$app_query['attractions_id'] = (!empty($request->any['attractions_id']) ? $request->any['attractions_id'] : null);
$app_module = $app_admin_url . 'attractions/photos/';
$app_return = $app_module . 'list/?' . http_build_query( $app_query );

switch( $command )
{
    case "list":
        Kernel::$ModuleName = "Atrakcje > Zdjęcia > lista";
        Kernel::template("photos/list.smarty");
        Kernel::setCss("photos.min.css" , "attractions/backend");
        Kernel::setJs("uploader.min.js" , "attractions/backend");
        $smarty->assign("list" , $att_photos->get_list( $app_query['attractions_id'] ));

        if(!empty($request->post['module'])) {
            $att_photos->update( $app_query['attractions_id'] );
            Kernel::redirect( $app_return );
        }
        break;

    case "add":
        Kernel::$ModuleName = "Atrakcje > dodawanie nowego zdjęcia";
        Kernel::template("photos/add-edit.smarty");

        if(!empty( $request->post['module'] )) {
            $r = $att_photos->create();
            if( $r == true ) {
                Kernel::redirect( $app_return );
            }
        }
        break;

    case "edit":
        Kernel::$ModuleName = "Atrakcje > Zdjęcia > edycja kategorii";
        Kernel::template("photos/add-edit.smarty");
        Form::$post = (empty( $request->post ) ? $att_photos->get_row( $request->get['photo_id'], true ) : $request->post );

        if(!empty( $request->post['module'] )) {
            $r = $att_photos->update( $request->get['photo_id'] );
            if( $r == true ) {
                Kernel::redirect( $app_return );
            }
        }
        break;

    case "delete":
        $att_photos->delete( $request->get['photo_id'] );
        Kernel::redirect( $app_return );
        break;
}

$smarty->assign([
    'app_module' => $app_module,
    'app_return' => $app_return,
    'app_query' => http_build_query( $app_query ),
    'app_query_array' => $app_query
]);
