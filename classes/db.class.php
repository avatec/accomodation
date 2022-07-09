<?php
/**
 * Database connector class
 *
 * @package		Classes
 * @subpackage	Database
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

class Db {

	private static $instance;
	public static $debug;

	public static function destruct()
	{
		self::$instance->close();
	}

	private function __clone() { }

	public static function call()
	{
		global $config;

    	if(!isset(self::$instance)){
			if(empty($config['db_port'])) {
				self::$instance = new MySQLi($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name'], 3306);
			} else {
				self::$instance = new MySQLi($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name'], $config['db_port']);
			}


        	if(self::$instance->connect_error){
                self::$instance = new MySQLi($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name'], (int) $config['db_port']);
                if(self::$instance->connect_error) {
            	   throw new Exception('Error MySQL: ' . self::$instance->connect_error);
                }
        	}

            self::$instance->set_charset("utf8");
    	}
    	return self::$instance;
	}

	public static function hasTable( $table )
	{
		if(!isset(self::$instance)) {
    		self::$instance = self::call();
    	}

    	$query = "SHOW TABLES LIKE " . $table;

    	if(self::$debug == true) {
	    	echo $query . PHP_EOL;
    	}

    	$result = self::$instance->query($query);
    	if(empty($result)) {
	    	return false;
		}

		if( $result->num_rows > 0 ) {
			return true;
		}

		return false;
	}

	public static function error()
	{
    	if(!isset(self::$instance)) {
    		self::$instance = self::call();
    	}

    	return self::$instance->error;
	}

	public static function real_escape( $string )
	{
    	if(!isset(self::$instance)) {
    		self::$instance = self::call();
    	}

		return self::$instance->real_escape_string( $string );
	}

	public static function query( $query )
	{
    	if(!isset(self::$instance)) {
    		self::$instance = self::call();
    	}

    	if(self::$debug == true) {
	    	echo $query . PHP_EOL;
    	}

    	$result = self::$instance->query($query);
    	if(!empty(self::$instance->error)) {
	    	throw new Exception('Error MySQL: ' . self::$instance->error);
    	}

    	while ($row = $result->fetch_assoc()) {
			$array[] = $row;
		}

		if(isset($array)) {
			return $array;
		}
	}

	public static function query_row($query)
    {
        if (!isset(self::$instance)) {
            self::$instance = self::call();
        }

        if (!empty(self::$debug)) {
            self::showDebug($query, __CLASS__ . '::' . __FUNCTION__ . ' on line: ' . __LINE__);
        }

        $result = self::$instance->query($query);
        if (!empty(self::$instance->error)) {
            throw new Exception('Error MySQL: ' . self::$instance->error);
        }
        if ($result == true) {
            while ($row = $result->fetch_assoc()) {
                $array[] = $row;
            }
        }
        if (isset($array[0])) {
            return $array[0];
        }
    }

	public static function run( $query )
	{
    	if(!isset(self::$instance)) {
    		self::$instance = self::call();
    	}

    	$result = self::$instance->query($query);
    	if($result == true) {
    		return true;
    	} else {
    		return false;
    	}
	}

	public static function update($t, $q, $w)
	{
    	if(!isset(self::$instance)) {
    		self::$instance = self::call();
    	}

    	$query = "UPDATE " . $t . " SET " . $q . " WHERE ".$w;
    	if(self::$instance->query($query) === true) {
	    	return true;
    	} else {
	    	if(self::$debug == true) {
		    	echo $query . PHP_EOL;
		    	exit;
	    	}
	    	throw new Exception('Error MySQL: ' . self::$instance->error);
    	}
	}

	public static function exec( $e = "*", $t, $w = null )
	{
    	if(self::$debug == true) {
	    	echo "Function exec: <b>SELECT " . $e . " FROM " . $t . " " . $w . "</b><br/>";
    	}
    	return self::query("SELECT " . $e . " FROM " . $t . " " . $w);
	}

	public static function row( $e = "*" , $t, $w = null )
	{
    	$query = "SELECT " . $e . " FROM " . $t . " " . $w;

    	if(!isset(self::$instance)) {
    		self::$instance = self::call();
    	}

    	if(self::$debug == true) {
	    	echo $query . PHP_EOL;
	    	exit;
    	}

    	$result = self::$instance->query($query);
    	if(!empty(self::$instance->error)) {
	    	throw new Exception('Error MySQL: ' . self::$instance->error);
    	}

    	$result = $result->fetch_assoc();
    	return $result;
	}

	public static function check( $t, $w, $silent = false )
	{

    	if(!isset(self::$instance)) {
    		self::$instance = self::call();
    	}
    	if(self::$debug == true) {
	    	echo "Function check: <b>SELECT * FROM " . $t . " WHERE " . $w . "</b><br/>";
    	}
    	$query = "SELECT * FROM " . $t . " WHERE " . $w;
    	$result = mysqli_query(self::$instance, $query);
    	if( isset($result->num_rows) && ($result->num_rows > 0) ) {
	    	return true;
    	} else {
	    	return false;
    	}
	}

	public static function insert( $t, $v ) {
    	if(!isset(self::$instance)) {
    		self::$instance = self::call();
    	}

    	$query = "INSERT INTO " . $t . " VALUES(" . $v . ");";

    	if(self::$debug == true) {
	    	echo "Function insert: <b>".$query."</b><br/>";
    	}

    	$result = mysqli_query(self::$instance, $query);
    	return $result;

	}

	public static function insert_id()
	{
		if(!empty(self::$instance)) {
			self::$instance = self::call();
		}

		return self::$instance->insert_id;
	}

	public static function save( $t, $v, $w ) {
    	if(!isset(self::$instance)) {
    		self::$instance = self::call();
    	}

    	$query = "UPDATE  " . $t . " SET " . $v . " WHERE " . $w . ";";
    	$result = mysqli_query(self::$instance, $query);
    	return $result;

	}

	public static function delete( $t, $w ) {
    	if(!isset(self::$instance)) {
    		self::$instance = self::call();
    	}

    	$query = "DELETE FROM  " . $t . " WHERE " . $w . ";";
    	$result = mysqli_query(self::$instance, $query);

    	$query = "OPTIMIZE TABLE " . $t;
    	mysqli_query(self::$instance, $query);
    	return $result;
	}

	public static function counter( $t, $w = null )
	{
    	if(!isset(self::$instance)) {
    		self::$instance = self::call();
    	}
		if(is_null($w)) {
			$result = self::$instance->query("SELECT * FROM " . $t );
			if(self::$debug == true) {
	    	echo "Function check: <b>SELECT * FROM " . $t . "</b><br/>";
    	}
		} else {
	    	$result = self::$instance->query("SELECT * FROM " . $t . " WHERE " . $w);
	    	if(self::$debug == true) {
		    	echo "Function check: <b>SELECT * FROM " . $t . " WHERE " . $w . "</b><br/>";
	    	}
	    }

	    if(!empty($result)) {
	    	return $result->num_rows;
    	} else {
	    	return 0;
    	}
	}

	public static function last_id( $table, $column = "id" )
	{
    	if(!isset(self::$instance)) {
    		self::$instance = self::call();
    	}

    	$result = self::row("*" , $table , "ORDER BY ".$column." DESC");
    	return $result[$column];
	}

	public static function count( $t, $w = null )
	{
    	if(!isset(self::$instance)) {
    		self::$instance = self::call();
    	}
		if(is_null($w)) {
			$result = self::$instance->query("SELECT * FROM " . $t );
		} else {
	    	$result = self::$instance->query("SELECT * FROM " . $t . " WHERE " . $w);
	    }

	    if(!empty($result->num_rows)) {
	    	return $result->num_rows;
    	} else {
	    	return 0;
    	}
	}

	public static function has_table( $t )
	{
    	global $config;

    	if(!isset(self::$instance)) {
    		self::$instance = self::call();
    	}

		$result = self::$instance->query("SHOW TABLES FROM " . $config['db_name'] . " LIKE '" . $t . "';");
    	if(!empty(self::$instance->error)) {
	    	throw new Exception('Error MySQL: ' . self::$instance->error);
    	}

    	if(!empty($result->num_rows)) {
	    	return true;
    	}

    	return false;
	}

	public static function install( $sql_array )
	{
    	if((!empty($sql_array)) && (is_array($sql_array))) {
	    	foreach($sql_array as $sql) {
		    	self::run( $sql );
	    	}
    	} else {
	    	if(!empty($sql_array)) {
		    	self::run( $sql_array );
	    	}
    	}

    	return true;
	}
}
