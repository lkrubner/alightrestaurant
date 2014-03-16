<?php



function showAllFormsInDirectoryAsLinksForScaffolding($directory=".") {
	// 11-09-06 - this is used on the index page in the scaffolding folder. It ignores the 
	// index.php page and otherwise treats all other files in that folder as a form that 
	// should be linked to. 
	// 
	// 06-22-07 - completely redoing this function today. It used to read the physical files
	// in scaffolding folder and report what was there. Now we instead look at what database
	// tables exist, and from that we derive the names of forms that should exist, and then
	// we check to see what exists, and if a file exists we create a link to it. The idea
	// is to provide a convenient list of what forms exist, so people can interact with them.

	global $controller; 

	$arrayOfAllTables = $controller->command("getListOfAllTables"); 
	sort($arrayOfAllTables);	
	
	if (is_array($arrayOfAllTables)) {
		
		for ($i=0; $i < count($arrayOfAllTables); $i++) {
			$row = $arrayOfAllTables[$i];
			list($key, $tableName) = each($row); 
					
			echo "<h7>$tableName</h7>";
			
			// 06-22-07 - the scaffolding software generates form names in this format:
			//				
			//			tableName_public.htm
			//			tableName_edit_Form.htm
			//			tableName_list_Form.htm
			//			tableName_list_each_Form.htm
			//			tableName_list_each_public.htm
			//			tableName_list_public.htm
			
				
			$formName = $tableName."_edit_Form.htm";
			if ($controller->command("checkForFileAndReturnTrueIfItExists", $formName)) {
				echo "<p><a href=\"index.php?formName=$formName\">$formName</a> <a title=\"Edit?\" href=\"index.php?formName=editFileSourceCode.htm&fileToEdit=$formName\">#</a> <a title=\"Delete?\" href=\"index.php?formName=deleteFile.htm&fileToEdit=$formName\">*</a> <a title=\"Move to site_specific_files?\" href=\"index.php?formName=moveFileToSiteSpecificFiles.htm&fileToEdit=$formName\">+</a></p> \n";
			}
			
			$formName = $tableName."_list_Form.htm";
			if ($controller->command("checkForFileAndReturnTrueIfItExists", $formName)) {
				echo "<p><a href=\"index.php?formName=$formName\">$formName</a> <a title=\"Edit?\" href=\"index.php?formName=editFileSourceCode.htm&fileToEdit=$formName\">#</a> <a title=\"Delete?\" href=\"index.php?formName=deleteFile.htm&fileToEdit=$formName\">*</a> <a title=\"Move to site_specific_files?\" href=\"index.php?formName=moveFileToSiteSpecificFiles.htm&fileToEdit=$formName\">+</a></p> \n";
			}

			$formName = $tableName."_list_each_Form.htm";
			if ($controller->command("checkForFileAndReturnTrueIfItExists", $formName)) {
				echo "<p><a href=\"index.php?formName=$formName\">$formName</a> <a title=\"Edit?\" href=\"index.php?formName=editFileSourceCode.htm&fileToEdit=$formName\">#</a> <a title=\"Delete?\" href=\"index.php?formName=deleteFile.htm&fileToEdit=$formName\">*</a> <a title=\"Move to site_specific_files?\" href=\"index.php?formName=moveFileToSiteSpecificFiles.htm&fileToEdit=$formName\">+</a></p> \n";
			}

			$formName = $tableName."_list_each_public.htm";
			if ($controller->command("checkForFileAndReturnTrueIfItExists", $formName)) {
				echo "<p><a href=\"index.php?formName=$formName\">$formName</a> <a title=\"Edit?\" href=\"index.php?formName=editFileSourceCode.htm&fileToEdit=$formName\">#</a> <a title=\"Delete?\" href=\"index.php?formName=deleteFile.htm&fileToEdit=$formName\">*</a> <a title=\"Move to site_specific_files?\" href=\"index.php?formName=moveFileToSiteSpecificFiles.htm&fileToEdit=$formName\">+</a></p> \n";
			}

			$formName = $tableName."_list_public.htm";
			if ($controller->command("checkForFileAndReturnTrueIfItExists", $formName)) {
				echo "<p><a href=\"index.php?formName=$formName\">$formName</a> <a title=\"Edit?\" href=\"index.php?formName=editFileSourceCode.htm&fileToEdit=$formName\">#</a> <a title=\"Delete?\" href=\"index.php?formName=deleteFile.htm&fileToEdit=$formName\">*</a> <a title=\"Move to site_specific_files?\" href=\"index.php?formName=moveFileToSiteSpecificFiles.htm&fileToEdit=$formName\">+</a></p> \n";
			}

			$formName = $tableName."_public.htm";
			if ($controller->command("checkForFileAndReturnTrueIfItExists", $formName)) {
				echo "<p><a href=\"index.php?formName=$formName\">$formName</a> <a title=\"Edit?\" href=\"index.php?formName=editFileSourceCode.htm&fileToEdit=$formName\">#</a> <a title=\"Delete?\" href=\"index.php?formName=deleteFile.htm&fileToEdit=$formName\">*</a> <a title=\"Move to site_specific_files?\" href=\"index.php?formName=moveFileToSiteSpecificFiles.htm&fileToEdit=$formName\">+</a></p> \n";
			}
		}
	} else {
		$controller->error("In showAllFormsInDirectoryAsLinks we failed to get an array of files back from getListOfAllTables."); 
	}
}



?>