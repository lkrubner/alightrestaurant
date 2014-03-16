<?php



function getFilesInDirectory($pathToDir=false) {
	// 04-09-07 - really, what I want is to be able to get all mp3s or videos
	// and show them in a drop down box, but of course, I want to layer the 
	// funtionality such that each action is reusable. The first step is to 
	// get a list of every file in a directory, which is what this function
	// will accomplish. We will return a one dimensional array. 

	global $controller; 
	$pathToSiteSpecificFiles = $controller->getVar("pathToSiteSpecificFiles"); 


	if (!$pathToDir) $pathToDir = $pathToSiteSpecificFiles;
	$arrayOfFilesInDirectory = array(); 

	if ($handle = opendir($pathToDir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				$arrayOfFilesInDirectory[] = $file; 
			}
		}
		closedir($handle);

		return $arrayOfFilesInDirectory;
	} else {
		$controller->error("In getFilesInDirectory we tried to open the directory whose path was '$pathToDir' but we failed."); 
	}
}



?>