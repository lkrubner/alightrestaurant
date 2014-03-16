<?php



function getLinkToThisImage($finalFileName=false) {
	// 09-21-06 - this is being called in processFinalFileNameOfFileJustUploaded.
	// It takes a name of a file and returns the full path to that file. 

	global $controller;
		
	$pathToSiteSpecificFiles = $controller->getVar("pathToSiteSpecificFiles"); 

	
	$link = $pathToSiteSpecificFiles.$finalFileName; 
	
	if (file_exists($link)) {
		return $link; 
	} else {
		return false; 	
	}
}



?>