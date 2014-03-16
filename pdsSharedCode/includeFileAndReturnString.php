<?php



function includeFileAndReturnString($whichArrangementToUse=false, $arrayOfInfoToBePassedToArrangement=false) {
	// 05-11-08 - most of the code here is stolen from readFileAndReturnString
	// but our intention is different. Here we want to include a file, whereas
	// there we are getting a string. But we assume that our file will have 
	// string that can be read as a PHP variable. An example would be 
	// message_your_message_has_been_blocked.htm, which looks like this: 
	//		
	//		$message = <<<EOD
	//		
	//		
	//		<p>Your message to $username could not be sent at this time.</p>
	//		
	//		
	//		
	//		EOD; 
	//
	// This function is called in places like addToUserMessages. It is essential 
	// to our new system of user messages, which move the messages to arrangements.
	// One of the great weaknesses of this framework so far is that  all user
	// messages have been hard-coded. 


	global $controller; 

	if (!$whichArrangementToUse) {
		$controller->error("In includeFileAndReturnString we expected to be told what file to open, but we were not."); 
		return false; 
	}

	$pathToSiteSpecificFiles = $controller->getVar("pathToSiteSpecificFiles"); 
	$pathToSharedCode = $controller->getVar("pathToSharedCode"); 
	$pathToScaffolding = $controller->getVar("pathToScaffolding"); 

	$sharedCode = $pathToSharedCode.$whichArrangementToUse;
	$siteSpecific = $pathToSiteSpecificFiles.$whichArrangementToUse;
	$scaffoldingPath = $pathToScaffolding.$whichArrangementToUse; 

	 if (is_array($arrayOfInfoToBePassedToArrangement)) extract($arrayOfInfoToBePassedToArrangement); 

	if (@file_exists($fileToGet)) {
		include($fileToGet); 
	} else if (@file_exists($scaffoldingPath)) {
		include($scaffoldingPath);
	} elseif (@file_exists($siteSpecific)) {
		include($siteSpecific); 
	} elseif(@file_exists($sharedCode)) {
		include($sharedCode); 
	} else {
		$controller->error("In readFileAndReturnString, we could not find the form '$fileToGet' in either '$siteSpecific' or '$sharedCode' or '$scaffoldingPath'.");
		return false; 
	} 

	return $message; 
}



?>