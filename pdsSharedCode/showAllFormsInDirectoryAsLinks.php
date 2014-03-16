<?php



function showAllFormsInDirectoryAsLinks($directory=".") {
	// 11-09-06 - this is used on the index page in the scaffolding folder. It 
	// ignores the index.php page and otherwise treats all other files in that
	//  folder as a form that should be linked to. 

	global $controller; 

	$arrayOfAllFilesInDirectory = $controller->command("getAnArrayOfAllFilesInADirectory", $directory); 

	if (is_array($arrayOfAllFilesInDirectory)) {
		for ($i=0; $i < count($arrayOfAllFilesInDirectory); $i++) {
			$formName = $arrayOfAllFilesInDirectory[$i];
			if ($formName != "index.php") {
				$visibleName = str_replace("_", " ", $formName); 
				$visibleName = ucfirst($visibleName); 
				$controller->addToOutput("<p><a href=\"index.php?formName=$formName\">$visibleName</a> <a title=\"Edit?\" href=\"index.php?formName=editFileSourceCode.htm&fileToEdit=$formName\">#</a></p> \n");
			}
		}
	} else {
		$controller->error("In showAllFormsInDirectoryAsLinks we failed to get an array of files back from getAnArrayOfAllFilesInADirectory."); 
	}
}



?>