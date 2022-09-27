<?php
use \Core\Backend\Navigation as Navigation;

class Translations
{
    public static $table = "cms_system_languages";
    public static $Error;
    public $post;

    public function __construct()
    {
        global $request;

        Navigation::submenu('config' , 'Tłumaczenia', "system/promotion/list/");
        //Kernel::addAdminMenu("config", 'Tłumaczenia', "system/promotion/list/", null, true);
        $this->post = $request->post;

        $this->install();
    }

    protected function install()
    {
        if (Db::has_table(self::$table) == false) {
            Db::install("CREATE TABLE " . self::$table . " (`id` int(11) NOT NULL,`module` varchar(200) NOT NULL,`code` varchar(5) NOT NULL,`slug` varchar(150) NOT NULL,`value` varchar(255) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
            Db::install("ALTER TABLE " . self::$table . " ADD PRIMARY KEY (`id`);");
            Db::install("ALTER TABLE " . self::$table . " MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
        }
    }

    public function get_list()
    {
        $r = Db::exec("*", self::$table, "WHERE code='" . Language::get_selected() . "' ORDER BY slug");
        if (empty($r)) {
            return;
        }

        foreach ($r as $k=>$i) {
            if ($i['slug'] == $i['value']) {
                $r[$k]['empty'] = true;
            }
            $r[$k]['value'] = stripslashes($i['value']);
        }

        return $r;
    }

    public function update()
    {
        if (!empty($this->post['translate'])) {
            foreach ($this->post['translate'] as $slug=>$value) {
                $r = Db::update(self::$table, "value='" . addslashes($value) . "'", "code='" . Language::get_selected() . "' AND slug='" . $slug . "'");
                if ($r == false) {
                    die(Db::error());
                }
            }
            Kernel::setMessage("NOTICE", "Zmiany zostały zapisane");
            return true;
        }
    }
}
