<?php



function writeFileToDisk($fileName=false, $someContent=false, $nameOfFolderToWriteTo="site_specific_files", $modeForWritingFile="w+") {
	// 11-05-06 - I'm sure this function will have many uses, but for now I'm inventing it for use 
	// in createAdminForms(). This function simply takes a file name and some content and writes the
	// content to that file name. The one software specific thing we are doing here is that we are
	// making sure the file gets created in the folder called site_specific_files. 

	global $controller; 
	
	$folderPath = $nameOfFolderToWriteTo;
	if (!file_exists($folderPath)) {
		$folderPath = "../site_specific_files/"; 
		if (!file_exists($folderPath)) {
			$controller->addToResults("Sorry, but we can not find the folder '$folderPath' nor can we find the folder 'site_specific_files'.");
			$controller->error("In writeFileToDisk we can not find the folder '$folderPath' nor can we find the folder 'site_specific_files'.");
			return false; 
		}
	}
	
	if (!is_writable($folderPath)) {
		$controller->addToResults("Sorry, but the folder '$folderPath' is not writeable. This suggests that the site has not been properly set up. Please chmod the folder to 777..");
		$controller->error("In writeFileToDisk we find that the folder '$folderPath' is not writeable. This suggests that the site has not been properly set up. Please chmod the folder to 777.");
		return false; 
	}



	// 08-12-07- for some reason I'm getting this error:
	//
	// Warning: fopen(site_specific_filescommunity_post.xml) [function.fopen]: failed to open stream: Permission 
	// denied in /var/www/vhosts/thesecondroad.org/httpdocs/secondroad/sharedCode/writeFileToDisk.php on line 36
	//
	// so I  need to make sure there is a forward slash in the folderPath
	if (substr($folderPath, -1) != "/") $folderPath .= "/";
	$fileName = $folderPath.$fileName; 

	// Let's make sure the file exists and is writable first.
	if (is_writable($folderPath)) {
		// In our example we're opening $fileName in append mode.
		// The file pointer is at the bottom of the file hence
		// that's where $someContent will go when we fwrite() it.
		if (!$handle = fopen($fileName, $modeForWritingFile)) {
			$controller->addToResults("Cannot open file ($fileName)");
			$controller->error("In writeFileToDisk(), we cannot open file ($fileName)");
			return false; 
		}
		
		// Write $someContent to our opened file.
		if (fwrite($handle, $someContent) === FALSE) {
			$controller->addToResults("Cannot write to file ($fileName)");
			$controller->error("In writeFileToDisk, we cannot write to file ($fileName)");
			return false; 
		}
				
		$changedMode = @ chmod($fileName, 0777);
		if (!$changedMode) {
			// 10-04 -07 - the only way this can be an error is if we get the permissions on the
			// the file and check to see if the permissions are not 777. 
			//
			$perms = substr(sprintf('%o', fileperms($fileName)), -4);
			if ($perms != "0777") {
				$controller->error("In writeFileToDisk we failed to change the permissions on the file '$fileName'."); 
			}
		}

		fclose($handle);
		return $fileName; 
	} else {
		$controller->addToOutput("The file $fileName is not writable");
	}	
}



?>