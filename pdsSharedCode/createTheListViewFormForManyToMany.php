<?php



function createTheListViewFormForManyToMany($firstTable=false, $secondTable=false, $nameOfIndexTable=false) {
	// 01-08-07 - this is being called from createAdminForms, here we create the form 
	// that lists all entries in a database table. 
	//
	// 04-17-07 - we've discarded createAdminForms. This is now being called 
	// from createHtmlForManyToManyTableRelationshipForms(). 

	global $controller; 

	if ($firstTable && $secondTable) {
		// 11-05-06 - the designer can always change these names if they way, but we must
		// have some file names, so these will be the defaults. 
		//
		// 11-17-06 - a huge change, we will no longer have a separate form for creating and
		// updating an item. Instead, we will have one form. If there is an id, we will update
		// the record that has that id. If there is no id, then we will create a new record.
		// $nameOfCreateForm = "create_".$nameOfTable."_Form.htm"; 
		$nameOfListForm1 = "list_".$firstTable."_for_index.htm"; 
		$nameOfListForm2 = "list_".$secondTable."_for_index.htm"; 
		$nameOfListEachForm1 = "list_each_".$firstTable."_for_index.htm"; 
		$nameOfListEachForm2 = "list_each_".$secondTable."_for_index.htm"; 
		
		// 11-05-06 - we have to embed the PHP tags in our forms without causing a great many errors
		$phpStart = "<";
		$phpStart .= "?"; 
		$phpStart .= "php"; 
		$phpStop = "?";
		$phpStop .= ">";

		// 11-05-06 - here is the form that gets every item from the database and then hands each 
		// row to the form created by $stringForListEachForm for formatting. 
		$htmlForOwningPage1 = $controller->command("createHtmlForPublicPageForOneItemFromADatabaseTable", $firstTable, "return");
		$stringForListForm = "
			$htmlForOwningPage1
			<p>Here are the entries for $firstTable:</p>			
			$phpStart listEntriesInDatabaseIndex(\"$nameOfIndexTable\", \"$firstTable\", \"$secondTable\", \"$nameOfListEachForm2\"); $phpStop
		";

		$htmlForOwningPage2 = $controller->command("createHtmlForPublicPageForOneItemFromADatabaseTable", $secondTable, "return");
		$stringForListForm2 = "
			$htmlForOwningPage2
			<p>Here are the entries for $secondTable:</p>			
			$phpStart listEntriesInDatabaseIndex(\"$nameOfIndexTable\", \"$secondTable\", \"$firstTable\", \"$nameOfListEachForm1\"); $phpStop
		";

		$arrayOfFieldsFromFirstTable = $controller->command("getListOfFieldsForDatabaseTable", $firstTable);
		$stringForListEachItem1 = "";
		for ($i=1; $i < count($arrayOfFieldsFromFirstTable); $i++) {
			$fieldName = $arrayOfFieldsFromFirstTable[$i];
			$stringForListEachItem1 .= "
				<p>$fieldName: 	$phpStart \$controller->command(\"currentValueFromLists\", \"$fieldName\"); $phpStop </p>
			";
		}

		$arrayOfFieldsFromSecondTable = $controller->command("getListOfFieldsForDatabaseTable", $secondTable);
		$stringForListEachItem2 = "";
		for ($i=1; $i < count($arrayOfFieldsFromSecondTable); $i++) {
			$fieldName = $arrayOfFieldsFromSecondTable[$i];
			$stringForListEachItem2 .= " 
				<p> $fieldName: $phpStart \$controller->command(\"currentValueFromLists\", \"$fieldName\"); $phpStop </p>
			";
		}


	
		$result = $controller->command("writeFileToDisk", $nameOfListForm1, $stringForListForm, "./" );
		if ($result) {
			$controller->addToResults("Success. We wrote the file <a href=\"index.php?formName=$nameOfListForm1\">$nameOfListForm1</a> to disk."); 
		} else {
			$controller->addToResults("Error: unable to write the file '$nameOfListForm1'."); 
		}
	
		$result = $controller->command("writeFileToDisk", $nameOfListForm2, $stringForListForm2, "./" );
		if ($result) {
			$controller->addToResults("Success. We wrote the file <a href=\"index.php?formName=$nameOfListForm2\">$nameOfListForm2</a> to disk."); 
		} else {
			$controller->addToResults("Error: unable to write the file '$nameOfListForm2'."); 
		}	

		$result = $controller->command("writeFileToDisk", $nameOfListEachForm1, $stringForListEachItem1, "./" );
		if ($result) {
			$controller->addToResults("Success. We wrote the file <a href=\"index.php?formName=$nameOfListEachForm1\">$nameOfListEachForm1</a> to disk."); 
		} else {
			$controller->addToResults("Error: unable to write the file '$nameOfListEachForm1'."); 
		}

		$result = $controller->command("writeFileToDisk", $nameOfListEachForm2, $stringForListEachItem2, "./" );
		if ($result) {
			$controller->addToResults("Success. We wrote the file <a href=\"index.php?formName=$nameOfListEachForm2\">$nameOfListEachForm2</a> to disk."); 
		} else {
			$controller->addToResults("Error: unable to write the file '$nameOfListEachForm2'."); 
		}
	}
}



?>