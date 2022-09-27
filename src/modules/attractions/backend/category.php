<?php namespace Modules\Attractions\Backend;

/**
 *  Attractions category module - backend model
 *
 *  @copyright (c) 2019 Grzegorz Miśkiewicz
 *  @version 1.0
 *  @package Avatec Framework
 */

use \Db;
use \Kernel;
use \LA;
use \Language;
use \System;
use \Core\Backend\Navigation as Navigation;

class Category
{
    public static $table     = "attractions_category";
    public static $table_i18 = "attractions_category_i18";

    public static $Error = array();

    protected static $UploadDirectory = 'userfiles/attractions/category/';
    public static $UploadPath, $UploadUrl;

    public function __construct()
    {
        global $config, $request, $app_path, $app_url;

        self::$table     = $config['db_prefix'] . self::$table;
        self::$table_i18 = $config['db_prefix'] . self::$table_i18;

        $this->post  = (!empty($request->post) ? $request->post : null);
        $this->get   = (!empty($request->get) ? $request->get : null);
        $this->files = (!empty($request->files) ? $request->files : null);

        self::$UploadPath = $app_path . self::$UploadDirectory;
        self::$UploadUrl  = $app_url . self::$UploadDirectory;

        $this->install();
        $this->register();
    }

    private function register()
    {
        Navigation::submenu('attractions' , 'Kategorie' , 'attractions/category/list');
        Kernel::registerComponent( 102 , "Atrakcje - kategorie" , "attractions/category/index");
    }

    private function install()
    {
        System::create_dir(self::$UploadPath);
        System::create_dir(self::$UploadPath . 'thumbs/');

        if (Db::has_table(self::$table) == false) {
            Db::install("CREATE TABLE " . self::$table . " ( `category_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `visibility` TINYINT(1) UNSIGNED NOT NULL , `create_date` DATETIME NOT NULL , `edit_date` DATETIME NULL , `photo` VARCHAR(200) NULL , PRIMARY KEY (`category_id`)) ENGINE = InnoDB;");
        }

        if (Db::has_table(self::$table_i18) == false) {
            Db::install("CREATE TABLE " . self::$table_i18 . " (`language_id` int(11) UNSIGNED NOT NULL,`category_id` int(11) UNSIGNED NOT NULL,`language` varchar(4) NOT NULL,`name` varchar(200) NOT NULL,`description` text NOT NULL,`meta_title` varchar(250) NOT NULL,`meta_description` text NOT NULL,`meta_index` tinyint(1) NOT NULL,`meta_follow` tinyint(1) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
            Db::install("ALTER TABLE " . self::$table_i18 . " ADD PRIMARY KEY (`language_id`), ADD KEY `category_id` (`category_id`,`language`);");
            Db::install("ALTER TABLE " . self::$table_i18 . " MODIFY `language_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;");
        }
    }

    protected function parse($d, $edit = false)
    {
        if (true == $edit) {
            $d['edit'] = true;
        }

        $d['name']    = html_entity_decode($d['name']);
        if(!empty($d['description'])) {
            $d['description'] = html_entity_decode($d['description']);
        }
        if( !empty( $d['meta_title'] )) {
            $d['meta_title']    = html_entity_decode($d['meta_title']);
        }
        if( !empty( $d['meta_description'] )) {
            $d['meta_description']    = html_entity_decode($d['meta_description']);
        }

        if (!empty($d['photo']) && System::file_exists(self::$UploadPath . $d['photo']) == true) {
            $photo = $d['photo'];

            unset($d['photo']);

            $d['photo']['thumb'] = self::$UploadUrl . 'thumbs/' . $photo;
            $d['photo']['url']   = self::$UploadUrl . $photo;
        }

        if( empty( $d['photo'] )) {
            $d['photo']['thumb'] = self::$UploadUrl . 'blank.png';
            $d['photo']['url'] = self::$UploadUrl . 'blank.png';
            $d['blank'] = true;
        }

        return $d;
    }

    public function get_list()
    {
        $r = Db::query(
            "SELECT a.*, i18.name, i18.description, i18.meta_title, i18.meta_description, i18.meta_index, i18.meta_follow FROM " . self::$table . " AS a RIGHT JOIN
            " . self::$table_i18 . " AS i18 ON i18.category_id=a.category_id
            WHERE i18.language='" . Language::get_selected() . "'
            ORDER BY create_date DESC"
        );
        if (empty($r)) {
            return;
        }

        foreach ($r as $k => $i) {
            $r[$k] = $this->parse($i);
        }

        return $r;
    }

    public function get_select()
    {
        $r = Db::query(
            "SELECT a.category_id as id, i18.name as name FROM " . self::$table . " AS a RIGHT JOIN
            " . self::$table_i18 . " AS i18 ON i18.category_id=a.category_id
            WHERE i18.language='" . Language::get_selected() . "' AND a.visibility = 1
            ORDER BY create_date DESC"
        );
        if (empty($r)) {
            return;
        }

        foreach ($r as $k => $i) {
            $r[$k] = $this->parse($i);
        }

        return $r;
    }

    public function get_row($id, $edit = false)
    {
        $r = Db::query_row(
            "SELECT a.*, i18.name, i18.description, i18.meta_title, i18.meta_description, i18.meta_index, i18.meta_follow FROM " . self::$table . " AS a RIGHT JOIN
            " . self::$table_i18 . " AS i18 ON i18.category_id=a.category_id
            WHERE a.category_id='" . $id . "' AND i18.language = '" . Language::get_selected() . "'
            ORDER BY create_date DESC"
        );
        if (empty($r)) {
            return;
        }

        return $this->parse($r, $edit);
    }

    public function verify($edit = false)
    {
        if (empty($this->post['name'])) {
            self::$Error[] = "Musisz podać nazwę kategorii";
        }
    }

    public function create()
    {
        $this->verify();
        if (!empty(self::$Error)) {
            Kernel::setMessage("ERROR", LA::get('cms', 'error_form_return_error'));
            return false;
        }

        if (!empty($this->files['photo']['name'])) {
            $photo = System::upload($this->files['photo'], [
                'upload_dir' => self::$UploadPath,
                'thumbs'     => true,
                'convert'    => true,
            ]);
        }

        $r = Db::insert(self::$table, "null,
        '" . $this->post['visibility'] . "'," .
            (!empty($this->post['create_date']) ? "'" . $this->post['create_date'] . "'" : "NOW()") . ",
            NULL," .
            (!empty($photo) ? "'" . $photo . "'" : "NULL"));

        if (false == $r) {
            Kernel::setMessage("ERROR", LA::get('cms', 'error_db_return_error'), Db::error());
            return false;
        }

        $category_id = Db::insert_id();

        $meta_title = (!empty($this->post['meta_title']) ? $this->post['meta_title'] : $this->post['name']);
        $meta_description = (!empty($this->post['meta_description']) ? $this->post['meta_description'] : $this->post['description'] );

        foreach (Language::$available as $language => $i) {
            $r = Db::insert(self::$table_i18, "NULL,
            '" . $category_id . "',
            '" . $language . "',
            '" . addslashes($this->post['name']) . "',
            '" . addslashes($this->post['description']) . "',
            '" . $meta_title . "',
            '" . $meta_description . "',
            " . (!empty( $this->post['meta_index']) ? "1" : "0") . ",
            " . (!empty( $this->post['meta_follow']) ? "1" : "0"));

            if (false == $r) {
                Kernel::setMessage("ERROR", LA::get('cms', 'error_db_return_error'), Db::error());
                return false;
            }
        }

        Kernel::setMessage("NOTICE", LA::get('cms', 'add_notice_success'));
        return true;
    }

    public function update($category_id)
    {
        $this->verify(true);
        if (!empty(self::$Error)) {
            Kernel::setMessage("ERROR", LA::get('cms', 'error_form_return_error'));
            return false;
        }

        if (!empty($this->post['delete_photo'])) {
            $this->delete_photo($category_id);
        }

        if (!empty($this->files['photo']['name'])) {
            $photo = System::upload($this->files['photo'], [
                'upload_dir' => self::$UploadPath,
                'thumbs'     => true,
                'convert'    => true,
            ]);

            if (!empty($photo)) {
                Db::update(self::$table, "photo='" . $photo .  "'", "category_id='" . $category_id . "'");
            }
        }

        $r = Db::update(self::$table, "visibility = '" . $this->post['visibility'] . "',
            create_date = " . (!empty($this->post['create_date']) ? "'" . $this->post['create_date'] . "'" : "NOW()") . ",
            edit_date = NOW()", "category_id='" . $category_id . "'");

        if (false == $r) {
            Kernel::setMessage("ERROR", LA::get('cms', 'error_db_return_error'), Db::error());
            return false;
        }

        $meta_title = (!empty($this->post['meta_title']) ? $this->post['meta_title'] : $this->post['name']);
        $meta_description = (!empty($this->post['meta_description']) ? $this->post['meta_description'] : $this->post['description'] );

        $r = Db::update(self::$table_i18, "name = '" . addslashes( $this->post['name'] ) . "',
            description = '" . addslashes( $this->post['description'] ) . "',
            meta_title = '" . $meta_title . "',
            meta_description = '" . $meta_description . "',
            meta_index = " . (!empty( $this->post['meta_index'] ) ? "1" : "0") . ",
            meta_follow = " . (!empty( $this->post['meta_follow'] ) ? "1" : "0") , "category_id='" . $category_id . "' AND language='" . Language::get_selected() . "'");

        if (false == $r) {
            Kernel::setMessage("ERROR", LA::get('cms', 'error_db_return_error'), Db::error());
            return false;
        }

        Kernel::setMessage("NOTICE", LA::get('cms', 'update_notice_success'));
        return true;
    }

    public function delete( $category_id )
    {
        $this->delete_photo( $category_id );

        Db::delete(self::$table, "category_id='" . $category_id . "'");
        Db::delete(self::$table_i18, "category_id='" . $category_id . "'");

        Kernel::setMessage("NOTICE", LA::get('cms', 'delete_notice_success'));
        return true;
    }

    public function delete_photo($category_id)
    {
        $r = Db::row("photo", self::$table, "WHERE category_id='" . $category_id . "'");
        if (!empty($r['photo'])) {

            System::delete_file(self::$UploadPath . $r['photo']);
            System::delete_file(self::$UploadPath . 'thumbs/' . $r['photo']);

            Db::update( self::$table , "photo = NULL" , "category_id='" . $category_id . "'");
            return true;
        }
    }
}
