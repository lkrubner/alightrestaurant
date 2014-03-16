<?php



function makeFieldsUnique() {
	// 05-13-07 - this function is closely based on the function makeFieldsUnique. 
	//
	// The inputs look like this: 
	//
	// <p>phone1 <input type="checkbox" name="fieldToChoose[site_identification][]" value="phone1" /> </p>
	//
	// We are receiving a two dimensional array. The first dimension gives us the name of the database
	// table, the second dimension gives us the field inside of that table. We will write the 
	// field names to an array, and check that array every time we create or update a form. The fields
	// listed in this array must contain unique values. 
	
	global $controller; 
	
	$fieldToChoose = $controller->getVar("fieldToChoose"); 

	$stringOfFieldNamesForUserMessage = ""; 
	
	if (is_array($fieldToChoose)) {
		$stringOfUniqueFields = "<";
		$stringOfUniqueFields .= "?";
		$stringOfUniqueFields .= "php";
		$stringOfUniqueFields .= "\n\n\n";

		while (list($key, $val) = each($fieldToChoose)) {
			// 11-13-06 - the key will be the name of a database table, like this: 
			//
			// neighborhoods 
			//
			// The val will be an array of fields, like this: 
			//
			// Array ( [0] => city [1] => state )
			for ($i=0; $i < count($val); $i++) {
				$thisField = $val[$i];
				$stringOfUniqueFields .= "\$";
				$stringOfUniqueFields .= "arrayOfUniqueFields[\"$key\"][] = \"$thisField\"; \n";
				$stringOfFieldNamesForUserMessage .= "$thisField,  ";
			}
		}

		$stringOfUniqueFields .= "\n\n\n";
		$stringOfUniqueFields .= "?";
		$stringOfUniqueFields .= ">";

		$controller->command("writeFileToDisk", "arrayOfUniqueFields.php", $stringOfUniqueFields);

		$stringOfFieldNamesForUserMessage = substr($stringOfFieldNamesForUserMessage, 0, -2);
		$controller->addToResults("These fields were made mandatory: $stringOfFieldNamesForUserMessage"); 
	} else {
		$controller->error("In makeFieldsUnique we expected to find an array called 'fieldToChoose' in the POST variables that were just submitted. However, we did not find any such variable."); 
	}
}



?>