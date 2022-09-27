<?php

namespace Modules\Admins\Backend;

use Core\Db;
use Core\Kernel;
use Core\Logs;
use Core\LanguageBackend;
use Modules\Admins\Backend\Admins as Admins;

/**
 *  Klasa obsługi tokenów administratorów
 *  @author Grzegorz Miśkiewicz <biuro@avatec.pl>
 *  @version 1.0
 */

class Tokens
{
    public static $table = "admins_tokens";
    public static $expire = 3600;

    public function __construct()
    {
        $this->install();
        self::remove_expired();

        self::$expire = ini_get('session.gc_maxlifetime');
    }

    protected function install()
    {
        if (Db::has_table(self::$table) == false) {
            Db::install("CREATE TABLE " . self::$table . " (`uid` int(11) NOT NULL,`session_id` varchar(128) NOT NULL,`token` varchar(128) NOT NULL,`expire` varchar(128) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
            Db::install("ALTER TABLE " . self::$table . " ADD KEY `uid` (`uid`), ADD KEY `token` (`session_id`);");
            Kernel::setMessage("INFO", LA::get('addons', 'addons_module'), "Logos", LA::get('addons', 'addons_install'));
            return true;
        }

        return false;
    }

    public static function validate($token)
    {
        $r = Db::row("uid", "cms_admins_tokens", "WHERE token='" . $token . "' AND expire>='" . time() . "'");
        if (empty($r)) {
            return false;
        }

        return true;
    }

    public static function restore($token)
    {
        $r = Db::row("uid", "cms_admins_tokens", "WHERE token='" . $token . "' AND expire>='" . time() . "'");
        if (empty($r)) {
            return;
        }

        return $r['uid'];
    }

    public static function create($uid, $token_length = 32)
    {
        $token = self::token($token_length);

        $r = Db::insert(self::$table, "'" . $uid . "','" . session_id() . "','" . $token . "','" . (time() + self::$expire) . "'");
        if ($r == true) {
            return $token;
        }

        return false;
    }

    public static function update($token)
    {
        if (!empty(Admins::$auth['id'])) {
            // Core\Logs::create('tokens.log', 'zalogowany ... Session_id: ' . session_id() . ' i token: ' . $token);
            $expire = (time() + self::$expire);
            $r = Db::update(self::$table, "expire='" . $expire . "'", "token='" . $token . "'");
            if ($r == true) {
                Admins::$auth['expire'] = $expire;
                return true;
            }
        }

        // Core\Logs::create('tokens.log', 'Session_id: ' . session_id() . ' i token: ' . $token);
        if (Db::check(self::$table, "session_id='" . session_id() . "' AND token='" . $token . "'") == true) {
            $expire = (time() + self::$expire);
            $r = Db::update(self::$table, "expire='" . $expire . "'", "token='" . $token . "'");
            if ($r == true) {
                Admins::$auth['expire'] = $expire;
                return true;
            }
        } else {
            // Core\Logs::create('tokens.log', 'Prawdopodobnie zmieniło się session_id: ' . session_id() . ' / token: ' . $token);
        }

        return false;
    }

    protected static function remove_expired()
    {
        if (Db::delete(self::$table, "expire<'" . time() . "'") == true) {
            return true;
        }

        return false;
    }

    public static function remove_all($uid)
    {
        Db::delete(self::$table, "uid='" . $uid . "'");
    }

    protected static function token($bytes = 64)
    {
        return bin2hex(openssl_random_pseudo_bytes($bytes));
    }
}
