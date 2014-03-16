<?php



function copyFileToServer($arrayOfFileInfo=false, $path=false) {
	// 07-16-06 - this gets called from uploadImages(). It is being handed an array that contains the 
	// name, type, size, and temporary name of a file that has just been uploaded. 

	global $controller;	
	
	$pathToSiteSpecificFiles = $controller->getVar("pathToSiteSpecificFiles"); 
	
	if (is_array($arrayOfFileInfo)) {		
	    // The original name of the file on the client machine. 
		$fileName_name = $arrayOfFileInfo['name'];
		//    The mime type of the file, if the browser provided this information. An example would be "image/gif". 
		$uploadMimeType = $arrayOfFileInfo['type'];
		//    The size, in bytes, of the uploaded file. 
		$fileName_size = $arrayOfFileInfo['size'];
		//    The temporary filename of the file in which the uploaded file was stored on the server. 
		$uploadedTempName = $arrayOfFileInfo['tmp_name'];			
		//      The error code  associated with this file upload. This element was added in PHP 4.2.0 
		$uploadError = $arrayOfFileInfo['error'];		

		if (is_writeable($path)) {					
			$fileName = $controller->command("processFileName", $fileName_name);
			if ($fileName != "") {
				$finalFileName = $controller->command("checkIfFileNameIsSafe", $fileName);			
				

				// 07-16-06- processFileNameIfFileAlreadyExists handles the problem of what to do when a file, that is
				// now being uploaded, has the same name as a file that already exists on the 
				// server. The function is called from copyFileToServer(). We add the date (the
				// current seconds of the clock) to the file name to make the name unique. 
				// It returns the server path to the file, with the name of the file altered
				// so as to be unique. 
				$absolutePath = $controller->command("processFileNameIfFileAlreadyExists", $pathToSiteSpecificFiles, $finalFileName); 	
				$finalFileName = basename($absolutePath); 
				if (@ move_uploaded_file($uploadedTempName, $absolutePath)) {
					if (file_exists($absolutePath)) {						
						chmod($absolutePath, 0777);
						return $finalFileName;
					} else {
						$controller->error("In copyFileToServer we tried to copy '$finalFileName' to '$absolutePath' but the copy failed for some reason."); 
					} 
				} else {
					$controller->addToResults("We're sorry, but your image did not upload."); 
					if (file_exists($absolutePath)) {
						$controller->error("The upload failed. The file '$absolutePath' already existed. The size of your uploaded file was $fileName_size. The type was $uploadMimeType. The error code was $uploadError. The temporary name given to it during the upload was $uploadedTempName.");
					} else {
						$controller->error("The upload failed. Your file of '$fileName_name' is not uploaded. The size was $fileName_size. The type was $uploadMimeType. The error code was $uploadError. The temporary name given to it during the upload was $uploadedTempName. The path we tried to use was $absolutePath.");
					} 
				} 
			} else {
				$controller->error("In copyFileToServer() we expected to get a name back from processFileName(), but we did not.");
			}			
		} else {
			$controller->error("In copyFileToServer, we expected to find a folder that we could copy the uploaded file to, but we could not. Make sure there is at least one folder on yur webserver that you have permission to write to."); 	
		}
	} else {
		$controller->error("In copyFileToserver() we expected to be given an array pertaining to the file that has just been uploaded, but we were not."); 	
	}
}



?>