<?php namespace Modules\Admins;

use \Db, \Kernel, \Modules\Admins as Admins;

class Tokens
{
	public static $table = "admins_tokens";
	public static $expire = 1800;

	public function __construct()
	{
		global $config;

		self::$table = $config['db_prefix'] . self::$table;

		$this->install();
	}

	protected function install()
	{
		if( Db::has_table( self::$table ) == false ) {
			Db::install("CREATE TABLE " . self::$table . " (`uid` int(11) NOT NULL,`session_id` varchar(128) NOT NULL,`token` varchar(128) NOT NULL,`expire` varchar(128) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
			Db::install("ALTER TABLE " . self::$table . " ADD KEY `uid` (`uid`), ADD KEY `token` (`session_id`);");
		}
	}

	public static function create( $uid, $token_length = 32 )
	{
		$token = self::token( $token_length );

		$r = Db::insert( self::$table , "'" . $uid . "','" . session_id() . "','" . $token . "','" . (time() + self::$expire) . "'");
		if( $r == true ) {
			Kernel::log('admins.log' , 'New token has been created');
			return $token;
		}

		return false;
	}

	public static function update( $token )
	{
		self::remove_expired();

		if( Db::check( self::$table , "session_id='" . session_id() . "' AND token='" . $token . "'") == true ) {
			$expire = (time() + self::$expire);
			$r = Db::update( self::$table , "expire='" . $expire . "'", "token='" . $token . "'");
			if( $r == true ) {
				Kernel::log('admins.log' , 'Token has been updated');
				Admins::$auth['expire'] = $expire;
				return true;
			}
		}

		session_regenerate_id();
		return false;
	}

	protected static function remove_expired()
	{
		if( Db::delete( self::$table , "expire<'" . time() . "'") == true ) {
			Kernel::log('admins.log' , 'Expired token has been removed');
			return true;
		}

		return false;
	}

	protected static function token( $bytes = 64 )
	{
		return bin2hex(openssl_random_pseudo_bytes($bytes));
	}
}
