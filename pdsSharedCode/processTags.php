<?php



function processTags($totalFormInputs=false) {
	// 05-14-08 - we are inventing this to handle the tags that
	// are being input off of this page: 
	// 
	// http://www.cyberbitten.com/my_private_page.php?formName=mp_personal_info.htm
	// 
	// We just modified createRecordMultipleDatabaseTables to allow
	// the form that is inputting data to specify, in a hidden input,
	// a function that should process the input. This function, processTags,
	// is the first that will take advantage of this new functionality. 

	global $controller; 

	if (is_array($totalFormInputs)) {
		while (list($whichDatabaseTable, $arrayOfFormInputArrays) = each($totalFormInputs)) {
			while (list($idOfThisEntry, $formInputs) = each($arrayOfFormInputArrays)) {
				$stringOfTags = $formInputs["tags"];
				$controller->command("addTagsForThisUser", $idOfThisEntry, $stringOfTags);
				// 05-14-08 - we have to unset the key "tags" because there is no such
				// field in the "users" database table. Since the SQL in createRecord and 
				// updateRecord is created out of the keys arrays, the keys have
				// to match what fields exist in the database table, or we will
				// get errors. 
				unset($formInputs["tags"]);
				$arrayOfFormInputArrays[$idOfThisEntry] = $formInputs;
			}
			$totalFormInputs[$whichDatabaseTable] = $arrayOfFormInputArrays;
		}
		reset($totalFormInputs); 
	}

	return $totalFormInputs;
}



?>