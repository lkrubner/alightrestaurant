<?php



function formatRequiredValuesForFields() {
	// 11-27-06 - being called from this page: 
	//
	// http://cacvb.cat4dev.com/authorized/development/index.php?formName=required.htm
	// 
	// We get an array called fieldToChoose. It is 2 dimensional, the first dimension 
	// being the name of the database field. The second dimension is the name of the field.
	// Inputs on the inputting form look like this: 
	//
	// <p>name <input type="text" name="fieldToChoose[aaa_rating][name]" value="" /> </p>
	//
	// We want to turn this into an almost English like string, which we can use for the
	// page, showTextForRequirementsForFields.htm. 

	global $controller; 

	$singletonResultsFormValuesObject = & $controller->getObject("SingletonResultsFormValues", "formatRequiredValuesForFields"); 
	
	$fieldToChoose = $controller->getVar("fieldToChoose"); 

	$stringForRequriedFields = "";

	while (list($key, $row) = each($fieldToChoose)) {
		while (list($rowKey, $value) = each($row)) {
			// 05-13-07 - getArrayOfRequiredValuesFromRequiredValuesFile() will later chop up these 
			// lines of English by splitting along the single quotes. Therefore it is crucial that
			// the values never contain single quotes. Single quotes are the dividers of these lines
			// and so every line needs to have 6 single quotes, and no more and no less. So we will
			// change any single quotes we find. 
			if (stristr($value, "'")) {
				$value = "######";	
				$controller->addToResults("Please don't use single quotes in your required values. Single quotes are the only characters that you can not use."); 
			}
			
			if ($value) $stringForRequriedFields .= "the field '$rowKey' of the database table '$key' should contain the value '$value'\n\n";
		}
	}
	
	$entry = array(); 
	$entry["requiredValuesForFields"] = $stringForRequriedFields; 
	$singletonResultsFormValuesObject->set($entry); 
}



?>