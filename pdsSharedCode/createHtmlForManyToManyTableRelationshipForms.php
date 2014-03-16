<?php



function createHtmlForManyToManyTableRelationshipForms($firstTable=false, $secondTable=false, $databaseTableName=false) {
	// 01-22-07 - this is being called from createManyToManyRelationship, which is
	// called from the form manyToManyForm.htm. 
	//
	// We need to create two forms, one that allows items from the second table to assign 
	// entries to the first table, and then another that allows many items from the first 
	// to be assigned to the second. 


	global $controller; 

	if ($firstTable && $secondTable) {
		$nameOfFirstForm = "assign_".$firstTable."_to_".$secondTable.".htm"; 
		$nameOfSecondForm = "assign_".$secondTable."_to_".$firstTable.".htm"; 
		$nameOfArrangementForFirstForm = "assign_".$firstTable."_to_".$secondTable."_each.htm"; 
		$nameOfArrangementForSecondForm = "assign_".$secondTable."_to_".$firstTable."_each.htm";

		// 11-05-06 - we have to embed the PHP tags in our forms without causing a great many errors
		$phpStart = "<";
		$phpStart .= "?"; 
		$phpStart .= "php"; 
		$phpStop = "?";
		$phpStop .= ">";

		// 01-23-07 - we need to have some field that we can use for visible information\
		// in the drop-down boxes in the forms we create below. We will simply use the first
		// field after the "id" field for this.
		$allFieldsForFirstTable = $controller->command("getListOfFieldsForDatabaseTable", $firstTable);
		$allFieldsForSecondTable = $controller->command("getListOfFieldsForDatabaseTable", $secondTable);
		$visibleFieldForFirstTable = $allFieldsForFirstTable[1];
		$visibleFieldForSecondTable = $allFieldsForSecondTable[1];

		$self = $_SERVER["PHP_SELF"];
		$formName = $controller->getVar("formName"); 

		$controller->command("createTheListViewFormForManyToMany", $firstTable, $secondTable,  $databaseTableName); 

		$stringOfHtmlForSecondForm = "
			<form method=\"post\" action=\"$self\">
				<p>Owning page: 
					<select name=\"owningPage\">
						<option value=\"\">(No choice made)</option>
						$phpStart  \$controller->command(\"getDatabaseTableValuesInOptionTags\", \"$firstTable\", \"id\", \"$visibleFieldForFirstTable\"); $phpStop
					</select></p>

				<p>Assign these items to the page above: </p>
				$phpStart \$controller->command(\"listEntriesInDatabase\", \"$secondTable\", \"$nameOfArrangementForSecondForm\"); $phpStop

				<input type=\"submit\" value=\"Assign these entries\" />
				<input type=\"hidden\" name=\"formName\" value=\"$formName\" />
				<input type=\"hidden\" name=\"choiceMade[]\" value=\"assignManyEntriesToOneEntry\" />
				<input type=\"hidden\" name=\"owningDatabaseTable\" value=\"$firstTable\" />
				<input type=\"hidden\" name=\"whichDatabaseTable\" value=\"$databaseTableName\" />
			</form>
		";

		$stringOfHtmlForFirstForm = "
			<form method=\"post\" action=\"$self\">
				<p>Owning page: 
					<select name=\"owningPage\">
						<option value=\"\">(No choice made)</option>
						$phpStart  \$controller->command(\"getDatabaseTableValuesInOptionTags\", \"$secondTable\", \"id\", \"$visibleFieldForSecondTable\"); $phpStop
					</select></p>

				<p>Assign these items to the page above: </p>
				$phpStart  \$controller->command(\"listEntriesInDatabase\", \"$firstTable\", \"$nameOfArrangementForFirstForm\"); $phpStop

				<input type=\"submit\" value=\"Assign these entries\" />
				<input type=\"hidden\" name=\"formName\" value=\"$formName\" />
				<input type=\"hidden\" name=\"choiceMade[]\" value=\"assignManyEntriesToOneEntry\" />
				<input type=\"hidden\" name=\"owningDatabaseTable\" value=\"$secondTable\" />
				<input type=\"hidden\" name=\"whichDatabaseTable\" value=\"$databaseTableName\" />
			</form>
		";

		$stringOfHtmlForFirstFormArrangement = "
			<p>$phpStart currentValueFromLists(\"$visibleFieldForFirstTable\"); $phpStop<input type=\"checkbox\" name=\"totalFormInputs[$firstTable][]\" value=\"$phpStart currentValueFromLists(\"id\"); $phpStop\" /></p>
		";

		$stringOfHtmlForSecondFormArrangement = "
			<p>$phpStart currentValueFromLists(\"$visibleFieldForSecondTable\"); $phpStop<input type=\"checkbox\" name=\"totalFormInputs[$secondTable][]\" value=\"$phpStart currentValueFromLists(\"id\"); $phpStop\" /></p>
		";





		$result = $controller->command("writeFileToDisk", $nameOfFirstForm, $stringOfHtmlForFirstForm, "./");
		if ($result) {
			$controller->addToResults("Success. We wrote the file <a href=\"index.php?formName=$nameOfFirstForm\">$nameOfFirstForm</a> to disk."); 
		} else {
			$controller->addToResults("Error: unable to write the file '$nameOfFirstForm'."); 
		}

		$result = $controller->command("writeFileToDisk", $nameOfSecondForm, $stringOfHtmlForSecondForm, "./");
		if ($result) {
			$controller->addToResults("Success. We wrote the file <a href=\"index.php?formName=$nameOfSecondForm\">$nameOfSecondForm</a> to disk."); 
		} else {
			$controller->addToResults("Error: unable to write the file '$nameOfSecondForm'."); 
		}

		$result = $controller->command("writeFileToDisk", $nameOfArrangementForFirstForm, $stringOfHtmlForFirstFormArrangement, "./");
		if ($result) {
			$controller->addToResults("Success. We wrote the file <a href=\"index.php?formName=$nameOfArrangementForFirstForm\">$nameOfArrangementForFirstForm</a> to disk."); 
		} else {
			$controller->addToResults("Error: unable to write the file '$nameOfArrangementForFirstForm'."); 
		}

		$result = $controller->command("writeFileToDisk", $nameOfArrangementForSecondForm, $stringOfHtmlForSecondFormArrangement, "./");
		if ($result) {
			$controller->addToResults("Success. We wrote the file <a href=\"index.php?formName=$nameOfArrangementForSecondForm\">$nameOfArrangementForSecondForm</a> to disk."); 
		} else {
			$controller->addToResults("Error: unable to write the file '$nameOfArrangementForSecondForm'."); 
		}
	} else {
		$controller->error("In createHtmlForManyToManyTableRelationshipForms we expected to be given the names of two database tables, but we were only given '$firstTable' and '$secondTable'. "); 
	}
}



?>
