<?php



function getFilesWhichHaveACertainFileExtension($extension=false) {
	// 04-09-07 - really, what I want is a drop down box that lists the files
	// on the server. I especially want it for this page: 
	//
	// http://www.ihanuman.com/store/
	//
	// This function is going be to called in getFilesInDropDownBox(). 
	
	global $controller; 

	$arrayOfMatchingFiles = array(); 

	$arrayOfFilesInDirectory = $controller->command("getFilesInDirectory"); 



	if (is_array($arrayOfFilesInDirectory)) {
		// 08-10-08 - we want these sorted.
		sort($arrayOfFilesInDirectory); 
		for ($i=0; $i < count($arrayOfFilesInDirectory); $i++) {
			$fileName = $arrayOfFilesInDirectory[$i];
			// 06-08-07 - we probably don't want thumbnails of images showing
			// up in our drop down boxes.
			if (!stristr($fileName, "thumb_") && !stristr($fileName, "small_") && !stristr($fileName, "medium_")) {	
				$endOfFile = substr($fileName, -6); 
				if (stristr($endOfFile, $extension)) $arrayOfMatchingFiles[] = $fileName;
			}
		}
		return $arrayOfMatchingFiles;
	} else {
		$controller->error("In getFilesWhichHaveACertainFileExtension() we failed to get an array back from getFilesInDirectory()");
	}
}



?>