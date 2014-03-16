<?php



function moveFileToSiteSpecificFiles() {
	// 05-14-07 - this is being called from here (an example url): 
	//
	// http://www.ihanuman.com/scaffolding/index.php?formName=moveFileToSiteSpecificFiles.htm&fileToEdit=CubeCart_inventory_list_Form.htm
	//
	// The idea here is to move a file out of the scaffolding folder and place it in the 
	// site_specific_files folder. For obvious reasons, the scaffolding folder should not 
	// be left up on a live site. It is a security risk, but also, even more important, 
	// it creates the danger of accidentally creating new versions of files when the old 
	// versions are what is wanted. However, for programmers, we want to maximize the ease
	// with which experimentation and variation can happen in the early phases of development
	// and so we want to make it easy for programmers to create new files in scaffolding, 
	// move it to site_specific_files when they like the version they've got, and then 
	// continue to experiment on a file of the same name in scaffolding, knowing that they've
	// got a version of the file that they like waiting for them in site_specific_files. 
	
	global $controller; 
	
	$pathToSiteSpecificFiles = $controller->getVar("pathToSiteSpecificFiles"); 

	
	$fileToEdit = $controller->getVar("fileToEdit"); 
	
	
		// 05-14-07 - Since there is no specified path for the scaffolding, we will 
	// use the one for site_specific_files, remove the site_specific_files 
	// directory, put the scaffolding directory onto the path, and then try 
	// it, and see if it works. 
	$pathToScaffolding = str_replace("site_specific_files", "scaffolding", $pathToSiteSpecificFiles); 
	$pathToFileInScaffolding = $pathToScaffolding.$fileToEdit; 

	$pathToFileInSiteSpecificFiles = $pathToSiteSpecificFiles.$fileToEdit; 
	
	if (@ file_exists($pathToFileInScaffolding)) {
		$fileWasCopied =  copy($pathToFileInScaffolding, $pathToFileInSiteSpecificFiles);
		chmod($pathToFileInSiteSpecificFiles, 0777);
	
		if ($fileWasCopied) {
			$controller->addToResults("We copied the file '$fileToEdit' to the folder site_specific_files."); 
			// 05-14-07 - once we've copied the file, we need to delete it from scaffolding. 
			$controller->command("deleteFileFromScaffolding", $fileToEdit); 
		} else {
			$controller->addToResults("Error: we were asked to copy the file '$fileToEdit' from '$pathToFileInScaffolding' to '$pathToFileInSiteSpecificFiles', but the copying failed, for reasons unknown. The file did exist, so check permissions on the file, and the path to site_specific_files. Also, check to see if another copy of the file already existed in the folder 'site_specific_files'. If it was put there via FTP, then you should suspect permissions problems."); 
		}
	} else {
		$controller->error("In deleteFileFromScaffolding() we were told to delete the file '$pathToFile' but we could find no such file."); 
	}
}



?>