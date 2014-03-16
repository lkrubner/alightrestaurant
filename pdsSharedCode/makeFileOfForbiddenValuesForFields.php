<?php



function makeFileOfForbiddenValuesForFields() {
	// 05-14-07 - this is a copy of makeFileOfRequiredValuesForFields. I've left
	// the names of some of the important variables untouched, so stringOfRequiredValues
	// may not make much sense, but it saves me some work. 
	//
	//
	// this is being called as the action of this form: 
	//
	// index.php?formName=showTextForForbiddenValuesForFields.htm
	//
	// We are receiving two inputs: 
	//
	// stringOfRequiredValues
	// overwriteRequiredFieldValues
	//
	// The first of these, stringOfRequiredValues, is formatted like this: 
	//
	//			the field 'word' of the database table 'adjective' must not contain the value 'sdfsdfsdfsdf'
	//			
	//			the field 'name' of the database table 'amenity' must not contain the value 'sdfsdf'
	//			
	//			the field 'description' of the database table 'amenity' must not contain the value 'sdfsdfsdf'
	//
	// By default we append this to the file forbiddenValuesForFields.php The variable overwriteRequiredFieldValues, if true
	// causes us to overwrite forbiddenValuesForFields.php, erasing all the old text and replacing it only with what is now
	// in overwriteRequiredFieldValues
	//
	// forbiddenValuesForFields.php is used in createRecord and updateRecord to make sure that certain fields 
	// don't have certain values. Commonly forbidden values include things like open spaces " " in an email address.
	//
	// This is the input that overrides the default: 
	//
	// <input type="checkbox" name="overwriteRequiredFieldValues" value="t" /> 

	global $controller; 

	$stringOfRequiredValues = $controller->getVar("stringOfRequiredValues"); 
	$overwriteRequiredFieldValues = $controller->getVar("overwriteRequiredFieldValues"); 

	$textOfRequiredFieldFileAsString = $controller->command("readFileAndReturnString", "forbiddenValuesForFields.php"); 

	$textOfRequiredFieldFileAsString .= "\n\n";
	$textOfRequiredFieldFileAsString .= $stringOfRequiredValues;

	if ($overwriteRequiredFieldValues == "t") {
		$result = $controller->command("writeFileToDisk", "forbiddenValuesForFields.php", $stringOfRequiredValues);
	} else {
		$result = $controller->command("writeFileToDisk", "forbiddenValuesForFields.php", $stringOfRequiredValues, "site_specific_files", "a+");
	}

	if ($result) {
		$controller->addToResults("Your new forbidden values have been saved."); 
	} else {
		$controller->addToResults("Sorry, an error occured. We could not save your changes."); 
		$controller->error("In makeFileOfForbiddenValuesForFields an error occured. We could not save any changes."); 		
	}
}



?>