<?php



function getStringOfHtmlForOwningPageWhenCreatingNewForms($nameOfOwningTable=false, $nameOfOwnedTable=false, $fieldIndicatingOwnership=false) {
	// 12-11-06 - this is being used in createPublicPages and createAdminForms. Both of those functions
	// sometimes need to rewrite pages for database tables other than the ones they are currently processing.
	// If they process a database table that belongs to (has a many to one relationship with) another table
	// then that other table needs to have its main public page rewritten. 
	
	global $controller; 

	$nameOfOneItemPage = "show_".$nameOfTable.".htm"; 
	$nameOfListPageEach = "list_".$nameOfOwnedTable."_each.htm"; 
	
	if ($nameOfOwningTable && $nameOfOwnedTable) {
		// 11-05-06 - the hardest thing we have to do is embed the PHP tags without causing a great many errors
		$phpStart = "<";
		$phpStart .= "?"; 
		$phpStart .= "php"; 
		$phpStop = "?";
		$phpStop .= ">";

		$arrayOfDatabaseTableFields = $controller->command("getListOfFieldsForDatabaseTable", $nameOfTable); 


		// 11-06-06 - we will make a big string out of all values and use that as the basis of the Show page
		// and also the Each page when listing items. 
		$stringOfValuesForIndividualPage = ""; 
		for ($i=0; $i < count($arrayOfDatabaseTableFields); $i++) {
			$fieldName = $arrayOfDatabaseTableFields[$i];
			$publicName = str_replace("_", " ", $fieldName); 
			$publicName = ucfirst($publicName); 
			// 11-08-06 - don't do anything if the field is id. Why show the id to the public? 
			if ($fieldName != "id") {
				if ($i == 1) {
					$stringOfValuesForIndividualPage .= "<h1>$phpStart currentValueFromLists(\"$fieldName\"); $phpStop </h1>\n\n";
				} else {
					$stringOfValuesForIndividualPage .= "<p>$phpStart currentValueFromLists(\"$fieldName\"); $phpStop </p>\n\n";
				}
			} 
		}

	
		// 11-06-06 - this is the public page where the public gets to see this information. 
		$stringForOneItemPage = "

			$phpStart setEntryForPage(\"$nameOfTable\"); $phpStop 

			$stringOfValuesForIndividualPage

			<hr>

			<p>These entries belong to this item:</p>

			$phpStart listEntriesInDatabase(\"$nameOfOwnedTable\", \"$nameOfListPageEach\", \"\", \"$fieldIndicatingOwnership\"); $phpStop
	
		";
		 

		$result = $controller->command("writeFileToDisk", $nameOfOneItemPage, $stringForOneItemPage, "./" );
		if ($result) {
			$controller->addToResults("Success. We wrote the file <a href=\"index.php?formName=$nameOfOneItemPage\">$nameOfOneItemPage</a> to disk."); 
		} else {
			$controller->addToResults("Error: unable to write the file '$nameOfListForm'."); 
		}
	} else {
		$controller->error("In getStringOfHtmlForOwningPageWhenCreatingNewForms we expected to be told the names of two database tables, the owning table and the owned table, but all we got was '$nameOfOwningTable' and '$nameOfOwnedTable'."); 
	}
}



?>