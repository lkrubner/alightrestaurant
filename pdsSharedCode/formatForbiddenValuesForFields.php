<?php



function formatForbiddenValuesForFields() {
	// 05-14-07 - being called from this page: 
	//
	// http://www.ihanuman.com/scaffolding/index.php?formName=forbidden.htm
	// 
	// We get an array called fieldToChoose. It is a 2 dimensional, the first dimension 
	// being the name of the database field. The second dimension is the name of the field.
	// Inputs on the inputting form look like this: 
	//
	// <p>name <input type="text" name="fieldToChoose[aaa_rating][name]" value="" /> </p>
	//
	// We want to turn this into an almost English like string, which we can use for the
	// page, showTextForForbiddenValuesForFields.htm. 

	global $controller; 

	$singletonResultsFormValuesObject = & $controller->getObject("SingletonResultsFormValues", "formatForbiddenValuesForFields"); 
	
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
			
			if ($value) $stringForRequriedFields .= "the field '$rowKey' of the database table '$key' must not contain the value '$value'\n\n";
		}
	}
	
	$entry = array(); 
	$entry["forbiddenValuesForFields"] = $stringForRequriedFields; 
	$singletonResultsFormValuesObject->set($entry); 
}



?>