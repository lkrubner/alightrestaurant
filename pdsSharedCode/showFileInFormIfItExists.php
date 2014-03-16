<?php



function showFileInFormIfItExists($imageName=false) {
	// 06-08-07 - this is probably a bad idea, but I'm going to do it anyway. I have
	// a problem right now: when I upload an image, this function tries to show me
	// an image that has the PHP temporary name. Down below I get the error message
	// which reads like this: 
	//	
	//			In showFileInFormIfItExists we tried to show the 
	//			file 'http://www.ihanuman.com/site_specific_files//tmp/phpIW7umz' 
	//			but we were told it doesn't exist. Check 
	//			the path information set in the config file.
	//
	// This function works fine on pages that show old entries, but now on new uploads.
	// So I'm going to change things. I'm going to alter the file uploadImagesEach(), 
	// which is called once per image from uploadFile(). I'm going to add a Singleton
	// object there, SingletonAllJustUploadedImages, to store the names of uploaded
	// images. I'm going to change this function so that when it is given a function
	// argument, it uses that for the image name, rather than calling currentValue().
	// And I'm going to create a new function, listAllJustUploadedImages(), and I'll 
	// use that function on forms, instead of this one. That function will get 
	// SingletonAllJustUploadedImages and loop through every image, calling this 
	// function with each image name. If SingletonAllJustUploadedImages is empty, then
	// listAllJustUpoadedImages will simply call this function once, without an
	// parameter.
	//
	//
	// 03-30-07 - this function is based on showImageInFormIfItExists, however, we
	// need to handle more than just images. This next comment is from the older
	// function: 
	//
	// 01-08-07 - its nice to have a function in forms that makes an image appear if one
	// is part of the database table. We use a naming convention to indicate an image
	// field in the database - there should be a field called 'upload_file' in the 
	// database table
	//
	// 03-30-07 - this function is being called in createTheCreateAndUpdateForm. We can
	// not assume that this field is meant to show images. We need a function more general
	// than that. We have to look at the file extension of the file, and create HTML that
	// is appropriate. 

	global $controller; 
	
	$pathToSiteSpecificFiles = $controller->getVar("pathToSiteSpecificFiles"); 
	$pathToImageFolder = $controller->getVar("pathToImageFolder"); 
	

	if (!$imageName) $imageName = $controller->command("currentValue", "upload_file", "", "return") ;

	// 03-31-07 - not much point writing HTML if the value is blank, yes? 
	if ($imageName != "") {
		if (stristr($imageName, ".jpg") || stristr($imageName, ".jpeg") || stristr($imageName, ".jpe") || stristr($imageName, ".gif") || stristr($imageName, ".png") || stristr($imageName, ".bmp")) {
			$pathToCheck = $pathToSiteSpecificFiles."thumb_".$imageName; 
			$pathToImage = $pathToImageFolder."thumb_".$imageName;
			$pathToBigImage = $pathToImageFolder.$imageName;
			
			if (file_exists($pathToCheck)) {
				$controller->addToOutput("<a href=\"$pathToBigImage\"><img alt=\"$imageName\" src=\"$pathToImage\" /></a> \n");
			} else {
				// 06-08-08 - we are having trouble with some images whereby the code seems unable to
				// to generate a thumbnail. So if the thumbnail does not exist, we will try to show
				// the original.
				$pathToCheck = $pathToSiteSpecificFiles.$imageName; 
				$pathToImage = $pathToImageFolder.$imageName;
				if (file_exists($pathToCheck)) {
					$controller->addToOutput("<a href=\"$pathToBigImage\"><img alt=\"$imageName\" src=\"$pathToImage\" /></a> \n");
				} else {			
					$controller->error("In showFileInFormIfItExists we tried to show the form '$pathToImage' but we were told it doesn't exist. Check the path information set in the config file."); 
				}
			}
		} else {
			$pathToCheck = $pathToSiteSpecificFiles.$imageName; 
			$pathToImage = $pathToImageFolder.$imageName;

			if (file_exists($pathToCheck)) {
				$controller->addToOutput("<a href=\"$pathToImage\" title=\"Link to $imageName\">$imageName</a> \n");
			} else {
				$controller->error("In showFileInFormIfItExists we tried to show the file '$pathToImage' but we were told it doesn't exist. Check the path information set in the config file."); 
			}			
		}
	}
}



?>