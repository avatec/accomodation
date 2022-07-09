<?php
use \Core\Backend\Navigation as Navigation;

/**
 * Objects video class
 *
 * @package        Modules
 * @subpackage    Objects/Videos
 * @author        Grzegorz Miskiewicz <biuro@avatec.pl>
 * @license        Property license
 *
 * <p>Ten produkt jest licencjonowany
 * Możesz modyfikować te pliki jednak nie możesz usuwać oryginalnych komentarzy
 * w szczególności informacji o autorze tego oprogramowania</p>
 *
 * <p>W przypadku indywidualnego modyfikowania tego pliku autor nie ponosi
 * odpowiedzialności za wszelkie błędy i/lub wady tego oprogramowania.</p>
 */

class ObjectsVideos
{

    public static $table = "objects_videos";
    protected static $uid;
    public static $Error;

    public function __construct()
    {
        global $config;

        self::$table = $config['db_prefix'] . self::$table;
    }

    public function getByObject($object_id, $for_user = true)
    {
        if (!empty(User::$admin)) {
            self::$uid = User::$admin['id'];
            return Db::exec("*", self::$table, "WHERE object_id='" . $object_id . "'");
        } else {
            self::$uid = User::$user['id'];
            return Db::exec("*", self::$table, "WHERE object_id='" . $object_id . "'" . ((true == $for_user) ? " AND uid='" . self::$uid . "'" : ""));
        }
    }

    public function get($id = null)
    {
        if (is_null($id)) {
            return Db::exec("*", self::$table, "ORDER BY id");
        } else {
            $Result         = Db::row("*", self::$table, "WHERE id='" . $id . "'");
            $Result['edit'] = true;
            return $Result;
        }
    }

    public function verify()
    {
        global $request;

        if (empty($request->post['link'])) {
            self::$Error[] = "wprowadź prawidłowy link do filmu z youtube";
        }
    }

    public function add()
    {
        global $app_path, $config, $request;

        $link = self::convertYoutube($request->post['link']);
        $this->verify();
        if (!empty(self::$Error)) {
            Kernel::setMessage("ERROR", "Wykryto błędy w formularzu:<br/>" . implode("<br/>", self::$Error));
            return false;
        }

        if (!empty(User::$admin)) {
            self::$uid = User::$admin['id'];
        } else {
            self::$uid = User::$user['id'];
        }

        $check = Db::check(self::$table, "uid='" . self::$uid . "' AND object_id='" . $request->post['object_id'] . "' AND link='" . $link . "'");

        if (false == $check) {
            $Result = Db::insert(self::$table, "null,
			'" . self::$uid . "',
			'" . $request->post['object_id'] . "',
			'" . $link . "',
			NOW()");

            if (true == $Result) {
                Kernel::setMessage("NOTICE", "Pomyślnie dodano nowy film wideo");
                if ("TRUE" == $config['announcement_moderate']) {
                    Objects::setStatus($request->post['object_id'], "FALSE");
                }
                return true;
            } else {
                self::$Error = Db::error();
                Kernel::setMessage("ERROR", "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
                return false;
            }
        } else {
            self::$Error = "File exists";
        }
    }

    public static function deleteByObject($object_id)
    {
        $Result = Db::exec("*", self::$table, "WHERE object_id='" . $object_id . "'");
        if (!empty($Result)) {
            foreach ($Result as $k => $i) {
                self::delete($i['id']);
            }
        }
    }

    public static function delete($id)
    {
        if (Db::check(self::$table, "id='" . $id . "'") == true) {
            Db::delete(self::$table, "id= '" . $id . "'");
            Kernel::setMessage("NOTICE", "Pomyślnie usunięto film wideo");
            return true;
        } else {
            self::$Error = Db::error();
            Kernel::setMessage("ERROR", "Wystąpił błąd podczas operacji w bazie danych: " . self::$Error);
            return false;
        }
    }

    public static function hasVideo($object_id)
    {
        return Db::check(self::$table, "object_id='" . $object_id . "'");
    }

    public static function convertYoutube($link)
    {
        preg_match("/\?v\=([a-zA-Z0-9_-]+)/", $link, $matches);
        if (isset($matches['1'])) {
            return $matches['1'];
        } else {
            self::$Error[] = "Wystąpił błąd podczas próby dodawania linku wideo - skontaktuj się z administratorem serwisu i podaj ten link: " . $link;
        }
    }

    public static function howManyCanAdd($object_id)
    {
        global $config;
        $photos_for_object = self::countVideos($object_id);
        $result            = (int) $config['announcement_max_videos'] - (int) $photos_for_object;
        return (int) $result;
    }

    public static function canUploadPhotos($object_id)
    {
        if (self::howManyCanAdd($object_id) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function countVideos($object_id)
    {
        return Db::count(self::$table, "object_id='" . $object_id . "'");
    }
}
