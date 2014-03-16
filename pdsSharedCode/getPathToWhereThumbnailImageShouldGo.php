<?php



function getPathToWhereThumbnailImageShouldGo($linkToExistingImage=false) {
	// 09-21-06 - this is being called from processFinalFileNameOfFileJustUploaded.
	// It's being given a valid path to an existing image, and its returning 
	// a path that can be used to write a thumbnail image. 
	//
	// 03-30-07 - now called from uploadImagesFinalFileNamesEach

	global $controller; 
	
	$pathToSiteSpecificFiles = $controller->getVar("pathToSiteSpecificFiles"); 
	$pathToImageFolder = $controller->getVar("pathToImageFolder"); 
		
	// 04-16-07 - given only the basename for the image, we will try to find
	// the directory path to the image.
	if (!file_exists($linkToExistingImage)) $linkToExistingImage = $pathToSiteSpecificFiles.$linkToExistingImage;  

	if ($linkToExistingImage) {
		if (@ file_exists($linkToExistingImage)) {
			$pathToWhereThumbnailShouldGo = basename($linkToExistingImage);

// 03-30-07 - I don't like this way of doing it, exactly because it requires a PHP
// str_replace and therefore necessitates the function getPathToWhereThumbnailImageShouldGo.php
//
//			$pathToWhereThumbnailShouldGo = str_replace(".", "_thumb.", $pathToWhereThumbnailShouldGo);

			$pathToWhereThumbnailShouldGo = "thumb_" . $pathToWhereThumbnailShouldGo;
			$pathToWhereThumbnailShouldGo = $pathToImageFolder.$pathToWhereThumbnailShouldGo;
			return $pathToWhereThumbnailShouldGo; 
		} else {
			$controller->error("In getPathToWhereThumbnailImageShouldGo we were given the path '$linkToExistingImage' which should be the path to an existing file, but no such file exists."); 
		}
	} else {
		$controller->error("In getPathToWhereThumbnailImageShouldGo we expected to be given a valid path to an existing image, but we were given nothing."); 
	}
}



?>