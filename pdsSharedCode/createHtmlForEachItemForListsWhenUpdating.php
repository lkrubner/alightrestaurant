<?php



function createHtmlForEachItemForListsWhenUpdating($nameOfTable=false) {
	// 01-08-07 - this is called from generateScaffoldingFiles. 
	// This function creates the HTML, and then the file, for the file that formats how
	// each item in a list should appear. In other words, if you have a database table 
	// called "neighborhoods" and the client puts in 8 records for 8 different neighborhoods,
	// then on neighborhoods_list_Form.htm you'll list all 8 records, using the file we 
	// create below to format how each of those 8 records appears on screen (the file
	// would be called neighborhoods_list_each_Form.htm).  

	global $controller; 

	// 11-16-06 - I'm now going to call this directly from addAFieldToATable, so I'm adding $nameOfTable
	// as an optional parameter to the function. When it isn't there, we go back to looking for it in
	// the POST variables. 
	if (!$nameOfTable) $nameOfTable = $controller->getVar("nameOfTable"); 

	if ($nameOfTable) {
		// 11-05-06 - the designer can always change these names if they way, but we must
		// have some file names, so these will be the defaults. 
		$nameOfListEachForm = $nameOfTable."_list_each_Form.htm"; 
		$nameOfEditForm = $nameOfTable."_edit_Form.htm"; 
		
		// 11-05-06 - we have to embed the PHP tags in the forms without causing errors
		$phpStart = "<";
		$phpStart .= "?"; 
		$phpStart .= "php"; 
		$phpStop = "?";
		$phpStop .= ">";
	
		$arrayOfDatabaseTableFields = $controller->command("getListOfFieldsForDatabaseTable", $nameOfTable); 

		// 11-05-06 - When we are listing items to be deleted, we need to show their values. We can't know
		// what values will be needed, so it's best to include all of them and let the designer remove some of
		// them.
		$stringOfValuesForListingAllEntries = ""; 
		for ($i=0; $i < count($arrayOfDatabaseTableFields); $i++) {
			$fieldName = $arrayOfDatabaseTableFields[$i];
			$publicName = str_replace("_", " ", $fieldName); 
			$publicName = ucfirst($publicName); 
			if ($fieldName == "id") {
				$stringOfValuesForListingAllEntries .= "<p><a href=\"index.php?formName=$nameOfEditForm&id=$phpStart currentValueFromLists(\"id\"); $phpStop\">Edit?</a> ";
				$stringOfValuesForListingAllEntries .= "<br /><input type=\"checkbox\" name=\"idDel[]\" value=\"$phpStart currentValueFromLists(\"id\"); $phpStop\" /> Delete? ";
			} else {
				$stringOfValuesForListingAllEntries .= "<br />$publicName - $phpStart currentValueFromLists(\"$fieldName\"); $phpStop \n\n";
			}
		}
	
		// 11-05-06 - the form $stringForListForm gets every item from the database and then 
		// hands each row to this function so it can be formatted. 
		$stringForListEachForm = "

			$stringOfValuesForListingAllEntries

			<hr>
	
		";	
	
		$result = $controller->command("writeFileToDisk", $nameOfListEachForm, $stringForListEachForm, "./");
		if ($result) {
			$controller->addToResults("Success. We wrote the file <a href=\"index.php?formName=$nameOfListEachForm\">$nameOfListEachForm</a> to disk."); 
		} else {
			$controller->addToResults("Error: unable to write the file '$nameOfListEachForm'."); 
		}
	}
}



?>