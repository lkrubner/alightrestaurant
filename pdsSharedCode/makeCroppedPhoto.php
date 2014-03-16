<?php



function makeCroppedPhoto($src_path=false, $dst_path=false, $PHOTO_SIDEX = 100, $PHOTO_SIDEY = 100) {		
	// 07-27-06 - please note the way this function works. It takes a square from one image and
	// then resamples it to fit another image. The 5th, 6th, 9th and 10th parameters determine
	// which square will be taken from the original image. The 5th and 6th parameters determine
	// how much in from the left and top the image should be. Set to 0 and 0 unless you want to 
	// crop the image (which we want to do here). The 9th and 10th parameters specify how much of 
	// image should be taken, from the starting pointed specified by the 5th and 6th parameters. 
	// The 9th and 10th parameters should be equal if you want a perfect square, which we do 
	// want here. The 3rd, 4th, 7th and 8th parameters specify how big the final image should be. 
	//
	//	imageCopyResampled  ( resource dst_im, resource src_im, int dstX, int dstY, int srcX, int srcY, int dstW, int dstH, int srcW, int srcH)
	//
	// How to crop to the center of the image? In the code below, the 5th and 6th parameters are 
	// specified like this:
	//
	//  ($w - $side) / 2, ($h - $side) / 2
	//
	// $side is defined as the smallest of the sides, whichever one that is. If width is 95 and 
	// height is 145, you'll get 0 and then 25 as your parameters above. That means you'll crop
	// in somewhat from the top. 
	//
	// See this page for more good information:
	//
	// http://www.nyphp.org/content/presentations/GDintro/gd19.php
	//
	
	global $controller; 

		
	// 03-30-07 - I just uploaded an MP3 to the digital store on monkeycalaus, and I got an 
	// error message from this function. So I'm adding this next line to check and see if
	// we are dealing with an image. If we are not dealing with an image, we should not
	// try to make a cropped photo. However, I think the code that calls makeCroppedPhoto
	// should check and see if a file is an image before makeCroppedPhoto is called. So
	// I am going to call an error if this next if() statement fails, because I want to 
	// push responsibility for testing what type of file it is further upstream. 	
	$imageName = basename($src_path); 
	if (stristr($imageName, ".jpg") || stristr($imageName, ".jpeg") || stristr($imageName, ".jpe") || stristr($imageName, ".gif") || stristr($imageName, ".png") || stristr($imageName, ".bmp")) {
		if (file_exists($src_path)) {
			// 09-12-06 - the original line here was, I think, taken from www.php.net and 
			// it was not working correctly when I first try to upload files after I first 
			// log in (the type was not being captured). Turns out, there is a note from
			// 05-Feb-2006 11:57 on this page: http://us2.php.net/getimagesize
			// about just this problem.
			$arrayOfImageInfo = getimagesize($src_path);
			$w = $arrayOfImageInfo[0];
			$h = $arrayOfImageInfo[1];
			$type = $arrayOfImageInfo[2];
			if (!$type) $type = $arrayOfImageInfo["mime"];
			$attr = $arrayOfImageInfo[3];
			
			// 09-12-06 - since we're having so much trouble capturing the type right after
			// login, I'm adding a check for it. 
			if ($type) {
				if (($side = $w) > $h) $side = $h;
				$dst = imagecreatetruecolor ($PHOTO_SIDEX, $PHOTO_SIDEY);
					
				if ($type == 1 || $type == 2 || $type == 3 || $type == 6) {			
					if ($type == 1) { # GIF
						$src = imagecreatefromgif ($src_path);
						$resampled = imagecopyresampled ($dst, $src, 0, 0, ($w - $side) / 2, ($h - $side) / 2, $PHOTO_SIDEX, $PHOTO_SIDEY,	$side, $side);
						
						if ($resampled) {
							return imagegif ($dst, $dst_path);
						} else {
							$controller->error("In makeCroppedPhoto, we could not resample the image '$src_path'.");
							return false; 	
						}
					}	else if ($type == 2) { #JPG
						$src = imagecreatefromjpeg ($src_path);
						$resampled = imagecopyresampled ($dst, $src, 0, 0, ($w - $side) / 2, ($h - $side) / 2, $PHOTO_SIDEX, $PHOTO_SIDEY, $side, $side);
						
						if ($resampled) {
							return imagejpeg ($dst, $dst_path);
						} else {
							$controller->error("In makeCroppedPhoto, we could not resample the image '$src_path'.");
							return false; 	
						}
					} else if ($type == 3) { #PNG
						$src = imagecreatefrompng ($src_path);
						$resampled = imagecopyresampled ($dst, $src, 0, 0, ($w - $side) / 2,	($h - $side) / 2, $PHOTO_SIDEX, $PHOTO_SIDEY,	$side, $side);
						
						if ($resampled) {
							return imagepng ($dst, $dst_path);
						} else {
							$controller->error("In makeCroppedPhoto, we could not resample the image '$src_path'.");
							return false; 	
						}
					} else if ($type == 6) { #BMP
						$src = imagecreatefromwbmp ($src_path);
						$resampled = imagecopyresampled ($dst, $src, 0, 0, ($w - $side) / 2,	($h - $side) / 2, $PHOTO_SIDEX, $PHOTO_SIDEY,	$side, $side);
						
						if ($resampled) {
							return imagewbmp ($dst, $dst_path);
						} else {
							$controller->error("In makeCroppedPhoto, we could not resample the image '$src_path'.");
							return false; 	
						}
					}
				} else {
					$controller->error("In makeCroppedPhoto the type of image was '$type' for image '$src_path' which is not an allowed type. All that is allowed is gif(1), jpg(2), png(3) and bmp(6)."); 	
				}
			} else {
				$controller->error("In makeCroppedPhoto, even though the file '$src_path' exists, we were unable to capture its type, possibly due to bugs in the PHP program getimagesize()."); 	
			}
		} else {
			$controller->error("In makeCroppedPhoto, we were told to look for a source filed called '$src_path', but no such file existed."); 	
		}	
	} else {
		$controller->error("In makeCroppedPhoto we were given the file '$imageName' which does not appear to be an image which the code knows how to manipulate."); 
	}
}
	

	
?>