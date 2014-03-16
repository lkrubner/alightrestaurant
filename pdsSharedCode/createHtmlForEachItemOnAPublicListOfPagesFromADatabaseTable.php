<?php



function createHtmlForEachItemOnAPublicListOfPagesFromADatabaseTable($nameOfTable=false) {
	// 01-08-07 - the function createPublicPages got too complicated, so we are refactoring
	// it today.This is now mostly called from generateScaffoldingFiles(). See 
	// generateScaffoldingFiles for more details. 

	global $controller; 

	if ($nameOfTable) {
		// 11-05-06 - the designer can always change these names if they way, but we must
		// have some file names, so this will be the default.
		$nameOfOneItemPage = $nameOfTable."_public.htm"; 
		$nameOfListPageEach = $nameOfTable."_list_each_public.htm"; 

		// 11-05-06 - we have to embed the PHP tags in our HTML pages without causing a great many errors
		$phpStart = "<";
		$phpStart .= "?"; 
		$phpStart .= "php"; 
		$phpStop = "?";
		$phpStop .= ">";

		// 01-08-07 - we rely on the database to generate what the default pages should look like.
		$arrayOfDatabaseTableFields = $controller->command("getListOfFieldsForDatabaseTable", $nameOfTable); 

		// 01-08-07 - we will make a big HTML string. We will use the names of the field in the database
		// as our guide. We will then create a file using the HTML. 
		$stringOfValuesForListingAllEntries = ""; 
		for ($i=0; $i < count($arrayOfDatabaseTableFields); $i++) {
			$fieldName = $arrayOfDatabaseTableFields[$i];
			$publicName = str_replace("_", " ", $fieldName); 
			$publicName = ucfirst($publicName); 
			if ($fieldName == "id") {
				$stringOfValuesForListingAllEntries .= "<p><a href=\"index.php?formName=$nameOfOneItemPage&id=$phpStart currentValueFromLists(\"id\"); $phpStop\">More?</a></p> ";
			} else {
				$stringOfValuesForListingAllEntries .= " $phpStart currentValueFromLists(\"$fieldName\"); $phpStop \n\n";
			}
		}

		// 11-08-06 - the form $stringForListForm gets every item from the database and then 
		// hands each row to some function so it can be formatted. The page (or rather, page fragment)
		// that we are creating with this next string, is what is used to control how the info is 
		// formatted. 
		$stringForListPageEach = "

			$stringOfValuesForListingAllEntries

			<hr>
		";
	
		$result = $controller->command("writeFileToDisk", $nameOfListPageEach, $stringForListPageEach, "./");
		if ($result) {
			$controller->addToResults("Success. We wrote the file <a href=\"index.php?formName=$nameOfListPageEach\">$nameOfListPageEach</a> to disk."); 
		} else {
			$controller->addToResults("Error: unable to write the file '$nameOfEditForm'."); 
		}
	}
}



?>