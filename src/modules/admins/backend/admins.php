<?php

namespace Modules\Admins\Backend;

use Core\Db;
use Core\Mail;
use Core\Files;
use Core\Logs;
use Core\Common;
use Core\Kernel;
use Core\Paginate;
use Core\Backend\Model;
use Core\Backend\Messages;
use Core\LanguageBackend as LA;
use Modules\System\Backend\Emails;
use Core\Backend\Navigation as Navigation;

/**
  * Klasa Admins dla Avatec Framework
  *
  * @author: Grzegorz Miskiewicz <biuro@avatec.pl>
  * @package: Avatec Framework
  *
  * Ten produkt jest licencjonowany
  * Możesz modyfikować te pliki jednak nie możesz usuwać oryginalnych komentarzy
  * w szczególności informacji o autorze tego oprogramowania
  *
  * W przypadku indywidualnego modyfikowania tego pliku autor nie ponosi
  * odpowiedzialności za wszelkie błędy i/lub wady tego oprogramowania.
 */

class Admins extends Model
{
    public static $table = "admins";
    public static $auth = array();
    public static $Error = array();

    public static $UploadDir = 'userfiles/admins/';
    public static $UploadPath;
    public static $UploadUrl;

    public static $types = [
        ["id" => -1, "name" => "Support"],
        ["id" => 1, "name" => "Administrator"],
        ["id" => 2, "name" => "Moderator"]
    ];

    public $post;
    public $get;

    public function __construct()
    {
        parent::__construct();

        global $config, $admin_folder;

        LA::load('admins');
        self::$types = LA::get('admins', 'admins_types');

        Navigation::submenu('advanced', LA::get('admins', 'admins_menu'), 'admins/list/');

        self::restore();

        global $app_url, $app_path;
        self::$UploadPath = $app_path . self::$UploadDir;
        self::$UploadUrl = $app_url . self::$UploadDir;
    }

    protected function parse($d, $edit = false)
    {
        if ($edit == true) {
            $d['edit'] = true;
        }

        return $d;
    }

/**
 *  Zwraca szczegółowe informacje o kontakcie
 *  @param int $user_id
 *  @return array
 */

    public function getInfo(int $id): array
    {
        $r = Db::query_row("SELECT name as full_name, email, type
        FROM " . self::$table . " WHERE id='" . $id . "'");

        if (empty($r)) {
            return [];
        }

        return $r;
    }

    public static function get_name($id)
    {
        $r = Db::row("name", self::$table, "WHERE id='" . $id . "'");
        if (empty($r['name'])) {
            return 'id konta #' . $id;
        }

        return $r['name'];
    }

    public function get_row($id, $edit = false)
    {
        $r = Db::row("*", self::$table, "WHERE id='" . $id . "'");
        if (empty($r)) {
            return;
        }

        return $this->parse($r, $edit);
    }

/**
 *	Zwraca listę do selecta na podstawie typu konta
 *	@param int $type
 *  @return array
 */

    public function get_select(int $type): array
    {
        $r = Db::query("SELECT id, name FROM " . self::$table . " WHERE type='" . $type . "' AND state='TRUE'");
        if (empty($r)) {
            return [];
        }

        return $r;
    }

    public function get_list()
    {
        if (!empty($this->get['q'])) {
            $q[] = "(id LIKE '%" . $this->get['q'] . "%' OR name LIKE '%" . $this->get['q'] . "%' OR email LIKE '%" . $this->get['q'] . "%' OR login LIKE '%" . $this->get['q'] . "%')";
        }
        if (!empty($this->get['t'])) {
            $q[] = "type='" . $this->get['t'] . "'";
        }

        Paginate::$query = "SELECT * FROM " . self::$table . (!empty($q) ? " WHERE " . implode(" AND ", $q) : "") . " ORDER BY id";
        Paginate::$perpage = 25;
        return Paginate::make();
    }

/**
 *	Obsługa akcji logowania
 *	@return (bool)
 */

    public function login($login = null, $password = null)
    {
        $login = (!empty($this->post['login']) ? $this->post['login'] : $login);
        $password = (!empty($this->post['password']) ? $this->post['password'] : $password);

        if (empty($login)) {
            self::$Error[] = "nie podano loginu";
        }

        if (empty($password)) {
            self::$Error[] = "nie podano hasła";
        }

        if (Db::check(self::$table, "login='" . $login . "'") == false) {
            self::$Error[] = "nie istnieje konto o podanym loginie";
        } else {
            if (Db::check(self::$table, "login='" . $login . "' AND state='TRUE'") == false) {
                self::$Error[] = "Konto nie jest aktywne. Skontaktuj się z administratorem w celu wyjaśnienia zainstniałej sytuacji.";
            }
        }

        if (!empty(self::$Error)) {
            $this->login_error();
            Messages::error(LA::get('cms', 'error_form_return_error'), self::$Error);
            return false;
        }

        $r = Db::row("password", self::$table, "WHERE login='" . $login . "'");

        if (password_verify($password, $r['password']) == false) {
            Db::update(self::$table, "error_login_date = NOW(), error_login_ip = '" . Common::get_ip() . "'", "login='" . $login . "'");

            $this->login_error();

            self::$Error[] = "dane logowania się nie zgadzają";
            Messages::error(LA::get('cms', 'error_form_return_error'), self::$Error);
            return false;
        }

        Db::update(self::$table, "last_login_date = NOW()", "login='" . $login . "'");

        self::$auth = Db::row("id, login, name, email, state, type, last_login_date, error_login_date, error_login_ip, access,image", self::$table, "WHERE login='" . $login . "'");

        $token = \Modules\Admins\Backend\Tokens::create(self::$auth['id']);
        if ($token == false) {
            Messages::error("Sesja wygasła !", self::$Error);
            Logs::create("admins.log", "Sesja wygasła dla login: " . self::$auth['login'] . " adres IP: " . Common::get_ip());
            self::$auth = null;
            return false;
        }

        self::$auth['token'] = $token;

        Logs::create("admin_login_history.log", $login . " [" . Common::get_ip() . "] dnia " . date('Y-m-d') . " o godz. " . date('H:i:s'));

        self::store();
        Messages::success("Logowanie przebiegło pomyślnie");
        return true;
    }

    public function login_is_blocked()
    {
        global $app_path;
        if (Files::file_exists($app_path . 'cache/login.log') == false) {
            return false;
        }
        $json = file_get_contents($app_path . 'cache/login.log');
        if (empty($json)) {
            return false;
        }

        $t = json_decode($json, true);
        if (!empty($t)) {
            foreach ($t as $k=>$i) {
                if ($i['i'] == Common::get_ip() && $i['t'] >= time() && $i['c'] >= 3) {
                    return true;
                }
            }
        }

        return false;
    }

    private function login_error()
    {
        global $app_path;
        if (Files::file_exists($app_path . 'cache/login.log') == true) {
            $json = file_get_contents($app_path . 'cache/login.log');
            $t = json_decode($json, true);
            if (!empty($t)) {
                foreach ($t as $k=>$i) {
                    if ($i['i'] == Common::get_ip() && $i['t'] >= time()) {
                        $t[$k]['t'] = time() + 300;
                        $t[$k]['c'] = $t[$k]['c'] + 1;
                        $exists = true;
                    }

                    if ($i['t'] < time()) {
                        unset($t[$k]);
                    }
                }

                $t = array_filter($t);
            }
        }

        if (empty($exists)) {
            $t[] = [ 'i' => Common::get_ip(), 't' => time() + 300, 'c' => 1, 'd' => date('Y-m-d H:i:s') ];
        }

        file_put_contents($app_path . 'cache/login.log', json_encode($t));
    }

/**
 *	Obsługa akcji wylogowania użytkownika
 *	@return (bool)
 */
    public function logout()
    {
        if (!empty(self::$auth)) {
            self::$auth = null;
            self::store();
            unset($_SESSION['admin']);
            return true;
        }
        return false;
    }

    private function register_verify($edit = false)
    {
        if (empty($this->post['login'])) {
            self::$Error[] = "musisz podać login";
        }

        if (empty($this->post['name'])) {
            self::$Error[] = "musisz wpisać nazwę";
        }

        if (empty($this->post['email'])) {
            self::$Error[] = "musisz podać adres e-mail";
        }

        if (strlen($this->post['login']) < 3) {
            self::$Error[] = "login musi się składać z conajmniej 3 znaków";
        }

        if ($edit == false) {
            if (empty($this->post['password'])) {
                self::$Error[] = "musisz podać hasło";
            }

            if (strlen($this->post['password']) < 7) {
                self::$Error[] = "hasło musi się składać z minimum 7 znaków";
            }

            if (Db::check(self::$table, "login='" . $this->post['login'] . "'") == true) {
                self::$Error[] = "podany login jest już zajęty";
            }

            if (Db::check(self::$table, "email='" . $this->post['email'] . "'") == true) {
                self::$Error[] = "podany adres e-mail jest już zajęty";
            }
        }
    }

    public function add()
    {
        $this->register_verify();
        if (!empty(self::$Error)) {
            Messages::error(LA::get('cms', 'error_form_return_error'), self::$Error);
            return false;
        }

        if ($this->post['type'] == 3) {
            $access = 'label_9;dietary;discounts;';
        }

        $r = Db::insert(self::$table, "null,
		'" . $this->post['login'] . "',
		'" . $this->make_password($this->post['login'], $this->post['password']) . "',
		'" . $this->post['name'] . "',
		'" . $this->post['email'] . "',
		'" . $this->post['type'] . "',
		'FALSE',
		NOW(),
		NULL,
		NULL,
		NULL,
		" . (!empty($access) ? "'" . $access . "'" : "NULL") . "," .
        (!empty($image) ? "'" . $image . "'" : "NULL"));

        if ($r == false) {
            self::$Error[] = Db::error();
            Messages::error(LA::get('cms', 'error_db_return_error'), self::$Error);
            return false;
        }

        Messages::success(LA::get('cms', 'add_notice_success'));

        $this->send_message("CREATE", [
            'email' => $this->post['email'],
            'login' => $this->post['login'],
            'password' => $this->post['password'],
            'token' => md5($this->post['email'])
        ]);

        return true;
    }

    public function update($id)
    {
        $this->register_verify(true);
        if (!empty(self::$Error)) {
            Messages::error(LA::get('cms', 'error_form_return_error'), self::$Error);
            return false;
        }

        $r = Db::update(self::$table, "login = '" . $this->post['login'] . "',
		name = '" . $this->post['name'] . "',
		email = '" . $this->post['email'] . "',
		type = '" . $this->post['type'] . "'", "id='" . $id . "'");

        if ($r == false) {
            self::$Error[] = Db::error();
            Messages::error(LA::get('cms', 'error_db_return_error'), self::$Error);
            return false;
        }

        Messages::success(LA::get('cms', 'update_notice_success'));
        return true;
    }

    public function change_password($id = null)
    {
        if (empty($this->post['new_pass'])) {
            self::$Error[] = "wprowadź nowe hasło";
        }
        if (empty($this->post['new_pass_repeat'])) {
            self::$Error[] = "wprowadź nowe hasło ponownie";
        }

        if ($this->post['new_pass_repeat'] !== $this->post['new_pass']) {
            self::$Error[] = "hasła się nie zgadzają";
        }

        if (!empty(self::$Error)) {
            Messages::error(LA::get('cms', 'error_form_return_error'), self::$Error);
            return false;
        }

        if (is_null($id)) {
            $id = self::$auth['id'];
        }

        $r = $this->get_row($id);

        $password = $this->make_password($r['login'], $this->post['new_pass']);

        Db::update(self::$table, "password='" . $password . "'", "id='" . $id . "'");

        $this->send_message("CHANGE_PASSWORD", [
            'email' => $r['email'],
            'login' => $r['login'],
            'password' => $this->post['new_pass'],
            'name' => $r['name']
        ]);

        Messages::success("Hasło zostało zmienione. Na adres e-mail " . $r['email'] . " została wysłana informacja potwierdzająca tą operację.");
        return true;
    }

    public function reset_password()
    {
        $r = Db::row("id, login, email, name", self::$table, "WHERE email='" . $this->post['login'] . "' OR login='" . $this->post['login'] . "'");
        if (empty($r)) {
            return false;
        }

        $id = $r['id'];

        $new_password = Common::random_string(8);
        $password = $this->make_password($r['login'], $new_password);

        Db::update(self::$table, "password='" . $password . "'", "id='" . $id . "'");

        $this->send_message("CHANGE_PASSWORD", [
            'email' => $r['email'],
            'login' => $r['login'],
            'password' => $new_password,
            'name' => $r['name']
        ]);

        Messages::success("Hasło zostało zresetowane. Szczegółowe informacje zostały wysłane na adres e-mail przypisany do tego konta.");
        return true;
    }

    public function activate($id)
    {
        if (empty(self::$auth['type']) || self::$auth['type'] != 1) {
            Messages::error("Nie masz wystarczających uprawnień, aby aktywować konto");
            return false;
        }

        Db::update(self::$table, "state='TRUE'", "id='" . $id . "'");

        $r = $this->get_row($id);

        $this->send_message("ACTIVATED", [
            'email' => $r['email'],
            'login' => $r['login'],
            'name' => $r['name']
        ]);

        Messages::success("Konto zostało pomyślnie aktywowane. Na adres e-mail " . $r['email'] . " została wysłana informacja potwierdzająca tą operację.");
        return true;
    }

    public function disactivate($id)
    {
        if (empty(self::$auth['type']) || self::$auth['type'] != 1) {
            Messages::error("Nie masz wystarczających uprawnień, aby dezaktywować konto");
            return false;
        }

        Db::update(self::$table, "state='FALSE'", "id='" . $id . "'");

        $r = $this->get_row($id);

        $this->send_message("DISACTIVATED", [
            'email' => $r['email'],
            'login' => $r['login'],
            'name' => $r['name']
        ]);

        Messages::success("Konto zostało pomyślnie dezaktywowane. Na adres e-mail " . $r['email'] . " została wysłana informacja potwierdzająca tą operację.");
        return true;
    }

    public function delete($id)
    {
        Db::delete(self::$table, "id='" . $id . "'");
        Messages::success("Konto zostało pomyślnie usunięte");
    }

/**
 *	Sprawdzanie, czy użytkownik jest zalogowany
 *	@return (bool)
 */

    public static function is_logged()
    {
        if (isset(self::$auth) && (!empty(self::$auth['id']))) {
            return true;
        }

        return false;
    }

    public function get_access($id)
    {
        $r = $this->get_row($id);
        if (!empty($r['access'])) {
            return $r['access'];
        }
    }

    public function update_access($id)
    {
        if (!empty($this->post['element'])) {
            $elements = array_filter($this->post['element']);
            if (!empty($elements)) {
                $elements = implode(";", $elements);

                $r = Db::update(self::$table, "access='" . $elements . "'", "id='" . $id . "'");
            } else {
                $r = Db::update(self::$table, "access=NULL", "id='" . $id . "'");
            }
        } else {
            $r = Db::update(self::$table, "access=NULL", "id='" . $id . "'");
        }

        if ($r == true) {
            Messages::success("Uprawnienia zostały zapisane");
            return true;
        }

        self::$Error = Db::error();
        Messages::error(LA::get('cms', 'error_db_return_error'), self::$Error);
        return false;
    }

/**
 *	Obsługa procesu zapisu sesji logowania
 */

    protected static function store()
    {
        if (empty(self::$auth)) {
            return false;
        }

        $auth = base64_encode(serialize(self::$auth));
        $auth_token = hash('SHA256', $auth);

        $_SESSION['admin'] = ['user' => $auth];
    }

/**
 *	Przywracanie sesji logowania
 */

    public static function restore()
    {
        if (empty($_SESSION['admin']['user'])) {
            return false;
        }

        $auth = unserialize(base64_decode($_SESSION['admin']['user']));
        self::$auth = $auth;
    }

    protected static function make_password($login, $password)
    {
        global $config;

        return password_hash($password, PASSWORD_BCRYPT);
    }

    protected static function send_message($type, $data)
    {
        global $app_admin_url, $app_url, $app_path, $config, $system;

        switch($type) {
            case "CREATE":
                Mail::$subject = "Utworzono nowe konto";

                $text = Emails::getByName("admin-new-account");
                $text = str_replace("[login-url]", $app_admin_url . "login.html", $text);
                $text = str_replace("[name]", (isset($data['name']) ? $data['name'] : ''), $text);
                $text = str_replace("[email]", $data['email'], $text);
                $text = str_replace("[password]", $data['password'], $text);
                break;

            case "ACTIVATED":
                Mail::$subject = "Konto zostało aktywowane";

                $text = Emails::getByName("admin-activated-account");
                $text = str_replace("[login-url]", $app_admin_url . "login.html", $text);
                break;

            case "DISACTIVATED":
                Mail::$subject = "Konto zostało dezaktywowane";

                $text = Emails::getByName("admin-disactivated-account");
                break;

            case "CHANGE_PASSWORD":
                Mail::$subject = "Hasło do twojego konta zostało zmienione";

                $text = Emails::getByName("admin-password-change");
                $text = str_replace("[login-url]", $app_admin_url . "login.html", $text);
                $text = str_replace("[password]", $data['password'], $text);
                break;
        }

        if (!empty($data['name'])) {
            $text = str_replace("[name]", $data['name'], $text);
        }
        if (!empty($data['login'])) {
            $text = str_replace("[login]", $data['login'], $text);
        }

        $tpl = str_replace("[subject]", Mail::$subject, $text);
        $tpl = str_replace("[sender-name]", $config['smtp_from'], $tpl);
        $tpl = str_replace("[content]", $text, $tpl);

        $smarty = new \Smarty();
        $smarty->compile_dir = $app_path . 'cache/templates_c';
        $smarty->assign("config", $config);
        $smarty->assign("app_url", $app_url);
        $smarty->assign("app_admin_url", $app_admin_url);

        $smarty->assign("subject", Mail::$subject);
        $smarty->assign("content", $tpl);

        $smarty->assign("send_date", date('d.m.Y') . ' o godz. ' . date('H:i:s'));
        $smarty->assign("address_ip", Common::get_ip());

        Mail::$text = $smarty->fetch($app_path . 'templates/emails/default.smarty');

        Mail::$address = $data['email'];
        Mail::$name = (isset($data['name']) ? $data['name'] : $data['email']);

        if (\Core\Mail::send() == true) {
            return true;
        }

        Messages::error(LA::get('cms', 'error_mail_return_error') .Mail::getError());
        return false;
    }

    public function Api_UploadImage()
    {
        if (empty($this->files['files'])) {
            http_response_code(400);
            return Api::error('Brak wymaganych parametrów');
        }

        $result = Files::upload($this->files['files'], [
            'upload_dir' => self::$UploadPath,
            'filename' => (!empty($this->post['id']) ? 'image_' . $this->post['id'] : null),
            'resize' => [
                'width' => 450
            ]
        ]);

        if (!empty($result['error'])) {
            return $result;
        }

        if (!empty($this->post['user_id'])) {
            Db::update(self::$table, "image='" . $result['filename'] . "'", "id='" . $this->post['id'] . "'");
            $edited = true;
        }

        return [
            'success' => true,
            'filename' => $result['filename'],
            'uploaded_url' => self::$UploadUrl . $result['filename'],
            'edited' => (empty($edited) ? false : true)
        ];
    }
}
