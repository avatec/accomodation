<?php
use \Core\Backend\Navigation as Navigation;

/**
 * Partners module class
 *
 * @package		Modules
 * @subpackage	Partner
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

class Partner {

	protected static $table = "partner";
	public static $Error;

	protected $post, $get;

    protected static $UploadDir = "userfiles/partner/";
    public static $UploadPath, $UploadUrl;

	public function __construct()
	{
		global $config, $request, $app_path, $app_url;

		Navigation::menu(14,'partner','Partnerzy','partner/list','fa-users');
		//Kernel::addAdminMenu("partner", "Partnerzy", "admin/partner/list/", "fa-users", null, false);
		self::$table = $config['db_prefix'] . self::$table;

		$this->post = (!empty($request->post) 	? $request->post 	: null);
		$this->get 	= (!empty($request->get) 	? $request->get 	: null);

        self::$UploadUrl = $app_url . self::$UploadDir;
        self::$UploadPath = $app_path . self::$UploadDir;
	}

	public static function getForCarousel()
	{
		$array = Db::exec("*" , self::$table , "ORDER BY priority");
		if(!empty($array)) {
			$all = count($array);
			$step = 4;
			$retar = array();
			$count = 0;
			$num = 0;

			if(is_array($array)) {
				foreach($array as $k=>$i) {
					$count++;
					$retar[$num][] = $i;
					if($count %$step == 0) {
						$num++;
					}
				}
				return $retar;
			}
		}
	}

	public function get( $id = NULL )
	{
		if(is_null($id)) {
			return Db::exec("*" , self::$table , "ORDER BY priority");
		} else {
			$Result = Db::row("*" , self::$table , "WHERE id='".$id."'");
			$Result['edit'] = true;
			return $Result;
		}
	}

	protected function verify( $edit = false )
	{
        if(empty($_FILES['photo']['name'])) {
            self::$Error[] = "wybierz logotyp, który chcesz dodać do partnerów";
        }
	}

	public function add()
	{
		$this->verify();
		if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , LA::get('cms','error_form_return_error') , self::$Error);
			return false;
		}

        if(!empty($_FILES['photo']['name'])) {
            $photo = System::upload($_FILES['photo'], [
                'upload_dir' => self::$UploadPath
            ]);
		}

		$r = Db::insert( self::$table , "null,
		'" . $this->post['name'] . "',
		'" . $this->post['link'] . "',
		'" . $this->post['priority'] . "',
		" . (!empty($photo) ? "'" . $photo . "'" : 'NULL') . ",
		NOW()");

        if( $r == true ) {
            Kernel::setMessage("NOTICE" , LA::get('cms' , 'add_notice_success'));
			return true;
        }

		self::$Error = Db::error();
		Kernel::setMessage("ERROR" , LA::get('cms' , 'error_db_return_error') , self::$Error);
		return false;
	}

	public function save( $id )
	{
		$this->verify( true );
        if(!empty(self::$Error)) {
			Kernel::setMessage("ERROR" , LA::get('cms','error_form_return_error') , self::$Error);
			return false;
		}

		if(!empty($_FILES['photo']['name'])) {
            if(!empty($this->post['old_photo'])) {
                System::delete_file( self::$UploadPath . $this->post['old_photo']);
            }
            $photo = System::upload( $_FILES['photo'] , [
                'upload_dir' => self::$UploadPath
            ]);
            Db::update( self::$table , "photo='" . $photo . "'" , "id='" . $id . "'");
		}

		$r = Db::update( self::$table , "name = '" . $this->post['name'] . "',
		link = '" . $this->post['link'] . "',
		priority = '" . $this->post['priority'] . "'" , "id='".$id."'");

		if( $r == true ) {
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'update_notice_success'));
			return true;
		}

		self::$Error = Db::error();
		Kernel::setMessage("ERROR" , LA::get('cms' , 'error_db_return_error') , self::$Error);
		return false;
	}

	public function delete( $id, $file )
	{
		if( Db::check( self::$table , "id='" . $id ."'") == true) {
			Db::delete( self::$table , "id= '" . $id . "'");
            System::delete_file( self::$UploadPath . $file );
			Kernel::setMessage("NOTICE" , LA::get('cms' , 'delete_notice_success'));
			return true;
		} else {
			self::$Error = Db::error();
			Kernel::setMessage("ERROR" , LA::get('cms' , 'error_db_return_error') , self::$Error);
			return false;
		}
	}
}
