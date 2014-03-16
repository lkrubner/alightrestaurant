<?php



function createHtmlForPublicListsOfPagesFromADatabaseTable($nameOfTable=false) {
	// 01-08-07 - the function createPublicPages got too complicated, so we are refactoring
	// it today.This is now mostly called from generateScaffoldingFiles(). See 
	// generateScaffoldingFiles for more details. 
	//
	// This function is meant to create the page that lists items from a database. Suppose we
	// have a database table called "nurses". Suppose the user enters in records for 8 
	// nurses. This function creates the HTML and PHP (which then becomes a file) which
	// would list those 8 nurses for the public to see. The HTML here is very rough - 
	// it is almost certain that the designer will want to rework this. This is only
	// meant to give the designer something to start with. 

	global $controller; 

	if ($nameOfTable) {
		// 11-05-06 - the designer can always change these names if they way, but we must
		// have some file names, so these will be the defaults. 
		$nameOfListPage = $nameOfTable."_list_public.htm"; 
		$nameOfListPageEach = $nameOfTable."_list_each_public.htm"; 
		
		// 11-05-06 - we have to embed the PHP tags in our HTML string without causing a great many errors
		$phpStart = "<";
		$phpStart .= "?"; 
		$phpStart .= "php"; 
		$phpStop = "?";
		$phpStop .= ">";
	
		// 11-05-06 - here is the page that gets every item from the database and then hands each 
		// row to another page to format the output. 
		$stringForListPage = "
	
			$phpStart getAll(\"$nameOfTable\", \"$nameOfListPageEach\"); $phpStop
		
		";
	
		$result = $controller->command("writeFileToDisk", $nameOfListPage, $stringForListPage, "./");
		if ($result) {
			$controller->addToResults("Success. We wrote the file <a href=\"index.php?formName=$nameOfListPage\">$nameOfListPage</a> to disk."); 
		} else {
			$controller->addToResults("Error: unable to write the file '$nameOfListEachForm'."); 
		}
	}
}



?>