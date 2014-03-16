<?php



function checkForFileAndReturnTrueIfItExists($fileToGet=false, $justReturnTrueIfTheFileExists=false) {
	// 06-22-07 - this function is based on readFileAndReturnString, but instead of opening the  file
	// and returning the string, we merely check to see if the file exists. This function is in use in
	// showAllFormsInDirectoryAsLinks. Older comments apply to readFileAndReturnString. 
	//	
	// 11-30-06 - 
	// The function takes one parameter, which is the name of the file. It is not a full path, just a name,
	// We then use the global variables $pathToSiteSpecificFiles and $pathToSharedCode to seek a file with
	// this name in those two locations. If the file is not in either location, we check to see if it is
	// in the same directory as the script that is using this function. If the file is not there, either, 
	// then we offer an error to whoever is using this function, telling them that the file was not found. 
	//
	// 06-22-07 - just added pathToScaffolding to the config file. 
	
	global $controller;
	
	$pathToSiteSpecificFiles = $controller->getVar("pathToSiteSpecificFiles"); 
	$pathToSharedCode = $controller->getVar("pathToSharedCode"); 
	$pathToScaffolding = $controller->getVar("pathToScaffolding"); 
	
	if ($fileToGet) {
		$textOfFileAsString = "";

		$sharedCode = $pathToSharedCode.$fileToGet;
		$siteSpecific = $pathToSiteSpecificFiles.$fileToGet;
		$scaffoldingPath = $pathToScaffolding.$fileToGet; 
	
		// 12-12-06 - possibly introducing a new bug here, but I'd prefer it if this code 
		// first looked in the current directory, and only then looked in the proper places.
		// In the scaffolding folder, I'm trying to edit a form that I've already created and
		// moved to site_specific_files, and that version overrides the new version. So I'm 
		// going to change the order of these next 3 if statements, so the first place 
		// we look is the current directory. 	
		if (file_exists($fileToGet)) {
			return true; 
		} else if (file_exists($scaffoldingPath)) {
			return true; 
		} elseif (file_exists($siteSpecific)) {
			return true; 
		} elseif(file_exists($sharedCode)) {
			return true; 
		} else {
			return false; 
		} 		
	} else {
		$controller->error("In checkForFileAndReturnTrueIfItExists the first parameter should have told us the name or url of the file we were suppose to look for, but the function was given nothing at all."); 
	}
}



?>