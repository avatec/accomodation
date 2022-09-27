<?php namespace Modules\Informant\Frontend;

/**
 *  informant module - frontend model
 *
 *  @copyright (c) 2019 Grzegorz Miśkiewicz
 *  @version 1.0
 *  @package Avatec Framework
 */

use \Common;
use \Db;
use \Kernel;
use \Language;
use \Paginate;
use \System;
use \ObjectsStates;

class Informant
{
    public static $table     = "informant";
    public static $table_i18 = "informant_i18";

    public static $Error = array();

    protected static $UploadDirectory = 'userfiles/informant/informant/';
    public static $UploadPath, $UploadUrl;

    public $link;

    public function __construct()
    {
        global $config, $request, $app_path, $app_url, $route;

        self::$table     = $config['db_prefix'] . self::$table;
        self::$table_i18 = $config['db_prefix'] . self::$table_i18;

        $this->post = (!empty($request->post) ? $request->post : null);
        $this->get  = (!empty($request->get) ? $request->get : null);

        self::$UploadPath = $app_path . self::$UploadDirectory;
        self::$UploadUrl  = $app_url . self::$UploadDirectory;

        $this->get_links();
        $this->routing($route);
    }

    public function routing($route)
    {
        $route->get('(informator)\/:string-i:id', [
            'module' => 'informant', 'file' => 'informant', 'command' => 'view', 'id' => '$3',
        ]);
    }

    public function get_links()
    {
        $this->link['index'] = '/informator';
        $this->link['view']  = '/informator/{name}-i{id}';

        return $this->link;
    }

    protected function create_link($view, $name, $informant_id)
    {
        if (strlen($name) > 60) {
            $name = substr(Kernel::rewrite($name), 0, 70);
        } else {
            $name = Kernel::rewrite($name);
        }

        $link = $this->link['view'];
        $link = str_replace("{name}", $name, $link);
        $link = str_replace("{id}", $informant_id, $link);

        return $link;
    }

    protected function parse($d, $edit = false)
    {
        if (true == $edit) {
            $d['edit'] = true;
        }

        $d['name'] = html_entity_decode($d['name']);
        $d['address'] = html_entity_decode($d['address']);
        $d['postcode'] = html_entity_decode($d['postcode']);
        $d['city'] = html_entity_decode($d['city']);
        $d['phone'] = html_entity_decode($d['phone']);
        $d['email'] = html_entity_decode($d['email']);
        $d['www'] = html_entity_decode($d['www']);
        $d['open_hours'] = html_entity_decode($d['open_hours']);

        $d['create_date_text'] = Common::dateAsText($d['create_date']);
        $d['link']             = $this->create_link('view', $d['name'], $d['informant_id']);

        if (!empty($d['photo']) && System::file_exists(self::$UploadPath . $d['photo']) == true) {
            $photo = $d['photo'];

            unset($d['photo']);

            $d['photo']['thumb'] = self::$UploadUrl . 'thumbs/' . $photo;
            $d['photo']['url']   = self::$UploadUrl . $photo;
            $d['is_blank']       = false;
        }

        if (empty($d['photo'])) {
            $d['photo']['thumb'] = self::$UploadUrl . 'blank.png';
            $d['photo']['url']   = self::$UploadUrl . 'blank.png';
            $d['is_blank']       = true;
        }

        return $d;
    }

    public function get_list()
    {
        Paginate::$query = "SELECT a.*, i18.name, i18.address, i18.postcode, i18.city, i18.state_id, i18.phone, i18.email, i18.www, i18.open_hours, s.name as state_name
        FROM " . self::$table . " AS a RIGHT JOIN " . self::$table_i18 . " AS i18 ON i18.informant_id=a.informant_id
        RIGHT JOIN " . ObjectsStates::$table . " AS s ON s.id=i18.state_id
        WHERE i18.language='" . Language::get_selected() . "' AND a.visibility = 1
        AND a.create_date<=NOW()
        ORDER BY create_date DESC";

        Paginate::$perpage = 12;
        $r                 = Paginate::make();

        if (empty($r)) {
            return;
        }

        foreach ($r as $k => $i) {
            $r[$k] = $this->parse($i);
        }

        return $r;
    }

    public function get_latest($limit = 4, $exclude_id = null)
    {
        $r = Db::query(
            "SELECT a.*, i18.name, i18.address, i18.postcode, i18.city, i18.state, i18.phone, i18.email, i18.www, i18.open_hours, s.name as state_name
            FROM " . self::$table . " AS a RIGHT JOIN " . self::$table_i18 . " AS i18 ON i18.informant_id=a.informant_id
            RIGHT JOIN " . ObjectsStates::$table . " AS s ON s.id=i18.state_id
            WHERE i18.language='" . Language::get_selected() . "' AND a.visibility = 1
            AND a.create_date<=NOW() " . (!empty($exclude_id) ? " AND a.informant_id != '" . $exclude_id . "'" : "") . "
            ORDER BY create_date DESC LIMIT 0," . $limit
        );

        if (empty($r)) {
            return;
        }

        foreach ($r as $k => $i) {
            $r[$k] = $this->parse($i);
        }

        return $r;
    }

    public function get_view($id)
    {
        $r = Db::query_row(
            "SELECT a.*, i18.name, i18.address, i18.postcode, i18.city, i18.state, i18.phone, i18.email, i18.www, i18.open_hours, s.name as state_name
            FROM " . self::$table . " AS a RIGHT JOIN " . self::$table_i18 . " AS i18 ON i18.informant_id=a.informant_id
            RIGHT JOIN " . ObjectsStates::$table . " AS s ON s.id=i18.state_id
            WHERE a.informant_id='" . $id . "' AND i18.language = '" . Language::get_selected() . "' AND a.visibility=1
            AND (a.create_date<=NOW())
            ORDER BY create_date DESC"
        );
        if (empty($r)) {
            http_response_code(404);
            return;
        }

        return $this->parse($r);
    }
}
