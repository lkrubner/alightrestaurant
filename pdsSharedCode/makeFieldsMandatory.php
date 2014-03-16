<?php



function makeFieldsMandatory() {
	// 11-13-06 - this is being called from here:
	//
	// http://craigbuilders.cat4dev.com/authorized/scaffolding/index.php?formName=mandatory.htm
	//
	// The inputs look like this: 
	//
	// <p>phone1 <input type="checkbox" name="fieldToChoose[site_identification][]" value="phone1" /> </p>
	//
	// We are receiving a two dimensional array. The first dimension gives us the name of the database
	// table, the second dimension gives us the field inside of that table. We will write the 
	// field names to an array, and check that array every time we create or update a form. 
	
	global $controller; 
	
	$fieldToChoose = $controller->getVar("fieldToChoose"); 

	$stringOfFieldNamesForUserMessage = ""; 
	
	if (is_array($fieldToChoose)) {
		$stringOfMandatorFields = "<";
		$stringOfMandatorFields .= "?";
		$stringOfMandatorFields .= "php";
		$stringOfMandatorFields .= "\n\n\n";

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
				$stringOfMandatorFields .= "\$";
				$stringOfMandatorFields .= "arrayOfMandatoryFields[\"$key\"][] = \"$thisField\"; \n";
				$stringOfFieldNamesForUserMessage .= "$thisField,  ";
			}
		}

		$stringOfMandatorFields .= "\n\n\n";
		$stringOfMandatorFields .= "?";
		$stringOfMandatorFields .= ">";

		$controller->command("writeFileToDisk", "arrayOfMandatoryFields.php", $stringOfMandatorFields);

		$stringOfFieldNamesForUserMessage = substr($stringOfFieldNamesForUserMessage, 0, -2);
		$controller->addToResults("These fields were made mandatory: $stringOfFieldNamesForUserMessage"); 
	} else {
		$controller->error("In makeFieldsMandatory we expected to find an array called 'fieldToChoose' in the POST variables that were just submitted. However, we did not find any such variable."); 
	}
}



?>