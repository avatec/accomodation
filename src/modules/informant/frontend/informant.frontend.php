<?php
use Core\Assets as Assets;
use Modules\Informant\Frontend\Informant as Informant;

/**
 *  Informant module for frontend controller
 *
 *  @copyright (c) 2019 Grzegorz Miśkiewicz
 *  @version 1.0
 *  @package Avatec Framework
 */

Kernel::module("informant");
Assets::css('informant.min.css' , 'informant');

$Pages = $content->getByComponent("informant/index");

switch( $command )
{
    case "index":
        Kernel::schema("informant");
        Kernel::template("list.smarty");
        $smarty->assign("informant" , [
            'list' => $informant->get_list()
        ]);
    break;

    case "view":
        Kernel::schema("informant");
        Kernel::template("view.smarty");
        $smarty->assign("view" , $view = $informant->get_view( $id ));
        $smarty->assign("latest" , $informant->get_latest( 4, $id ));
        if(empty($view)) {
            http_response_code( 404 );
        }

        Kernel::addMeta(
            ($view['meta_title'] ? $view['meta_title'] : $view['name']) . " | " . $config['service_meta_title'],
            $view['meta_description'] ? $view['meta_description'] : $config['service_meta_description'],
            $view['meta_keywords'] ? $view['meta_keywords'] : $config['service_meta_keywords'],
            (bool) $view['meta_index'],
            (bool) $view['meta_follow']
        );

        Kernel::addPath([
            "name" => "Aktualności",
            "url" => '/' . Language::get_selected() . '/' . $Pages['rewrite']
        ]);

        Kernel::addPath([
            "name" => $view['name'],
            "main" => true
        ]);
    break;
}

$smarty->assign( 'view_template' , Kernel::get_view() );
