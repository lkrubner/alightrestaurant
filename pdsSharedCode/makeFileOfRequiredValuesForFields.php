<?php



function makeFileOfRequiredValuesForFields() {
	// 11-30-06 - this is being called as the action of this form: 
	//
	// index.php?formName=showTextForRequirementsForFields.htm
	//
	// We are receiving two inputs: 
	//
	// stringOfRequiredValues
	// overwriteRequiredFieldValues
	//
	// The first of these, stringOfRequiredValues, is formatted like this: 
	//
	//			the field 'word' of the database table 'adjective' should contain the value 'sdfsdfsdfsdf'
	//			
	//			the field 'name' of the database table 'amenity' should contain the value 'sdfsdf'
	//			
	//			the field 'description' of the database table 'amenity' should contain the value 'sdfsdfsdf'
	//
	// By default we append this to the file requriedValuesForFields.php The variable overwriteRequiredFieldValues, if true
	// causes us to overwrite requriedValuesForFields.php, erasing all the old text and replacing it only with what is now
	// in overwriteRequiredFieldValues
	//
	// requriedValuesForFields.php is used in createRecord and updateRecord to make sure that certain fields have certain 
	// values. Commonly requried values: an "http://" should be in an url field, and a "@" should be in an email field.
	//
	// This is the input that overrides the default: 
	//
	// <input type="checkbox" name="overwriteRequiredFieldValues" value="t" /> 

	global $controller; 

	$stringOfRequiredValues = $controller->getVar("stringOfRequiredValues"); 
	$overwriteRequiredFieldValues = $controller->getVar("overwriteRequiredFieldValues"); 

	$textOfRequiredFieldFileAsString = $controller->command("readFileAndReturnString", "requiredValuesForFields.php"); 

	$textOfRequiredFieldFileAsString .= "\n\n";
	$textOfRequiredFieldFileAsString .= $stringOfRequiredValues;

	if ($overwriteRequiredFieldValues == "t") {
		$result = $controller->command("writeFileToDisk", "requiredValuesForFields.php", $stringOfRequiredValues);
	} else {
		$result = $controller->command("writeFileToDisk", "requiredValuesForFields.php", $stringOfRequiredValues, "site_specific_files", "a+");
	}

	if ($result) {
		$controller->addToResults("Your new required values have been saved."); 
	} else {
		$controller->addToResults("Sorry, an error occured. We could not save your changes."); 
		$controller->error("Sorry, an error occured. We could not save your changes."); 		
	}
}



?>