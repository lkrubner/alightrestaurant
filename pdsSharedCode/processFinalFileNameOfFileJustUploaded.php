<?php



function processFinalFileNameOfFileJustUploaded($finalFileName=false, $arrayOfInfoForOneUploadedFile=false,  $whichDatabaseTable=false, $id=false) {
	// 07-16-06 - we need to make a record of this image, updating
	// whatever record in the database with this new info. Remember
	// that the user may have uploaded an image that has the same name
	// as an older image, in which the code has changed the name, but
	// the username was initially stored in the database, and its
	// important that we overwrite that. 
	
	global $controller; 
	
	$controller->command("makeDatabaseEntryForUploadedFiles", $finalFileName,  $whichDatabaseTable, $id);
	$link = $controller->command("getLinkToThisImage", $finalFileName); 
	
	// 03-30-07 - I just uploaded an MP3 to the digital store on monkeycalaus, and I got an 
	// error message from makeCroppedPhoto. So I'm adding this next line to check and see if
	// we are dealing with an image. If we are not dealing with an image, we should not
	// try to make a cropped photo. The code that calls makeCroppedPhoto (this function)
	// should check and see if a file is an image before makeCroppedPhoto is called. 
	$imageName = basename($link); 
	if (stristr($imageName, ".jpg") || stristr($imageName, ".jpeg") || stristr($imageName, ".jpe") || stristr($imageName, ".gif") || stristr($imageName, ".png") || stristr($imageName, ".bmp")) {	
		$controller->command("limitPhotoSize", $link); 
				
		$pathToWhereThumbnailShouldGo = $controller->command("getPathToWhereThumbnailImageShouldGoServerPath", $link); 
		// 09-21-06 - makeCroppedPhoto returns a boolean
		$successfullyCreatedThumbnail = $controller->command("makeCroppedPhoto", $link, $pathToWhereThumbnailShouldGo, 90, 90);
		
		// 05-03-07 - Peter Agelasto is requesting two different sizes of thumbnails for the iHanuman site. So
		// I'm creating a new function that auto generates a slightly larger thumbnail. 
		$pathToWhereThumbnailBiggerShouldGo = $controller->command("getPathToWhereThumbnailImageBiggerShouldGoServerPath", $link); 
		$successfullyCreatedThumbnail = $controller->command("makeCroppedPhoto", $link, $pathToWhereThumbnailBiggerShouldGo, 110, 160);
		
		if ($successfullyCreatedThumbnail) {
			$controller->addToResults("<img class=\"uploadedThumbnailImage\" src=\"$pathToWhereThumbnailShouldGo\" />"); 	
			return $pathToWhereThumbnailShouldGo; 
		} else {
			$controller->error("In processFinalFileNameOfFile, we tried to use makeCroppedPhoto to make a thumbnail image called '$thumbnailLink' from the image '$link', but we failed."); 
			return false; 
		}
	} else {
		$controller->addToResults("<a href=\"$link\" title=\"Link to $finalFileName\">File uploaded: $finalFileName</a>\n"); 	
		return $link; 
	}
}



?>