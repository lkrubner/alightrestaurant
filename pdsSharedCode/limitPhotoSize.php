<?php




function limitPhotoSize($img_file=false) {
	// 07-20-06 - I'm lifting this function from lib.php. This function is
	// used to limit the size of the images that are uploaded. People with
	// digital camera might accidentally upload a 3 meg images that is far 
	// too big for people's screens, so we resize it here. 
	//
	// NOTE: returns bool

	// 09-27-06 - need a big number to not interfere with mastheads on mjhforwomen.org
	$max_dim = 600;
	
	global $controller;
	
	if (file_exists($img_file)) {
		list ($w, $h, $type, $attr) = getimagesize ($img_file);
		if (($longest = $w) < $h) $longest = $h;
		if ($longest <= $max_dim) {
			return true;
		} else {
			$scale = ((float)$max_dim) / (float)$longest;
			$new_w = (int) ($w * $scale);
			$new_h = (int) ($h * $scale);
			$dst = imagecreatetruecolor ($new_w, $new_h);
			
			if ($type == 1) { # GIF
				$src = imagecreatefromgif ($img_file);
				imagecopyresampled ($dst, $src, 0, 0, 0, 0,
					$new_w, $new_h, $w, $h);
				return imagegif ($dst, $img_file);
			} else if ($type == 2) { #JPG
				$src = imagecreatefromjpeg ($img_file);
				imagecopyresampled ($dst, $src, 0, 0, 0, 0,
					$new_w, $new_h, $w, $h);
				return imagejpeg ($dst, $img_file);
			} else if ($type == 3) { #PNG
				$src = imagecreatefrompng ($img_file);
				imagecopyresampled ($dst, $src, 0, 0, 0, 0,
					$new_w, $new_h, $w, $h);
				return imagepng ($dst, $img_file);
			} else if ($type == 6) { #BMP
				$src = imagecreatefromwbmp ($img_file);
				imagecopyresampled ($dst, $src, 0, 0, 0, 0,
					$new_w, $new_h, $w, $h);
				return imagewbmp ($dst, $img_file);
			}
			$controller->error("In limitPhotoSize2, it would appear we were given an image that was not a gif, jpeg, png, or bmp."); 
		}
	} else {
		$controller->error("In limitPhotoSize2() we are being told to look for the file '$img_file' but it does not seem to exist."); 
	}
}
	
	
	
?>