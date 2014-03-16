<?php



function getAnArrayOfAllFilesInADirectory($directory=".") {	
	// 11-09-06 - this code is stolen almost straight from www.php.net. It simply reads all the files
	// in a directory. It is used in showAllFormsInDirectoryAsLinks. 

	global $controller; 

	$arrayOfAllFilesInDirectory = array(); 
	if ($handle = opendir($directory)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				$arrayOfAllFilesInDirectory[] =  $file;
			}
		}
		closedir($handle);
		return $arrayOfAllFilesInDirectory;
	} else {
		$controller->error("In getAnArrayOfAllFilesInADirectory we were unable to open the directory: '$directory'."); 
	}
}



?> 