<?php



function showImageInFormIfItExists() {
	// 01-08-07 - its nice to have a function in forms that makes an image appear if one
	// is part of the database table. We use a naming convention to indicate an image
	// field in the database - there should be a field called 'upload_file' in the 
	// database table

	global $controller; 
	
	$pathToSiteSpecificFiles = $controller->getVar("pathToSiteSpecificFiles"); 
	$pathToImageFolder = $controller->getVar("pathToImageFolder"); 
	
	$imageName = $controller->command("currentValue", "upload_file", "", "return") ;
	if (stristr($imageName, ".jpg") || stristr($imageName, ".jpeg") || stristr($imageName, ".jpe") || stristr($imageName, ".gif") || stristr($imageName, ".png") || stristr($imageName, ".bmp")) {
		$pathToCheck = $pathToSiteSpecificFiles."thumb_".$imageName; 
		$pathToImage = $pathToImageFolder."thumb_".$imageName;
		if (file_exists($pathToCheck)) {
			$controler->addToOutput("<img alt=\"$imageName\" src=\"$pathToImage\" /> \n");
		} else {
			$controller->error("In showImageInFormIfItExists we tried to show the form '$pathToImage' but we were told it doesn't exist. Check the path information set in the config file."); 
		}
	}
}



?>