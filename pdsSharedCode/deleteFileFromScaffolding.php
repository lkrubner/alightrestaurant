<?php



function deleteFileFromScaffolding($fileToEdit=false) {
	// 05-14-07 - this is being called from this url: 
	//
	// http://www.ihanuman.com/scaffolding/index.php?formName=deleteFile.htm&fileToEdit=CubeCart_customer_edit_Form.htm
	//
	// A hidden input on that form, with a name of "fileToEdit", tells us the name of the form to delete. 
	//
	// The goal here is to give programmers an easy way to delete forms from scaffolding. We will
	// also, elsewhere in the code, give them a way to copy files from scaffolding to site_specific_files.
	// This function gives them the power to eliminate the file from scaffolding once the file has
	// been moved to site_specific_files. 
	//
	// @param - fileToEdit - string - optional - this function is called from moveFileToSiteSpecificFiles()
	//		and when it is called from there that function feeds in the name of the file to be deleted. 
	
	global $controller; 
	
	$pathToSiteSpecificFiles = $controller->getVar("pathToSiteSpecificFiles"); 

	
	if (!$fileToEdit) $fileToEdit = $controller->getVar("fileToEdit"); 
	
	// 05-14-07 - Since there is no specified path for the scaffolding, we will 
	// use the one for site_specific_files, remove the site_specific_files 
	// directory, put the scaffolding directory onto the path, and then try 
	// it, and see if it works. 
	$pathToScaffolding = str_replace("site_specific_files", "scaffolding", $pathToSiteSpecificFiles); 
	$pathToFile = $pathToScaffolding.$fileToEdit; 
	
	if (@ file_exists($pathToFile)) {
		$deleted = unlink($pathToFile); 
		if ($deleted) {
			$controller->addToResults("The file '$fileToEdit' was deleted from the scaffolding folder."); 
		} else {
			$controller->error("In deleteFileFromScaffolding() we were asked to delete the file '$pathToFile'. The file does exist, yet we were unable to delete it. Please check it via FTP for permissions problems."); 
		}
	} else {
		$controller->error("In deleteFileFromScaffolding() we were told to delete the file '$pathToFile' but we could find no such file."); 
	}
}



?>