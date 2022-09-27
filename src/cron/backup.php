<?php

	$path = realpath(dirname('../index.php')) . "/";
	$backup_dir = $path . ".backup/";
	if(!is_dir( $backup_dir )) {
		mkdir($backup_dir);
		chmod( $backup_dir, 0777);
	}

	// backup files
	$zip_file = $backup_dir.'backup_'.date('Y-m-d_H-i-s').'.tar.gz';
	$sql_file = $backup_dir.'backup_'.date('Y-m-d_H-i-s').'.sql';
	exec('tar --exclude="'.$backup_dir.'" -czf '.$zip_file.' '.$path);

	// backup database
	include $path . "include/config/main.php";
	include $path . "classes/mysqli.class.php";

	backup_tables($sql_file, $config['db_host'],$config['db_user'],$config['db_pass'],$config['db_name'],$tables = '*');

	/* backup the db OR just a table */
	function backup_tables($sql_file, $host,$user,$pass,$name,$tables = '*')
	{

		$link = mysqli_connect($host, $user, $pass, $name);
		if(!$link) {
			echo mysqli_connect_error();
			exit;
		}

		//get all of the tables
		if($tables == '*')
		{
			$tables = array();
			$result = mysqli_query($link, 'SHOW TABLES');
			while($row = mysqli_fetch_row($result))
			{
				$tables[] = $row[0];
			}
		}
		else
		{
			$tables = is_array($tables) ? $tables : explode(',',$tables);
		}

		//cycle through
		foreach($tables as $table)
		{
			$result = mysqli_query($link, 'SELECT * FROM '.$table);
			$num_fields = mysqli_num_fields($result);

			$return.= 'DROP TABLE '.$table.';';
			$row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE '.$table));
			$return.= "\n\n".$row2[1].";\n\n";

			for ($i = 0; $i < $num_fields; $i++)
			{
				while($row = mysqli_fetch_row($result))
				{
					$return.= 'INSERT INTO '.$table.' VALUES(';
					for($j=0; $j<$num_fields; $j++)
					{
						$row[$j] = addslashes($row[$j]);
						$row[$j] = preg_replace("\n","\\n",$row[$j]);
						if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
						if ($j<($num_fields-1)) { $return.= ','; }
					}
					$return.= ");\n";
				}
			}
			$return.="\n\n\n";
		}

		//save file
		$handle = fopen($sql_file,'w+');
		fwrite($handle,$return);
		fclose($handle);
	}

	function filesize_formatted($path)
	{
		if(file_exists($path)) {
		    $size = filesize($path);
		    return $size;
		    $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		    $power = $size > 0 ? floor(log($size, 1024)) : 0;
		    return (number_format($size / pow(1024, $power), 2, '.', ',') * 10240) . ' ' . $units[$power];
	    }
	}

	echo "<h3>Backup został wykonany w postaci plików:</h3><p>- " .$zip_file . "</p><p>- " . $sql_file . "</p><br/></br><p>" . "Dostęp do plików z poziomu FTP " . $path . "cron/backup/" . "</p>";
	exit;
?>
