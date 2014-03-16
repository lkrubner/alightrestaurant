<?php



function createTheCreateAndUpdateForm($nameOfTable=false) {
	// 01-08-07 - the function createAdminForms has grown to complicated and needs to be refactored.
	// This function does just one thing - it creates the form that allows an entry in a 
	// database to be created or updated. 


	global $controller; 

	if ($nameOfTable) {
		$publicName = str_replace("_", " ", $nameOfTable); 
		$publicName = ucfirst($publicName);

		// 01-08-07 - the designer can always change this name if they want, but this is the
		// default name for this form. 
		$nameOfEditForm = $nameOfTable."_edit_Form.htm"; 

		// 11-05-06 - we have to embed the PHP tags without causing a great many errors, so
		// we carefully create variables for these tags.
		$phpStart = "<";
		$phpStart .= "?"; 
		$phpStart .= "php"; 
		$phpStop = "?";
		$phpStop .= ">";

		// 01-08-07 - we need to have a list of all the field names in the database table we
		// are creating a form for. This scaffolding software relies on naming conventions of
		// the field names to determine what inputs the form should have and what type of HTML
		// inputs are appropriate.
		//
		// getListOfFieldsAndFieldTypesForDatabaseTable is returning a two dimensional array. Each
		// row contains the "Field" and "Type" of a column in the database. "Field" is the name of the
		// field.
		$arrayOfDatabaseTableFields = $controller->command("getListOfFieldsAndFieldTypesForDatabaseTable", $nameOfTable);

		// 01-08-07 - we will use this variable to build up a string containing all the HTML that we want 
		// to have appear in the main part of the form. Almost all HTML inputs that go into the form,
		// save for a few hidden inputs, are created here and added to the string.
		$stringForInputsWhenEditing = ""; 

				
		for ($i=0; $i < count($arrayOfDatabaseTableFields); $i++) {
			$row = $arrayOfDatabaseTableFields[$i];
			$fieldName = $row["field"];
			$fieldType = $row["type"];
			$doesThisEntryBelongToSomeOtherTable = substr($fieldName, 0, 3);
			if ($doesThisEntryBelongToSomeOtherTable == "id_") {
				$nameOfOwningDatabaseTable = substr($fieldName, 3);
				$nameOfOwningDatabaseTableForShow = ucfirst(str_replace("_", " ", $nameOfOwningDatabaseTable));
				$arrayOfDatabaseTableFieldsFromOwningTable = $controller->command("getListOfFieldsAndFieldTypesForDatabaseTable", $nameOfOwningDatabaseTable);
				$rowToGetVisibleTextForUseInSelectBox = $arrayOfDatabaseTableFieldsFromOwningTable[1];
				$fieldNameForVisibleTextForUseInSelectBox = $rowToGetVisibleTextForUseInSelectBox["field"];
				$stringForSelectBoxToAssigningOwnership .= "
					<p>Belongs to $nameOfOwningDatabaseTableForShow <select name=\"totalFormInputs[$nameOfTable][$phpStart currentValueForFormArrays(\"id\"); $phpStop][$fieldName]\">
						$phpStart createHtmlForFirstRowOfSelectBox(\"$fieldName\"); $phpStop
						$phpStart getDatabaseTableValuesInOptionTags(\"$nameOfOwningDatabaseTable\", \"id\", \"$fieldNameForVisibleTextForUseInSelectBox\"); $phpStop
					</select></p>
				";
			} else {
				$stringForInputsWhenEditing .= $controller->command("createHtmlForFormInput", $fieldName, $fieldType, "createTheCreateAndUpdateForm", $nameOfTable);
				if ($fieldName == "upload_file") $areImageUploadsWanted = true;
			}
		}

		// 01-08-07 - if images are wanted then we need to add the upload handling function to choiceMade, so we create another
		// string, which we will use to insert the hidden input that adds uploadImages to choiceMade.
		if ($areImageUploadsWanted) {
			$stringForAddingUploadImagesToChoiceMade = "<input type=\"hidden\" name=\"choiceMade[]\" value=\"uploadImages\" />";
			$stringOfHtmlToShowAnImage = "$phpStart showFileInFormIfItExists(); $phpStop";
		}
		
		// 01-08-07 - now we bring it all together, and create the HTML for the form that can both
		// add a new entry to the database as well as update an old one. 
		//
		// 11-17-06 - we no longer have a create form. We will only have one form and it will handle
		// both creation and updates. We have edited updateRecord so that if there is no id present
		// it will call createRecord. If there is an id it will update the record as before. 
		$stringForEditForm = "

			$phpStart setEntryForPage(\"$nameOfTable\"); $phpStop 

			<form method=\"post\" action=\"index.php\"  enctype=\"multipart/form-data\">
			<h3>Update or create this '$publicName' record</h3>

			$stringForSelectBoxToAssigningOwnership

			$stringForInputsWhenEditing 

			$stringOfHtmlToShowAnImage
			
			<p><input type=\"submit\" value=\"Update\" /></p>
			<input type=\"hidden\" name=\"choiceMade[]\" value=\"createRecordsForMultipleDatabaseTables\" />
			<input type=\"hidden\" name=\"formName\" value=\"$nameOfEditForm\" />
			$stringForAddingUploadImagesToChoiceMade
			</form>
	
		";

		$result = $controller->command("writeFileToDisk", $nameOfEditForm, $stringForEditForm, "./");
		if ($result) {
			$controller->addToResults("Success. We wrote the file <a href=\"index.php?formName=$nameOfEditForm\">$nameOfEditForm</a> to disk."); 
		} else {
			$controller->addToResults("Error: unable to write the file '$nameOfEditForm'."); 
		}
	}
}



?>
