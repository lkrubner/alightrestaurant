<?php



function uploadFile($nameOfFile=false, $whichDatabaseTable=false, $id=false) {
	// 05-03-07 - I'm creating this function today to handle file uploads in a new way.
	// This can be considered a replacement for the function uploadImages, which was 
	// misnamed because it handled the uploads of all files. This function is being 
	// called from createRecord and updateRecord, whenever either of those functions
	// encounter a field name that contains the string 'upload_file'. 
	
	global $controller; 
	
	while (list($key, $arrayOfFileAttributes) = each($_FILES)) {
		// 05-03-07 - PHP makes things unnecessarily complicated. When I look
		// at $arrayOfFileAttributes with print_r(), I get back the following
		// very complicated array.
		//
		//	print_r($arrayOfFileAttributes); 
		//
		//
		//			Array (
		//			    [name] => Array (
		//			            [albums] => Array (
		//			                    [0] => Array (
		//			                            [upload_file] => dog.jpg
		//			                        )
		//			                )
		//			        )
		//			    [type] => Array (
		//			            [albums] => Array (
		//			                    [0] => Array (
		//			                            [upload_file] => image/jpeg
		//			                        )
		//			                )
		//			        )					
		//			    [tmp_name] => Array (
		//			            [albums] => Array (
		//			                    [0] => Array (
		//			                            [upload_file] => /tmp/phpXb9qOk
		//			                        )
		//			                )
		//			        )
		//			    [error] => Array (
		//			            [albums] => Array (
		//			                    [0] => Array (
		//			                            [upload_file] => 0
		//			                        )
		//			                )
		//			        )
		//			    [size] => Array (
		//			            [albums] => Array (
		//			                    [0] => Array (
		//			                            [upload_file] => 3694
		//			                        )
		//			                )
		//			        )
		//			)
		
		// 05-03-07 - I'm  shocked about this, but apparently the HTML form
		// that inputs the file name is getting filled by PHP with the
		// temporary name that PHP creates for the file. On other words,
		// the first parameter that this function receives is the temp
		// name of the file. Odd, I know. 
		$nameArray = $arrayOfFileAttributes["tmp_name"];
		$albumArray = current($nameArray);
		$idArray = current($albumArray); 
		$fileNameFromFilesArray = current($idArray); 		
				
		if ($nameOfFile == $fileNameFromFilesArray) {		
			$errorArray = $arrayOfFileAttributes["error"];
			$albumArray = current($errorArray);
			$idArray = current($albumArray); 
			$uploadErrorCode = current($idArray); 
				
			
			// 09-21-06 - possible values for this error code, as listed on www.php.net: 
			//		Value: 0; There is no error, the file uploaded with success. 
			//	    Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini. 
			//	    Value: 2; The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form. 
			//	    Value: 3; The uploaded file was only partially uploaded. 
			//	    Value: 4; No file was uploaded. 
			
			if ($uploadErrorCode === 1) $controller->addToResults("Sorry, but one of your files exceeded the maximum allowed size for uploaded files");  
			if ($uploadErrorCode === 2) $controller->addToResults("Sorry, but one of your files exceeded the maximum allowed size for uploaded files");  
			if ($uploadErrorCode === 3) $controller->addToResults("Sorry, but one of your files only partially uploaded. Please try to upload it again.");  
			
			// 05-03-07 - is this really an error? what about when you're creating a new record but you don't want to 
			// upload a file? I see this error message on every form, whenever I create a new entry but  don't upload
			// a file. So I think maybe I should comment out this next line. 
			//				
			//				if ($uploadErrorCode === 4) $controller->addToResults("Sorry, but one of your files failed to upload.");  
			if ($uploadErrorCode === 0) {			
				// 05-03-07 - if we get this far then we must put together a 1 dimensional array of
				// info about this particular file. 
				$arrayOfInfoAboutOneFile = array(); 

				$arrayOfInfoAboutOneFile["tmp_name"] = $fileNameFromFilesArray;
				$arrayOfInfoAboutOneFile["error"] = $uploadErrorCode;

				
									
				$nameArray = $arrayOfFileAttributes["name"];
				$albumArray = current($nameArray);
				$idArray = current($albumArray); 
				$uploadName = current($idArray); 
					
				$arrayOfInfoAboutOneFile["name"] = $uploadName;
				
				
				
									
				$sizeArray = $arrayOfFileAttributes["size"];
				$albumArray = current($sizeArray);
				$idArray = current($albumArray); 
				$uploadSize = current($idArray); 
				
				$arrayOfInfoAboutOneFile["size"] = $uploadSize;
				
				
									
				$typeArray = $arrayOfFileAttributes["type"];
				$albumArray = current($typeArray);
				$idArray = current($albumArray); 
				$uploadType = current($idArray); 
				
				$arrayOfInfoAboutOneFile["type"] = $uploadType;
				
				
				
				
				$arrayOfFinalFileNames[] = $controller->command("uploadImagesEach", $arrayOfInfoAboutOneFile, $whichDatabaseTable, $id); 
			}			
			
			return $arrayOfFinalFileNames; 
		}
	}
}



?>