<?php



function uploadImages() {
	global $controller;


	/*

	10-16-07 - PHP 5 no longer stores uploaded files in $_POST, so I
	no longer look there to see if something has been uploaded. I will
	instead have to look in $_FILES. If I use print_r to look at 
	$_FILES, this is what I see: 

	$userArray = $_FILES["totalFormInputs"]["name"]["users"];
	$uploadFileArray = current($userArray): 
	$arrayOfImageInfo["name"] = $uploadFileArray["upload_file"];

		print_r($_FILES); 
			
			Array
			(
			[totalFormInputs] => Array
				(
				[name] => Array
					(
					[users] => Array
						(
						[5] => Array
							(
							[upload_file] => DSC00081.jpg
							)
			
						)
			
					)
			
				[type] => Array
					(
					[users] => Array
						(
						[5] => Array
							(
							[upload_file] => image/jpeg
							)
			
						)
			
					)
			
				[tmp_name] => Array
					(
					[users] => Array
						(
						[5] => Array
							(
							[upload_file] => /tmp/phpsz6IlP
							)
			
						)
			
					)
			
				[error] => Array
					(
					[users] => Array
						(
						[5] => Array
							(
							[upload_file] => 0
							)
			
						)
			
					)
			
				[size] => Array
					(
					[users] => Array
						(
						[5] => Array
							(
							[upload_file] => 54678
							)
			
						)
			
					)
			
				)
			)

*/



			
	// 10-16-07 - this next block is sort of special for www.thesecondroad.org. It 
	// may become the general way I handle stuff on servers running PHP 5. 
	$arrayOfImageInfo = array();

	$userArray = $_FILES["totalFormInputs"]["name"]["users"];
// 05-15-08 - user of "users" here assumes we are always trying to 
// change a users default image, when instead they might be trying
// to upload to image gallery. 
	if (!is_array($userArray)) $userArray = $_FILES["totalFormInputs"]["name"]["files"];
	if (!is_array($userArray)) return false; 
	$uploadFileArray = current($userArray);
	$actualName = $uploadFileArray["upload_file"];
	if ($actualName) {
		$controller->command("uploadImageTsr"); 
		return false; 
	}







	// 09-21-06 - the $_FILES array will only have one entry, no matter how many 
	// files were just uploaded. $_FILES will include the userfile array, which
	// will contain all the files just uploaded, if any. 
	$userfile = $_FILES["upload_file"];
	if (is_array($userfile)) {		
		// 07-16-06 - it's complicated how this works, but PHP structures the upload
		// array as 5 separate arrays. I could avoid this problem by given a unique
		// name to every input that is of type "file", but that would complicate 
		// a huge amount of form code, whereas this way I only have to deal with the 
		// complications once, here in this command. We will get these 5 arrays, then 
		// loop through them. 
		$arrayOfFileNames = $userfile["name"];
		$arrayOfTempNames = $userfile["tmp_name"];
		$arrayOfFileSizes = $userfile["size"];
		$arrayOfFileTypes = $userfile["type"];
		$arrayOfErrorMessages = $userfile["error"];
	
		// 07-16-06 - the temp names seem the surest way to tell that something
		// has been uploaded, so we will use this array to gauge how many times
		// we should try to upload something. The user may leave some file
		// inputs blank, but may upload an unlimited number of images, so we have
		// to deal with this in  flexible way. Suppose we have a form with 
		// 15 file inputs. PLEASE NOTE: this means there will be 15 items
		// in the temp file array, even if the user only fills in 12 of those inputs.
		// If they only fill in 12, then we will have 3 blanks rows in the array.
		// we need to check arrayOfErrorMessages, any time it has a "4" in it, we 
		// can assume we are dealing with a blank file input. 
		if (is_array($arrayOfTempNames)) {			
			$arrayOfFinalFileNames = array(); 
			$howManyFileInputsToTryToUpload = count($arrayOfTempNames); 			
			for ($i=0; $i < $howManyFileInputsToTryToUpload; $i++) {
				$uploadErrorCode = $arrayOfErrorMessages[$i];
				
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
					$arrayOfInfoForOneUploadedFile = array(); 
					$arrayOfInfoForOneUploadedFile["name"] = $arrayOfFileNames[$i]; 
					$arrayOfInfoForOneUploadedFile["tmp_name"] = $arrayOfTempNames[$i];
					$arrayOfInfoForOneUploadedFile["size"] = $arrayOfFileSizes[$i];
					$arrayOfInfoForOneUploadedFile["type"] = $arrayOfFileTypes[$i];
					
					$arrayOfFinalFileNames[] = $controller->command("uploadImagesEach", $arrayOfInfoForOneUploadedFile); 
				}
			}
			// 09-21-06 - a message is produced in lower level code
			//$controller->command("loopArray", $arrayOfFinalFileNames, "uploadImagesFinalFileNamesEach"); 
		} else {
			$controller->error("In uploadImages(), we got the array 'userfile' that suggested you were trying to upload some files, yet we could not find an array of file names inside of it to upload."); 	
		}
	}
}



?>
