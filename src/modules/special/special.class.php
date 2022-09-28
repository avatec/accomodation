<?php

use Core\Backend\Navigation;

/**
 * Special Offers class
 *
 * @package		Modules
 * @subpackage	SpecialOffers
 * @author		Grzegorz Miskiewicz <biuro@avatec.pl>
 * @license		Property license
 *
 * <p>Ten produkt jest licencjonowany
 * Możesz modyfikować te pliki jednak nie możesz usuwać oryginalnych komentarzy
 * w szczególności informacji o autorze tego oprogramowania</p>
 *
 * <p>W przypadku indywidualnego modyfikowania tego pliku autor nie ponosi
 * odpowiedzialności za wszelkie błędy i/lub wady tego oprogramowania.</p>
 */
class SpecialOffers
{

	protected static$table = "special";
	protected static$table_order = "special_order";
	protected static$nl_table;

	public static $Error;
	public static $UploadDir = "userfiles/specials/";
	public static $UploadPath, $UploadUrl;

	public $post, $get, $files;

	public function __construct()
	{
		global $app_url, $app_path, $request, $config, $route;

		//Kernel::addAdminMenu("objects", "Oferty specjalne", "admin/special/list/", null, true);
		Navigation::submenu('objects', 'Oferty specjalne', "special/list/");

		self::$nl_table = $config['db_prefix'] . self::$table . "_";
		self::$table = self::$table . "_" . Language::get_selected();
		self::$table = $config['db_prefix'] . self::$table;

		self::$table_order = $config['db_prefix'] . self::$table_order;

		self::$UploadPath = $app_path . self::$UploadDir;
		self::$UploadUrl = $app_url . self::$UploadUrl;

		$this->post = (!empty($request->post) ? $request->post : null);
		$this->get  = (!empty($request->get) ? $request->get : null);
		$this->files = (!empty($request->files) ? $request->files : null);

		$this->install();
		$this->register($route);
	}

	protected function register($route)
	{
		$route->get('(panel)\/(special)\/:string', [
			'module' => 'special', 'file' => 'special', 'command' => '$3'
		]);
	}


	protected function install()
	{
		System::create_dir(self::$UploadPath);

		if (!empty(Language::$available)) {
			foreach (Language::$available as $lang => $i) {
				if (Db::has_table(self::$nl_table . $lang) == false) {
					Db::install("CREATE TABLE " . self::$nl_table . $lang . " (`id` int(11) UNSIGNED NOT NULL, `name` varchar(200) NOT NULL, `rewrite` varchar(200) NOT NULL, `icon` varchar(200) DEFAULT NULL );");
					Db::install("ALTER TABLE " . self::$nl_table . $lang . " ADD PRIMARY KEY (`id`);");
					Db::install("ALTER TABLE " . self::$nl_table . $lang . " MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;");
				}
			}
		}
	}

	public function getMain()
	{
		$result = Cache::get("special_main");
		if (empty($result)) {
			$result = Db::exec("*", self::$table, "WHERE show_main='TRUE'");
			Cache::set("special_main", $result);
		}
		if (!empty($result)) {
			return $result;
		}
	}

	public function get($id = NULL)
	{
		if (is_null($id)) {
			return Db::exec("*", self::$table, "ORDER BY id");
		} else {
			$Result = Db::row("*", self::$table, "WHERE id='" . $id . "'");
			$Result['edit'] = true;
			return $Result;
		}
	}

	public static function getName($id)
	{
		$row = Db::row("name", self::$table, "WHERE id='" . $id . "'");
		if (!empty($row)) {
			return $row['name'];
		}
	}

	public function getOrdered($object_id)
	{
		$list = array();
		$list['ordered'] = Db::exec("*", self::$table_order, "WHERE object_id='" . $object_id . "'");
		$list['specials'] = $this->get();
		if (!empty($list['specials'])) {
			foreach ($list['specials'] as $k => $i) {
				if (Db::check(self::$table_order, "special_id = '" . $i['id'] . "' AND object_id = '" . $object_id . "' AND expire_date >= CURDATE()")) {
					$list['specials'][$k]['status'] = true;
				} else {
					$list['specials'][$k]['status'] = false;
				}
			}
		}
		if (!empty($list)) {
			return $list;
		}
	}

	public function getOrderedSingle($id)
	{
		return Db::row("*", self::$table_order, "WHERE id='" . $id . "'");
	}

	public function updateOrdered($object_id, $id)
	{
		if (Db::check(self::$table_order, "special_id='" . $id . "' AND object_id = '" . $object_id . "'") == true) {
			$result = Db::update(self::$table_order, "expire_date = '" . $this->post['expire_date'] . "'", "special_id='" . $id . "' AND object_id='" . $object_id . "'");
		} else {
			$result = Db::insert(self::$table_order, "null,
			'" . $id . "',
			'" . $object_id . "',
			NOW(),
			'" . $this->post['expire_date'] . "'");
		}
		if (!empty($result)) {
			Kernel::setMessage("NOTICE", "Pomyślnie ustawiono okres promocji dla wybranych ofert specjalnych");
			return $result;
		}
	}

	public function deleteObject($id, $object_id)
	{
		return Db::delete(self::$table_order, "id = '" . $id . "' AND object_id='" . $object_id . "'");
	}

	public function verify()
	{
		if (empty($this->post['name'])) {
			self::$Error[] = "podaj nazwę";
		}
	}

	public function add()
	{
		$this->verify();
		if (!empty($this->files['photo']['name'])) {
			$photo = System::upload($this->files['photo'], [
				'upload_dir' => self::$UploadPath
			]);
		}

		if (!empty(self::$Error)) {
			Kernel::setMessage("ERROR", "Wystąpiły błędy w formularzu:<br/>" . implode("<br/>", self::$Error));
			return false;
		}
		foreach (Language::$available as $lang => $i) {
			$result = Db::insert(self::$nl_table . $lang, "null,
			'" . $this->post['show_main'] . "',
			'" . $this->post['name'] . "',
			'" . (!empty($this->post['description']) ? addslashes($this->post['description']) : "") . "',
			'" . (empty($this->post['rewrite']) ? Kernel::rewrite($this->post['name']) : Kernel::rewrite($this->post['rewrite'])) . "',
			" . (!empty($photo) ? "'" . $photo . "'" : "NULL") . ",
			'" . (!empty($this->post['meta_title']) ? $this->post['meta_title'] : "") . "',
			'" . (!empty($this->post['meta_description']) ? $this->post['meta_description'] : "") . "',
			'" . (!empty($this->post['meta_keywords']) ? $this->post['meta_keywords'] : "") . "'");

			if ($result == true) {
				Kernel::setMessage("NOTICE", "Dodano nową pozycję dla języka " . $lang);
			} else {
				self::$Error = Db::error();
				Kernel::setMessage("ERROR", "Wystąpił błąd podczas operacji w bazie danych (" . self::$nl_table . $lang . "):<br/>" . self::$Error);
			}
		}

		if ($result == true) {
			return true;
		} else {
			return false;
		}
	}

	public function save($id)
	{
		global $app_path;

		$this->verify();
		if (!empty($this->files['photo']['name'])) {
			$photo = System::upload($this->files['photo'], [
				'upload_dir' => self::$UploadPath
			]);
		}

		if (!empty($this->post['delete_photo'])) {
			$this->delete_photo($id);
		}

		if (!empty(self::$Error)) {
			Kernel::setMessage("ERROR", "Wystąpiły błędy w formularzu:<br/>" . implode("<br/>", self::$Error));
			return false;
		}

		$result = Db::update(self::$table, "show_main = '" . $this->post['show_main'] . "',
		name = '" . $this->post['name'] . "',
		" . (!empty($this->post['description']) ? "description = '" . addslashes($this->post['description']) . "'," : "") . "
		rewrite = '" . (empty($this->post['rewrite']) ? Kernel::rewrite($this->post['name']) : Kernel::rewrite($this->post['rewrite'])) . "'" .
			(!empty($this->post['meta_title']) ? ",meta_title = '" . $this->post['meta_title'] . "'" : "") .
			(!empty($this->post['meta_description']) ? ",meta_description = '" . $this->post['meta_description'] . "'" : "") .
			(!empty($this->post['meta_keywords']) ? ",meta_keywords = '" . $this->post['meta_keywords'] . "'" : ""), "id='" . $id . "'");

		if (!empty($result)) {
			Kernel::setMessage("NOTICE", "Pomyślnie zapisano zmiany");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR", "Wystąpił błąd podczas w bazie danych podczas operacji::<br/>" . self::$Error);
			return false;
		}
	}

	public function delete($id)
	{
		if (Db::check(self::$table, "id='" . $id . "'") == true) {
			$this->delete_photo($id);
			foreach (Language::$available as $lang => $i) {
				Db::delete(self::$nl_table . $lang, "id= '" . $id . "'");
			}
			Kernel::setMessage("NOTICE", "Pomyślnie usunięto pozycję");
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR", "Wystąpił błąd podczas usuwania pozycji. Najprawdopodobniej wybrana pozycja nie istnieje<br/>" . self::$Error);
			return false;
		}
	}

	public function delete_photo($id)
	{
		$r = Db::row("icon", self::$table, "WHERE id='" . $id . "'");
		if (empty($r)) {
			return;
		}

		System::delete_file(self::$UploadPath . $r['icon']);
		Db::update(self::$table, "icon = NULL", "id='" . $id . "'");
	}

	public static function getSelect()
	{
		return Db::exec("*", self::$table, "ORDER BY id");
	}

	public static function getID($rewrite)
	{
		$row = Db::row("id", self::$table, "WHERE rewrite='" . $rewrite . "'");
		return $row['id'];
	}

	public static function has($rewrite)
	{
		if (Db::check(self::$table, "rewrite='" . $rewrite . "'") == true) {
			return self::getID($rewrite);
		} else {
			return false;
		}
	}

	public static function getPromoted($special_name, $by_id = false)
	{
		if ($by_id == false) {
			$result = Db::exec("object_id", self::$table_order, "WHERE special_id IN (SELECT id FROM " . self::$table . " WHERE rewrite='" . $special_name . "') AND expire_date>= CURDATE()");
		} else {
			$result = Db::exec("object_id", self::$table_order, "WHERE special_id IN (SELECT id FROM " . self::$table . " WHERE id='" . $special_name . "') AND expire_date>= CURDATE()");
		}
		if (!empty($result)) {
			return $result;
		}
	}

	public static function readExpire($object_id, $special_id)
	{
		$r = Db::row("*", self::$table_order, "WHERE special_id='" . $special_id . "' AND object_id='" . $object_id . "' AND expire_date>=CURDATE()");
		if (empty($r)) {
			return '<label class="label label-danger">kliknij na przycisk wykup i zwiększ zainteresowanie swoją ofertą</label>';
		}

		return '<label class="label label-info">ważne do ' . $r['expire_date'] . '</label>';
	}

	public static function addExpire($object_id, $promotion_id, $special_id)
	{
		$days = Promotion::getDays($promotion_id);

		if (Db::check(self::$table_order, "special_id = '" . $special_id . "' AND order_id='" . $object_id . "'") == false) {
			Payment::log('table_order false - adding');
			return Db::insert(self::$table_order, "null,
			'" . $special_id . "',
			'" . $object_id . "',
			NOW(),
			CURDATE() + INTERVAL " . $days . " DAY");
		} else {
			Payment::log('table_order true - updating');
			return Db::update(self::$table_order, "expire_date = expire_date + INTERVAL " . $days . " DAY", "special_id='" . $special_id . "' AND object_id='" . $object_id . "'");
		}
	}
}
