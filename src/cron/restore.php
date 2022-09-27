<?php
error_reporting(E_ALL);
ini_set("display_errors" , "on");

$path = realpath(dirname('../index.php')) . '/';

// Deleting all files in userfiles folder

$files = glob('../userfiles/ads/*');
foreach($files as $file){
	if(is_file($file)) {
		@unlink($file);
		$errors= error_get_last();
		if(isset($errors)) {
	    	echo "COPY ERROR: ".$errors['type'];
	    	echo "<br />\n".$errors['message'];
		}
	}
}
exec('cp ' . $path . "cron/restore/userfiles/ads/*" . ' ' . $path . "userfiles/ads/") or
@copy($path . "cron/restore/userfiles/ads/*.*" , $path . "userfiles/ads/");

unset($files);
$files = glob('../userfiles/editor/*');
foreach($files as $file){
	if(is_file($file)) {
		@unlink($file);
		$errors= error_get_last();
		if(isset($errors)) {
	    	echo "COPY ERROR: ".$errors['type'];
	    	echo "<br />\n".$errors['message'];
		}
	}
}

exec('cp ' . $path . "cron/restore/userfiles/editor/*" . ' ' . $path . "userfiles/editor/") or
@copy($path . "cron/restore/userfiles/editor/*.*" , $path . "userfiles/editor/");

unset($files);
$files = glob('../userfiles/locations/*');
foreach($files as $file){
	if(is_file($file)) {
		@unlink($file);
		$errors= error_get_last();
		if(isset($errors)) {
	    	echo "COPY ERROR: ".$errors['type'];
	    	echo "<br />\n".$errors['message'];
		}
	}
}
exec('cp ' . $path . "cron/restore/userfiles/locations/*" . ' ' . $path . "userfiles/locations/") or
@copy($path . "cron/restore/userfiles/locations/*.*" , $path . "userfiles/locations/");

unset($files);
$files = glob('../userfiles/news/icons/*');
foreach($files as $file){
	if(is_file($file)) {
		@unlink($file);
		$errors= error_get_last();
		if(isset($errors)) {
	    	echo "COPY ERROR: ".$errors['type'];
	    	echo "<br />\n".$errors['message'];
		}
	}
}
unset($files);
$files = glob('../userfiles/news/gallery/*');
foreach($files as $file){
	if(is_file($file)) {
		@unlink($file);
		$errors= error_get_last();
		if(isset($errors)) {
	    	echo "COPY ERROR: ".$errors['type'];
	    	echo "<br />\n".$errors['message'];
		}
	}
}
unset($files);
$files = glob('../userfiles/news/gallery/thumbs/*');
foreach($files as $file){
	if(is_file($file)) {
		@unlink($file);
		$errors= error_get_last();
		if(isset($errors)) {
	    	echo "COPY ERROR: ".$errors['type'];
	    	echo "<br />\n".$errors['message'];
		}
	}
}
exec('cp ' . $path . "cron/restore/userfiles/news/gallery/*" . ' ' . $path . "userfiles/news/galery/") or
@copy($path . "cron/restore/userfiles/news/gallery/*.*" , $path . "userfiles/news/gallery/");
exec('cp ' . $path . "cron/restore/userfiles/news/gallery/thumbs/*" . ' ' . $path . "userfiles/news/galery/thumbs/") or
@copy($path . "cron/restore/userfiles/news/gallery/thumbs/*.*" , $path . "userfiles/news/gallery/thumbs");
exec('cp ' . $path . "cron/restore/userfiles/news/icons/*" . ' ' . $path . "userfiles/news/icons/") or
@copy($path . "cron/restore/userfiles/news/icons/*.*" , $path . "userfiles/news/icons/");

unset($files);
$files = glob('../userfiles/objects/photos/*');
foreach($files as $file){
	if(is_file($file)) {
		@unlink($file);
		$errors= error_get_last();
		if(isset($errors)) {
	    	echo "COPY ERROR: ".$errors['type'];
	    	echo "<br />\n".$errors['message'];
		}
	}
}
exec('cp ' . $path . "cron/restore/userfiles/objects/photos/*" . ' ' . $path . "userfiles/objects/photos/") or
@copy($path . "cron/restore/userfiles/objects/photos/*.*" , $path . "userfiles/objects/photos/");

unset($files);
$files = glob('../userfiles/objects/photos/thumbs/*');
foreach($files as $file){
	if(is_file($file)) {
		@unlink($file);
		$errors= error_get_last();
		if(isset($errors)) {
	    	echo "COPY ERROR: ".$errors['type'];
	    	echo "<br />\n".$errors['message'];
		}
	}
}
exec('cp ' . $path . "cron/restore/userfiles/objects/photos/thumbs/*" . ' ' . $path . "userfiles/objects/photos/thumbs/") or
@copy($path . "cron/restore/userfiles/objects/photos/thumbs/*.*" , $path . "userfiles/objects/photos/thumbs/");

unset($files);
$files = glob('../userfiles/partner/*');
foreach($files as $file){
	if(is_file($file)) {
		@unlink($file);
		$errors= error_get_last();
		if(isset($errors)) {
	    	echo "COPY ERROR: ".$errors['type'];
	    	echo "<br />\n".$errors['message'];
		}
	}
}
exec('cp ' . $path . "cron/restore/userfiles/partner/*" . ' ' . $path . "userfiles/partner/")  or
@copy($path . "cron/restore/userfiles/partner/*.*" , $path . "userfiles/partner/");

unset($files);
$files = glob('../userfiles/slider/*');
foreach($files as $file){
	if(is_file($file)) {
		@unlink($file);
		$errors= error_get_last();
		if(isset($errors)) {
	    	echo "COPY ERROR: ".$errors['type'];
	    	echo "<br />\n".$errors['message'];
		}
	}
}
exec('cp ' . $path . "cron/restore/userfiles/slider/*" . ' ' . $path . "userfiles/slider/")  or
@copy($path . "cron/restore/userfiles/slider/*.*" , $path . "userfiles/slider/");

unset($files);
$files = glob('../userfiles/users/*');
foreach($files as $file){
	if(is_file($file)) {
		@unlink($file);
		$errors= error_get_last();
		if(isset($errors)) {
	    	echo "COPY ERROR: ".$errors['type'];
	    	echo "<br />\n".$errors['message'];
		}
	}
}
//exec('cp ' . $path . "cron/restore/userfiles/users/*" . ' ' . $path . "userfiles/users/");

// Inserting database
include "../include/config/main.php";
include "../classes/db.class.php";
include "../classes/sqlparser.class.php";

Db::call();

// Database
$sqlLists = SqlParser::parse(file_get_contents("restore/restore.sql"));
if(!empty($sqlLists)) {
	foreach($sqlLists as $sql) {

		$Result = Db::run($sql);
		if($Result == true) {
			file_put_contents("../logs/restore.log" , "[DONE]" . PHP_EOL . PHP_EOL, FILE_APPEND);
		} else {
			file_put_contents("../logs/restore.log" , "[ERROR]: " . PHP_EOL . $sql . PHP_EOL , FILE_APPEND);
			file_put_contents("../logs/restore.log" , "[DB ERROR]" . PHP_EOL . Db::error() . PHP_EOL . PHP_EOL, FILE_APPEND);
			$error = true;
		}
	}
}

// Datas
$sqlLists = SqlParser::parse(file_get_contents("restore/restore_data.sql"));
if(!empty($sqlLists)) {
	foreach($sqlLists as $sql) {

		$Result = Db::run($sql);
		if($Result == true) {
			file_put_contents("../logs/restore_data.log" , "[DONE]" . PHP_EOL . PHP_EOL, FILE_APPEND);
		} else {
			file_put_contents("../logs/restore_data.log" , "[ERROR]: " . PHP_EOL . $sql . PHP_EOL , FILE_APPEND);
			file_put_contents("../logs/restore_data.log" , "[DB ERROR]" . PHP_EOL . Db::error() . PHP_EOL . PHP_EOL, FILE_APPEND);
			$error = true;
		}
	}
}

die("DONE");

?>
