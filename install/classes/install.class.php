<?php
class Install 
{
	public static $data;
	public static $Error;
	
	public static function verify()
	{
		$response = Kernel::httpPost('https://www.avatec.pl/api/' , [
			'license' => [
				'name' => self::$data['license']['name'],
				'email' => self::$data['license']['email'],
				'key' => self::$data['license']['key']
			]
		]);

		if($response == false) {
			$response = Kernel::httpPost('http://www.avatec.pl/api/' , [
				'license' => [
					'name' => self::$data['license']['name'],
					'email' => self::$data['license']['email'],
					'key' => self::$data['license']['key']
				]
			]);
		}
		
		$json = json_decode( $response );
		if(!empty($json)) {
			if($json->result == 'ok' && strlen($json->token) == 64) {
				self::$data['salt'] = $json->token;
				
				self::create_config();
				if( self::install_sql() == false) {
					self::$Error = "Podczas operacji w bazie danych wystąpił błąd. Prześlij do nas swój plik <a href=\"/logs/install.log\">install.log</a>";
					return false;
				} else {
					return true;
				}
				self::$Error = "Wystąpił błąd podczas instalacji. Numer błędu 1091";
				return true;
			} else {
				self::$Error = "Wystąpił błąd podczas instalacji. Numer błędu 1093";
				return false;
			}
		}
	}
	
	private static function install_sql()
	{
		global $app_path;
		include "../classes/db.class.php";
		include $app_path . "classes/sqlparser.class.php";
		
		if(file_exists("./install.sql") == true) {
			global $config;
			$config = [
			 "db_host" => self::$data['db_host'],
			 "db_name" => self::$data['db_name'],
			 "db_user" => self::$data['db_user'],
			 "db_pass" => self::$data['db_pass'],
			 "db_port" => self::$data['db_port']	
			];
			file_put_contents("../logs/install.log" , "Running installer at " . date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);
			file_put_contents("../logs/install.log" , "-----------------------------------------------------------" . PHP_EOL, FILE_APPEND);
			file_put_contents("../logs/install.log" , "Checking install.sql existing", FILE_APPEND);
			Db::call();
			file_put_contents("../logs/install.log" , ".... true" . PHP_EOL, FILE_APPEND);
			$sqlLists = SqlParser::parse(file_get_contents("./install.sql"));
			if(!empty($sqlLists)) {
				foreach($sqlLists as $sql) {
				    
				    $Result = Db::run($sql);
				    if($Result == true) {
				    	//file_put_contents("../logs/install.log" , "[DONE]" . PHP_EOL . PHP_EOL, FILE_APPEND);
				    } else {
				    	file_put_contents("../logs/install.log" , "[ERROR WHILE EXECUTING QUERY]: " . PHP_EOL . $sql . PHP_EOL , FILE_APPEND);
				    	$error = true;
				    }
				}
			}
		}
		
		if(isset($error)) {
			return false;
		} else {
			if(!file_exists($app_path . ".htaccess")) {
				//echo $app_path . "htaccess.txt";
				copy($app_path . "htaccess.txt" , $app_path . "../.htaccess");
			}
			@chmod($app_path . "../cache/" , 0777);
			@chmod($app_path . "../logs/" , 0777);
			@chmod($app_path . "../userfiles/" , 0777);
			@chmod($app_path . "../templates/website/images/logo.png" , 0666);
			@chmod($app_path . "../templates/website/images/facebook.jpg" , 0666);
			@chmod($app_path . "../templates/website/images/watermark.png" , 0666);
			$password = md5( "Admin1234|admin|" . self::$data['salt'] );
			if( Db::insert("acc_users" , "1,NULL,'admin','" . $password . "','".self::$data['license']['name']."','".self::$data['license']['email']."','','ADMIN','TRUE','USER',NULL,NULL,NULL,NULL,'','','','','','',NULL,NULL,NOW(),NULL,NULL,NULL,NULL") == true ) {
				file_put_contents("../logs/install.log" , "[USER ADMIN CREATED]" . PHP_EOL . PHP_EOL, FILE_APPEND);
				return true;
			} else {
				file_put_contents("../logs/install.log" , "[USER ADMIN NOT CREATED]" . PHP_EOL . Db::error() . PHP_EOL . PHP_EOL, FILE_APPEND);
				return false;
			}
			
		}
	}
	
	private static function create_config()
	{
		$config = file_get_contents("../include/config/main.default");
		
		$config = str_replace('{$db_host}' , self::$data['db_host'], $config);
		$config = str_replace('{$db_port}' , self::$data['db_port'], $config);
		$config = str_replace('{$db_name}' , self::$data['db_name'], $config);
		$config = str_replace('{$db_user}' , self::$data['db_user'], $config);
		$config = str_replace('{$db_pass}' , self::$data['db_pass'], $config);
		$config = str_replace('{$salt}' , self::$data['salt'], $config);
		$config = str_replace('{$license_name}' , self::$data['license']['name'], $config);
		$config = str_replace('{$license_email}' , self::$data['license']['email'], $config);
		$config = str_replace('{$license_key}' , self::$data['license']['key'], $config);
		
		file_put_contents("../include/config/main.php", $config);	
	}
}