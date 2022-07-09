<?php
Kernel::module("system");

switch( $command ) {
    case "list":
        Kernel::template("languages/list.smarty");
        $smarty->assign("list" , $translation->get_list());
        
        if(!empty($request->post['module'])) {
            $translation->update();
            Kernel::redirect( '/admin/system/languages/list');
        }
    break;
}
