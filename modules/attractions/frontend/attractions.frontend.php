<?php
use Core\Assets as Assets;
use Modules\Attractions\Frontend\attractions as Attractions;

/**
 *  attractions module for frontend controller
 *
 *  @copyright (c) 2019 Grzegorz Miśkiewicz
 *  @version 1.0
 *  @package Avatec Framework
 */

Kernel::module("attractions");
Assets::css('attractions.min.css' , 'attractions');

$Pages = $content->getByComponent("attractions/index");

switch( $command )
{
    case "index":
        Kernel::schema("attractions");
        Kernel::template("list.smarty");
        $smarty->assign("attractions" , [
            'list' => $attractions->get_list()
        ]);
    break;

    case "view":
        Kernel::schema("attractions");
        Kernel::template("view.smarty");
        $smarty->assign("view" , $view = $attractions->get_view( $id ));
        $smarty->assign("latest" , $attractions->get_latest( 4, $id ));
        if(empty($view)) {
            http_response_code( 404 );
        }

        Kernel::addMeta(
            ($view['meta_title'] ? $view['meta_title'] : $view['name']) . " | " . $config['service_meta_title'],
            $view['meta_description'] ? $view['meta_description'] : $config['service_meta_description'],
            '',
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
