<?php



function processFileNameIfFileAlreadyExists($path=false, $finalFileName=false) {
	// 07-16-06- this function handles the problem of what to do when a file, that is
	// now being uploaded, has the same name as a file that already exists on the 
	// server. The function is called from copyFileToServer(). We add the date (the
	// current seconds of the clock) to the file name to make the name unique. 
	// It returns the server path to the file, with the name of the file altered
	// so as to be unique. 
	
	global $controller;

	if (file_exists($path)) {		
		$absolutePath = $path.$finalFileName; 
		if (file_exists($absolutePath)) {
			$extNum = strpos($finalFileName, ".");
			// 07-16-06 - save the extension in a separate variable
			$ext = substr($finalFileName, $extNum); 
			$finalFileName = str_replace($ext, "", $finalFileName); 
			$finalFileName .= "_"; 
			$date = date(" s");
			$date = str_replace(" ", "_", $date); 
			$finalFileName .= $date;
			// 07-16-06 - put the extension back on the file
			$finalFileName .= $ext; 						
			$absolutePath = $path.$finalFileName; 		
			// 07-16-06 - if the name is not yet unique, we call this same function again,
			// recursively, until the name is unique. 
			if (file_exists($absolutePath)) {
				$absolutePath = $controller->command("processFileNameIfFileAlreadyExists", $path, $finalFileName, $originalName); 	
			}
		}	
		
		return $absolutePath; 
	} else {
		$controller->error("In processFileNameIfFileAlreadyExists we expected the first parameter to be a valid path, but instead we got '$path'."); 
	}
}



?>