<?php namespace Modules\Attractions\Backend;

/**
 *  attractions module - backend model
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
use \ObjectsStates;
use \Core\Backend\Navigation as Navigation;

class Attractions
{
    public static $table     = "attractions";
    public static $table_i18 = "attractions_i18";

    public static $Error = array();

    protected static $UploadDirectory = 'userfiles/attractions/attractions/';
    public static $UploadPath;
    public static $UploadUrl;

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
        Navigation::menu(101, 'attractions', 'Atrakcje', null, 'fa-newspaper-o');
        Navigation::submenu('attractions', 'Punkty', 'attractions/list');

        Kernel::registerComponent(101, "Atrakcje", "attractions/index");
    }

    private function install()
    {
        System::create_dir(self::$UploadPath);
        System::create_dir(self::$UploadPath . 'thumbs/');

        if (Db::has_table(self::$table) == false) {
            Db::install("CREATE TABLE " . self::$table . " ( `attractions_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `visibility` TINYINT(1) UNSIGNED NOT NULL , `category_id` INT(11) NULL , `create_date` DATETIME NOT NULL , `edit_date` DATETIME NULL , `photo` VARCHAR(200) NULL , PRIMARY KEY (`attractions_id`)) ENGINE = InnoDB;");
        }

        if (Db::has_table(self::$table_i18) == false) {
            Db::install("CREATE TABLE " . self::$table_i18 . " (`language_id` int(11) UNSIGNED NOT NULL,`attractions_id` int(11) UNSIGNED NOT NULL,`language` varchar(4) NOT NULL,`name` varchar(200) NOT NULL,`address` varchar(250) NOT NULL,`postcode` varchar(10) NOT NULL,`city` varchar(250) NOT NULL,`state_id` int(11) NULL, `phone` varchar(250) DEFAULT NULL,`www` varchar(250) DEFAULT NULL,`open_hours` varchar(250) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
            Db::install("ALTER TABLE " . self::$table_i18 . " ADD PRIMARY KEY (`language_id`), ADD KEY `attractions_id` (`attractions_id`,`language`);");
            Db::install("ALTER TABLE " . self::$table_i18 . " MODIFY `language_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;");
        }
    }

    protected function parse($d, $edit = false)
    {
        if (true == $edit) {
            $d['edit'] = true;
        }

        $d['name']          = html_entity_decode($d['name']);
        $d['description']   = html_entity_decode($d['description']);
        $d['prices']        = html_entity_decode($d['prices']);
        $d['address']       = html_entity_decode($d['address']);
        $d['city']          = html_entity_decode($d['city']);
        $d['www']           = html_entity_decode($d['www']);
        $d['open_hours']    = html_entity_decode($d['open_hours']);

        if (!empty($d['photo']) && System::file_exists(self::$UploadPath . $d['photo']) == true) {
            $photo = $d['photo'];

            unset($d['photo']);

            $d['photo']['thumb'] = self::$UploadUrl . 'thumbs/' . $photo;
            $d['photo']['url']   = self::$UploadUrl . $photo;
        }

        if (empty($d['photo'])) {
            $d['photo']['thumb'] = self::$UploadUrl . 'blank.png';
            $d['photo']['url'] = self::$UploadUrl . 'blank.png';
            $d['blank'] = true;
        }

        return $d;
    }

    public function get_list()
    {
        $r = Db::query(
            "SELECT a.*, i18.name, i18.description, i18.prices, i18.address, i18.postcode, i18.city, i18.state_id, i18.phone, i18.email, i18.www, i18.open_hours
            FROM " . self::$table . " AS a RIGHT JOIN " . self::$table_i18 . " AS i18 ON i18.attractions_id=a.attractions_id
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

    public function get_row($id, $edit = false)
    {
        $r = Db::query_row(
            "SELECT a.*, i18.name, i18.description, i18.prices, i18.address, i18.postcode, i18.city, i18.state_id, i18.phone, i18.email, i18.www, i18.open_hours
            FROM " . self::$table . " AS a RIGHT JOIN " . self::$table_i18 . " AS i18 ON i18.attractions_id=a.attractions_id
            WHERE a.attractions_id='" . $id . "' AND i18.language = '" . Language::get_selected() . "'
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
            self::$Error[] = "Musisz podać nazwę punktu";
        }

        if (empty($this->post['address'])) {
            self::$Error[] = "Musisz podać adres";
        }

        if (empty($this->post['postcode'])) {
            self::$Error[] = "Musisz podać kod pocztowy";
        }

        if (empty($this->post['city'])) {
            self::$Error[] = "Musisz podać miejscowość";
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
            (!empty($this->post['category_id']) ? $this->post['category_id'] : "NULL") . "," .
            (!empty($this->post['create_date']) ? "'" . $this->post['create_date'] . "'" : "NOW()") . ",
            NULL," .
            (!empty($photo) ? "'" . $photo . "'" : "NULL"));

        if (false == $r) {
            Kernel::setMessage("ERROR", LA::get('cms', 'error_db_return_error'), Db::error());
            return false;
        }

        $attractions_id = Db::insert_id();

        $meta_title = (!empty($this->post['meta_title']) ? $this->post['meta_title'] : $this->post['name']);
        $meta_description = (!empty($this->post['meta_description']) ? $this->post['meta_description'] : $this->post['description']);

        foreach (Language::$available as $language => $i) {
            $r = Db::insert(self::$table_i18, "NULL,
            '" . $attractions_id . "',
            '" . $language . "',
            '" . addslashes($this->post['name']) . "',
            '" . addslashes($this->post['description']) . "',
            '" . addslashes($this->post['prices']) . "',
            '" . $this->post['address'] . "',
            '" . $this->post['postcode'] . "',
            '" . $this->post['city'] . "',
            '" . $this->post['state_id'] . "',
            " . (!empty($this->post['phone']) ? "'" . $this->post['phone'] . "'" : "NULL") . ",
            " . (!empty($this->post['email']) ? "'" . $this->post['email'] . "'" : "NULL") . ",
            " . (!empty($this->post['www']) ? "'" . $this->post['www'] . "'" : "NULL") . ",
            " . (!empty($this->post['open_hours']) ? "'" . $this->post['open_hours'] . "'" : "NULL") . ",
            '" . $meta_title . "',
            '" . $meta_description . "',
            " . (!empty($this->post['meta_index']) ? 1 : 0) . ",
            " . (!empty($this->post['meta_follow']) ? 1 : 0));

            if (false == $r) {
                Kernel::setMessage("ERROR", LA::get('cms', 'error_db_return_error'), Db::error());
                return false;
            }
        }

        Kernel::setMessage("NOTICE", LA::get('cms', 'add_notice_success'));
        return true;
    }

    public function update($attractions_id)
    {
        $this->verify(true);
        if (!empty(self::$Error)) {
            Kernel::setMessage("ERROR", LA::get('cms', 'error_form_return_error'));
            return false;
        }

        if (!empty($this->post['delete_photo'])) {
            $this->delete_photo($attractions_id);
        }

        if (!empty($this->files['photo']['name'])) {
            $photo = System::upload($this->files['photo'], [
                'upload_dir' => self::$UploadPath,
                'thumbs'     => true,
                'convert'    => true,
            ]);

            if (!empty($photo)) {
                Db::update(self::$table, "photo='" . $photo .  "'", "attractions_id='" . $attractions_id . "'");
            }
        }

        $r = Db::update(self::$table, "visibility = '" . $this->post['visibility'] . "'" .
            (!empty($this->post['category_id']) ? ",category_id = '" . $this->post['category_id'] . "'" : "") . ",
            create_date = " . (!empty($this->post['create_date']) ? "'" . $this->post['create_date'] . "'" : "NOW()") . ",
            edit_date = NOW()", "attractions_id='" . $attractions_id . "'");

        if (false == $r) {
            Kernel::setMessage("ERROR", LA::get('cms', 'error_db_return_error'), Db::error());
            return false;
        }

        $meta_title = (!empty($this->post['meta_title']) ? $this->post['meta_title'] : $this->post['name']);
        $meta_description = (!empty($this->post['meta_description']) ? $this->post['meta_description'] : $this->post['description']);

        $r = Db::update(self::$table_i18, "name = '" . addslashes($this->post['name']) . "',
            description = '" .addslashes( $this->post['description'] ) . "',
            prices = '" . addslashes( $this->post['prices'] ) . "',
            address = '" . $this->post['address'] . "',
            postcode = '" . $this->post['postcode'] . "',
            city = '" . $this->post['city'] . "',
            state_id = '" . $this->post['state_id'] . "',
            phone = " . (!empty($this->post['phone']) ? "'" . $this->post['phone'] . "'" : "NULL") . ",
            email = " . (!empty($this->post['email']) ? "'" . $this->post['email'] . "'" : "NULL") . ",
            www = " . (!empty($this->post['www']) ? "'" . $this->post['www'] . "'" : "NULL") . ",
            open_hours = " . (!empty($this->post['open_hours']) ? "'" . $this->post['open_hours'] . "'" : "NULL") . ",
            meta_title = '" . $meta_title . "',
            meta_description = '" . $meta_description . "',
            meta_index = " . (!empty($this->post['meta_index']) ? 1 : 0 ) . ",
            meta_follow = " . (!empty( $this->post['meta_follow']) ? 1 : 0) , "attractions_id='" . $attractions_id . "' AND language='" . Language::get_selected() . "'");

        if (false == $r) {
            Kernel::setMessage("ERROR", LA::get('cms', 'error_db_return_error'), Db::error());
            return false;
        }

        Kernel::setMessage("NOTICE", LA::get('cms', 'update_notice_success'));
        return true;
    }

    public function delete($attractions_id)
    {
        $this->delete_photo($attractions_id);

        Db::delete(self::$table, "attractions_id='" . $attractions_id . "'");
        Db::delete(self::$table_i18, "attractions_id='" . $attractions_id . "'");

        Kernel::setMessage("NOTICE", LA::get('cms', 'delete_notice_success'));
        return true;
    }

    public function delete_photo($attractions_id)
    {
        $r = Db::row("photo", self::$table, "WHERE attractions_id='" . $attractions_id . "'");
        if (!empty($r['photo'])) {
            System::delete_file(self::$UploadPath . $r['photo']);
            System::delete_file(self::$UploadPath . 'thumbs/' . $r['photo']);

            Db::update(self::$table, "photo = NULL", "attractions_id='" . $attractions_id . "'");
            return true;
        }
    }
}
