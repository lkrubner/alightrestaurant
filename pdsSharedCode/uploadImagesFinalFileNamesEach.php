<?php



function uploadImagesFinalFileNamesEach($finalFileName=false) {
	// 9-21-06 - this is being called by loopArray, which is being called by uploadImages.
	// An array of all the files that were just successfully uploaded is being fed to
	// loopArray, which then calls this function once for each name. We are simply 
	// creating a friendly message for the user, or in the case of images, we are
	// showing the thumbnails. 
	
	global $controller; 
	$pathToSiteSpecificFiles = $controller->getVar("pathToSiteSpecificFiles"); 



// 03-30-07 - I don't like this way of doing it, exactly because it requires a PHP
// str_replace and therefore necessitates the function getPathToWhereThumbnailImageShouldGo.php
//	$thumb = str_replace(".", "_thumb.", $finalFileName); 	
	$thumb = "thumb_".$finalFileName; 
	$pathToThumbnails = $pathToSiteSpecificFiles.$thumb;
	
	if (file_exists($pathToThumbnails)) {
		$controller->addToResults("<img class\"newThumbnailImages\" src=\"$pathToThumbnails\" />"); 
	} else {
		$controller->error("In uploadImagesFinalFileNamesEach we looked for the thumbnail for the file '$finalFileName' but we could not find it at the path '$pathToThumbnails'."); 
	}
}



?>