<?php



function uploadFileEach($arrayOfInfoForOneUploadedFile=false) {

	
	// 05-03-07 - this function is not in use
	
	global $controller; 
		
	// 07-16-06 - the function that handles the uploads
	// will have to rewrite the file name under two conditions:
	//
	// 1. The file name extension is a dangerous type (ie, php) and not an image type
	// 2. An image of the same name has already been uploaded.
	//
	// This next function will return false if there is an error, otherwise
	// it'll return the finalname of the file as it was written to the server.
	//
	// 09-21-06 - we have to specify, as the 3rd parameter, what directory images should
	// be saved to. 
	$pathToSiteSpecificFiles = $controller->getVar("pathToSiteSpecificFiles"); 

	
	$finalFileName = $controller->command("copyFileToServer", $arrayOfInfoForOneUploadedFile, $pathToSiteSpecificFiles); 
	
	if ($finalFileName != "") {
		$controller->command("processFinalFileNameOfFileJustUploaded", $finalFileName, $arrayOfInfoForOneUploadedFile); 
		return $finalFileName; 	
	} else {
		$name = $arrayOfInfoForOneUploadedFile["name"];
		$controller->error("In uploadImages(), for some reason we could not upload or secure the file '$name'.");
	}

}



?>