<?php



function uploadImagesEach($arrayOfInfoForOneUploadedFile=false,  $whichDatabaseTable=false, $id=false) {
	// 09-21-06 - this is being called from uploadImages. It is called once
	// for every file that was just successfully uploaded.  It is being given
	// an array of info whose structure is this: 
	//
	//		$arrayOfInfoForOneUploadedFile["name"] 
	//		$arrayOfInfoForOneUploadedFile["tmp_name"] 
	//		$arrayOfInfoForOneUploadedFile["size"] 
	//		$arrayOfInfoForOneUploadedFile["type"] 
	//
	// Every website is different, and the way images need to be handled is
	// especially different, so this function will probably be overridden on
	// every website. "Overridden" in this case means that a copy of this file
	// should be modified and then put in site_specific_files. This, the original
	// file, lives in sharedCode and does not need to be touched. 

	global $controller; 

	$singletonJustUploadedImagesObject = & $controller->getObject("SingletonAllJustUploadedImages", "uploadedImagesEach");
			
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
	global $pathToSiteSpecificFiles;
	$finalFileName = $controller->command("copyFileToServer", $arrayOfInfoForOneUploadedFile, $pathToSiteSpecificFiles); 
	
	if ($finalFileName != "") {
		$controller->command("processFinalFileNameOfFileJustUploaded", $finalFileName, $arrayOfInfoForOneUploadedFile,  $whichDatabaseTable, $id); 
		$singletonJustUploadedImagesObject->set($finalFileName);
		return $finalFileName; 	
	} else {
		$name = $arrayOfInfoForOneUploadedFile["name"];
		$controller->error("In uploadImages(), for some reason we could not upload or secure the file '$name'.");
	}

}



?>