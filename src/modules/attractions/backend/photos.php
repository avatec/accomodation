<?php namespace Modules\Attractions\Backend;

/**
 *  attractions module - category backend model
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
use \Modules\Admins\Backend\Admins;

class Photos
{
    public static $table     = "attractions_photos";
    public static $table_i18 = "attractions_photos_i18";

    public static $Error = array();

    protected static $UploadDirectory = 'userfiles/attractions/photos/';
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

    }

    private function install()
    {
        System::create_dir(self::$UploadPath);
        System::create_dir(self::$UploadPath . 'thumbs/');

        if (Db::has_table(self::$table) == false) {
            Db::install("CREATE TABLE " . self::$table . " (`photo_id` int(11) NOT NULL,`attractions_id` int(11) NOT NULL,`visibility` tinyint(1) NOT NULL,`priority` int(11) NOT NULL,`photo` varchar(200) NOT NULL,`create_date` date NOT NULL,`edit_date` date DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
            Db::install("ALTER TABLE " . self::$table . " ADD PRIMARY KEY (`photo_id`);");
            Db::install("ALTER TABLE " . self::$table . " MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT;");
        }

        if (Db::has_table(self::$table_i18) == false) {
            Db::install("CREATE TABLE " . self::$table_i18 . " (`language_id` int(11) NOT NULL,`photo_id` int(11) NOT NULL,`language` varchar(10) NOT NULL,`alt` varchar(200) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
            Db::install("ALTER TABLE " . self::$table_i18 . " ADD PRIMARY KEY (`language_id`), ADD KEY `attractions_id` (`photo_id`);");
            Db::install("ALTER TABLE " . self::$table_i18 . " MODIFY `language_id` int(11) NOT NULL AUTO_INCREMENT;");
        }
    }

    protected function parse($d, $edit = false)
    {
        if (true == $edit) {
            $d['edit'] = true;
        }

        $d['alt']    = html_entity_decode($d['alt']);

        if( !empty( $d['photo'] )) {
            $d['photo_url'] = self::$UploadUrl . $d['photo'];
            $d['photo_thumb_url'] = self::$UploadUrl . 'thumbs/' . $d['photo'];
        }

        return $d;
    }

    public function get_list( $attractions_id )
    {
        $r = Db::query(
            "SELECT p.*, i18.alt FROM " . self::$table . " AS p RIGHT JOIN " . self::$table_i18 . " AS i18 ON i18.photo_id=p.photo_id
            WHERE i18.language='" . Language::get_selected() . "' AND p.attractions_id='" . $attractions_id . "'
            ORDER BY p.create_date DESC"
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
            "SELECT p.*, i18.alt FROM " . self::$table . " AS p RIGHT JOIN
            " . self::$table_i18 . " AS i18 ON i18.photo_id=p.photo_id
            WHERE p.photo_id='" . $id . "' AND i18.language = '" . Language::get_selected() . "'"
        );
        if (empty($r)) {
            return;
        }

        return $this->parse($r, $edit);
    }

    public function update( $attractions_id )
    {
        if(!empty($this->post['priority'])) {
            foreach( $this->post['priority'] as $id=>$value ) {
                Db::update( self::$table , "priority='" . $value . "'" , "photo_id='" . $id . "'");
            }
            Kernel::setMessage("NOTICE" , "Zmiany zostały zapisane");
        }

        if(!empty($this->post['alt'])) {
            foreach( $this->post['alt'] as $id=>$value ) {
                Db::update( self::$table_i18 , "alt='" . $value . "'" , "photo_id='" . $value . "' AND language='" . Language::get_selected() . "'");
            }
            Kernel::setMessage("NOTICE" , "Zmiany zostały zapisane");
        }

        if(!empty($this->post['delete'])) {
            foreach( $this->post['delete'] as $id=>$file ) {
                $this->delete( $id );
            }
            Kernel::setMessage("NOTICE" , "Zmiany zostały zapisane");
        }
    }

/**
 *  Removing photos from database and server
 *  @param (int) $photo_id
 *  @return (bool)
 */

    public function delete( $photo_id )
    {
        $row = $this->get_row( $photo_id );
        if( System::file_exists( self::$UploadPath . $row['photo'] ) == true ) {
            System::delete_file( self::$UploadPath . $row['photo'] );
        }

        if( System::file_exists( self::$UploadPath . 'thumbs/' . $row['photo'] ) == true ) {
            System::delete_file( self::$UploadPath . 'thumbs/' . $row['photo'] );
        }

        Db::delete(self::$table, "photo_id='" . $photo_id . "'");
        Db::delete(self::$table_i18, "photo_id='" . $photo_id . "'");

        Kernel::setMessage("NOTICE", LA::get('cms', 'delete_notice_success'));
        return true;
    }

/**
 *  Uploading file method
 *  @param (array)  file
 *  @param (int)    post['attractions_id']
 *  @return (array)
 */

    public static function photo_uploader( $file )
    {
        ini_set('display_errors' , 1);
        error_reporting(E_ALL);

        global $route, $request;

        if(empty($file['name'])) {
			return [ 'error' => true, 'msg' => 'File not found' ];
		}

        if(empty($request->post['attractions_id'])) {
            return ['error' => true, 'msg' => 'attractions_id not found'];
        }

        Admins::restore();

        if( empty( Admins::$auth['id'] )) {
            return [ 'error' => true, 'msg' => 'Auth failed' ];
        }

		$uploaded_file = System::upload( $file, [
			'upload_dir' => self::$UploadPath,
			'convert' => true,
			'thumbs' => true,
			'thumb_width' => 768,
			'thumb_height' => 768,
			'resize' => true,
			'resize_width' => 1680,
			'allowed' => 'image/*'
		]);


		if(!empty($uploaded_file)) {
			$r = Db::row("priority" , self::$table , "WHERE attractions_id='" . $request->post['attractions_id'] . "' ORDER BY priority DESC");
			if(empty($r['priority'])) {
				$priority = 0;
			} else {
				$priority = ++$r['priority'];
			}

			$result = Db::insert( self::$table , "null,
            '" . $request->post['attractions_id'] . "',
            1,
			'" . $priority . "',
			'" . $uploaded_file . "',
			NOW(),
            NULL");

            if( $result == true ) {
                $photo_id = Db::insert_id();

                foreach( Language::$available as $lang=>$item ) {
                    Db::insert( self::$table_i18 , "null,
                    '" . $photo_id . "',
                    '" . $lang . "',
                    ''");
                }

                return [
    				'success' => true,
    				'filename' => $uploaded_file,
    				'photo_url' => self::$UploadUrl . $uploaded_file,
    				'thumb_url' => self::$UploadUrl . 'thumbs/' . $uploaded_file,
    			];
            }

            return ['error' => true, 'msg' => 'Error while executing db query', 'debug' => Db::error(), 'request' => print_r($request, true)];
		}

        return ['error' => true, 'msg' => 'Unknown error while uploading file'];
    }
}
