<?php



function readFileAndReturnString($fileToGet=false) {
	// 11-30-06 - We use this function internally. We use this anywhere we need to get a file as a string. 
	// The function takes one parameter, which is the name of the file. It is not a full path, just a name,
	// We then use the global variables $pathToSiteSpecificFiles and $pathToSharedCode to seek a file with
	// this name in those two locations. If the file is not in either location, we check to see if it is
	// in the same directory as the script that is using this function. If the file is not there, either, 
	// then we offer an error to whoever is using this function, telling them that the file was not found. 

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
		if (@ file_exists($fileToGet)) {
			$lines = file($fileToGet); 
		} else if (@ file_exists($scaffoldingPath)) {
			$lines = file($scaffoldingPath);
		} elseif (@ file_exists($siteSpecific)) {
			$lines = file($siteSpecific); 
		} elseif(@ file_exists($sharedCode)) {
			$lines = file($sharedCode); 
		} else {
			$controller->error("In readFileAndReturnString, we could not find the form '$fileToGet' in either '$siteSpecific' or '$sharedCode' or '$scaffoldingPath'.");
			return false; 
		} 
		
		// Loop through our array, show HTML source as HTML source; and line numbers too.
		foreach ($lines as $line_num => $line) {
			$textOfFileAsString .=  $line;
		}
			

		return $textOfFileAsString; 
	} else {
		$controller->error("In readFileAndReturnString the first parameter should have told us the name or url of the file we were suppose to look for, but the function was given nothing at all."); 
	}
}



