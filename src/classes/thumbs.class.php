<?php
class Thumbnail
{
	public static function cropImage($nw, $nh, $source, $dest, $stype = "auto")
	{
		global $app_path;
		$stype = pathinfo($source, PATHINFO_EXTENSION);
		if (extension_loaded('imagick')) {
			self::cropImageUsingImagick($nw, $nh, $source, $dest, $stype);
		} else {
			if( !file_exists( $app_path . "logs/")) {
				mkdir( $app_path . "logs/");
			}
			file_put_contents($app_path . "logs/thumbs.log", date('Y-m-d H:i:s') . " > Extension IMAGICK is not present, using GD insted" . PHP_EOL, FILE_APPEND);
			self::cropImageUsingGD($nw, $nh, $source, $stype, $dest);
		}

	}

	public static function cropImageUsingImagick($nw, $nh, $source, $dest, $stype = "auto")
	{
		$im = new \imagick( $source );
		$im->cropThumbnailImage( $nw, $nh );
		$im->writeImage( $dest );
	}

	public static function cropImageUsingGD($nw, $nh, $source, $stype, $dest)
	{
	    $size = getimagesize($source);
	    $w = $size[0];
	    $h = $size[1];

		if($nh==0 OR $nh=='AUTO' OR $nh =='auto'):
			$prop = $w/$h;
			$nh = $nw * $prop;
		endif;

        if($stype=="auto"):
            preg_match("/[gif|png|jpg]{3}/", $source, $matches);
            $stype = $matches[0];
        endif;

	    switch($stype)
		{
			case 'gif': $simg = imagecreatefromgif($source); break;
			case 'jpg': $simg = imagecreatefromjpeg($source); break;
			case 'jpeg': $simg = imagecreatefromjpeg($source); break;
			case 'png': $simg = imagecreatefrompng($source); break;
			default: $simg = imagecreatefromjpeg($source); break;
	    }

		imagealphablending($simg, false);
        imagesavealpha($simg, true);
		$dimg = imagecreatetruecolor($nw, $nh);
		$backgroundColor = imagecolorallocate($dimg, 255, 255, 255);
		imagefill($dimg, 0, 0, $backgroundColor);
	    $wm = $w/$nw;
	    $hm = $h/$nh;
	    $h_height = $nh/2;
	    $w_height = $nw/2;
	    if($w> $h)
		{
	      $adjusted_width = $w / $hm + ($w/$nw);//+($w/6);
	      $half_width = $adjusted_width / 2.2;
	      $int_width = $half_width - $w_height;
	      imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
	    } elseif(($w <$h) || ($w == $h)) {
	      $adjusted_height = $h / $wm;
	      $half_height = $adjusted_height / 3.5;
	      $int_height = $half_height - $h_height;
	      imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
	    } else { imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h); }
	    imagejpeg($dimg,$dest,80);
	}

	public static function flop_image( $filename, $mirror_filename, $path, $path_mirror = null )
	{
		if(is_null($path_mirror)) {
			$path_mirror = $path;
		}

		if(class_exists( '\Gmagick' )) {
			$image = new \Gmagick();
			$image->readImage( $path . $filename );
			$image->flopImage();
			$image->writeImage( $path_mirror . $mirror_filename );
			$image->destroy();

			return true;
		}

		$im = imagecreatefromjpeg( $path . $filename );
		imageflip( $im, IMG_FLIP_HORIZONTAL );
		imagejpeg( $im, $path_mirror . $mirror_filename, 80 );

		return true;
	}

	public static function png2jpg( $src_file, $dest_path, $quality = 85 )
	{
		$pi = pathinfo( $src_file );
		$dest_file = $pi['filename'] . '.jpg';

		$image = imagecreatefrompng( $src_file );
		$bg = imagecreatetruecolor(imagesx($image), imagesy($image));
		imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
		imagealphablending($bg, TRUE);
		imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
		imagedestroy($image);
		imagejpeg($bg, $dest_path . $dest_file, $quality);
		imagedestroy($bg);
	}

	function resize( $after_width = 1920, $file = '', $output = '' )
	{
	    list( $width, $height, $type, $attr) = getimagesize( $file );
		if( $width > $after_width ) {
			if (extension_loaded('imagick')) {
				$reduced_width = ($width - $after_width);
		        $reduced_radio = round(($reduced_width / $width) * 100, 2);
		        $reduced_height = round(($height / 100) * $reduced_radio, 2);
		        $after_height = $height - $reduced_height;

				$thumb = new Imagick();
				$thumb->readImage($file);
				$thumb->resizeImage($after_width,$after_height,Imagick::FILTER_LANCZOS,1);
				$thumb->writeImage($output);
				$thumb->clear();
				$thumb->destroy();
			} else {
				$img = imagecreatefromjpeg( $file );
		        $reduced_width = ($width - $after_width);
		        $reduced_radio = round(($reduced_width / $width) * 100, 2);
		        $reduced_height = round(($height / 100) * $reduced_radio, 2);
		        $after_height = $height - $reduced_height;
		        $resized = imagescale( $img, $after_width, $after_height);
				imageantialias($img, true);
		        imagejpeg($resized,  $output, 100 );
			}
		}
	}
}
