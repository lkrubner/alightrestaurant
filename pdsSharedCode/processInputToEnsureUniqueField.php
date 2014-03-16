<?php



function processInputToEnsureUniqueField($totalFormInputs=false, $fieldWhichMustBeUnique=false, $valueOfInputForFieldThatMustBeUnique=false) {
	// 05-15-08 - sometimes we want to ensure that input has a field with a unique
	// value. For instance, this form creates a group, and we want to ensure
	// that the group name will be unique: 
	//
	// http://www.cyberbitten.com/my_private_page.php?formName=mp_groups.htm&editId=91
	//
	// So we are writing this function, which will get triggered in 
	// createRecordForMultipleDatabaseTables, and it will ensure that the
	// name will be unique. 

	global $controller; 

	if (!$fieldWhichMustBeUnique) $fieldWhichMustBeUnique = $controller->getVar("fieldWhichMustBeUnique"); 

	if (is_array($totalFormInputs) && $fieldWhichMustBeUnique) {
		while (list($whichDatabaseTable, $arrayOfFormInputArrays) = each($totalFormInputs)) {
			while (list($idOfThisEntry, $formInputs) = each($arrayOfFormInputArrays)) {
				if (!$valueOfInputForFieldThatMustBeUnique) $valueOfInputForFieldThatMustBeUnique = $formInputs[$fieldWhichMustBeUnique];
				$query = "SELECT $fieldWhichMustBeUnique FROM $whichDatabaseTable WHERE $fieldWhichMustBeUnique='$valueOfInputForFieldThatMustBeUnique' ";
				$result = $controller->command("makeQuery", $query, "processInputToEnsureUniqueField"); 
				if ($result) {
					// 06-28-08 - if the owner is editing a group they already created, then
					// there will be one group in the database with the name of the current group - 
					// that is, the current group will be in the database. Otherwise there should be
					// zero entries in the database with this name. 
					if ($idOfThisEntry) {

						// 05-17-08 - we will loop recursively till the field has a unique value
						if (mysql_num_rows($result) > 1) {
							$valueOfInputForFieldThatMustBeUnique .= rand(0, 99); 
                                                        // 07-30-08 - what the hell is wrong with me? Why was I calling processInputToEnsureUniqueField?
                                                        // Clearly I want to get back a string, yet that function returns an array. I'm an idiot. 
							// $valueOfInputForFieldThatMustBeUnique = $controller->command("processInputToEnsureUniqueField", $totalFormInputs, $fieldWhichMustBeUnique, $valueOfInputForFieldThatMustBeUnique); 
							$valueOfInputForFieldThatMustBeUnique = $controller->command("processInputToEnsureUniqueFieldEach", $totalFormInputs, $fieldWhichMustBeUnique, $valueOfInputForFieldThatMustBeUnique); 
							$controller->addToResults("Sorry, but the name you tried to give this group is already in use. Could you try another name?"); 
						}
					} else {
						// 05-17-08 - we will loop recursively till the field has a unique value
						if (mysql_num_rows($result) > 0) {

							$valueOfInputForFieldThatMustBeUnique .= rand(0, 99); 
                                                        // 07-30-08 - what the hell is wrong with me? Why was I calling processInputToEnsureUniqueField?
                                                        // Clearly I want to get back a string, yet that function returns an array. I'm an idiot. 
							// $valueOfInputForFieldThatMustBeUnique = $controller->command("processInputToEnsureUniqueField", $totalFormInputs, $fieldWhichMustBeUnique, $valueOfInputForFieldThatMustBeUnique); 
							$valueOfInputForFieldThatMustBeUnique = $controller->command("processInputToEnsureUniqueFieldEach", $totalFormInputs, $fieldWhichMustBeUnique, $valueOfInputForFieldThatMustBeUnique); 
							$controller->addToResults("Sorry, but the name you tried to give this group is already in use. Could you try another name?"); 
						}
					}
				} 

				$formInputs[$fieldWhichMustBeUnique] = $valueOfInputForFieldThatMustBeUnique;
				$arrayOfFormInputArrays[$idOfThisEntry] = $formInputs;

			}
			$totalFormInputs[$whichDatabaseTable] = $arrayOfFormInputArrays;
		}
		reset($totalFormInputs); 
	}

	return $totalFormInputs;
}



?>