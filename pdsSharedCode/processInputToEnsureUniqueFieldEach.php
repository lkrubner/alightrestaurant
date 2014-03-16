<?php



function processInputToEnsureUniqueFieldEach($totalFormInputs=false, $fieldWhichMustBeUnique=false, $valueOfInputForFieldThatMustBeUnique=false) {
    // 07-30-08 - this is called in processInputToEnsureUniqueField - we need to recursviely check a 
    // field till we have a unique value for this field.  

    global $controller; 

	if (is_array($totalFormInputs) && $fieldWhichMustBeUnique) {
                reset($totalFormInputs); 
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
 							$valueOfInputForFieldThatMustBeUnique = $controller->command("processInputToEnsureUniqueFieldEach", $totalFormInputs, $fieldWhichMustBeUnique, $valueOfInputForFieldThatMustBeUnique); 
						}
					} else {
						// 05-17-08 - we will loop recursively till the field has a unique value
						if (mysql_num_rows($result) > 0) {
							$valueOfInputForFieldThatMustBeUnique .= rand(0, 99); 
 							$valueOfInputForFieldThatMustBeUnique = $controller->command("processInputToEnsureUniqueFieldEach", $totalFormInputs, $fieldWhichMustBeUnique, $valueOfInputForFieldThatMustBeUnique); 
						}
					}
				} 
				$formInputs[$fieldWhichMustBeUnique] = $valueOfInputForFieldThatMustBeUnique;
				$arrayOfFormInputArrays[$idOfThisEntry] = $formInputs;
			}
		}
	}
	return $valueOfInputForFieldThatMustBeUnique;

}



?>