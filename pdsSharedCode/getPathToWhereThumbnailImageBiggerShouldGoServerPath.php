<?php



function getPathToWhereThumbnailImageBiggerShouldGoServerPath($linkToExistingImage=false) {
	// 05-03-07 - previously, the functiton processFinalFileNameOfFile() was using the
	// function getPathToWhereThumbnailImageShouldGo() to figure out where to save a 
	// thumbnail of a recently created image. But I think I changed that function
	// so it started returning the url path (not the server path) for the thumbnail.
	// That function is now in use in templates, helping fill in the src attribute
	// of image tags. But now I need a function that can return the server path. 
	// So that is what this function will now do. 
	//
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
// str_replace and therefore necessitates the function getPathToWhereThumbnailImageBiggerShouldGoServerPath.php
//
//			$pathToWhereThumbnailShouldGo = str_replace(".", "_thumb.", $pathToWhereThumbnailShouldGo);

			$pathToWhereThumbnailShouldGo = "thumb_big_" . $pathToWhereThumbnailShouldGo;
			$pathToWhereThumbnailShouldGo = $pathToSiteSpecificFiles.$pathToWhereThumbnailShouldGo;
			return $pathToWhereThumbnailShouldGo; 
		} else {
			$controller->error("In getPathToWhereThumbnailImageBiggerShouldGoServerPath we were given the path '$linkToExistingImage' which should be the path to an existing file, but no such file exists."); 
		}
	} else {
		$controller->error("In getPathToWhereThumbnailImageBiggerShouldGoServerPath we expected to be given a valid path to an existing image, but we were given nothing."); 
	}
}



?>