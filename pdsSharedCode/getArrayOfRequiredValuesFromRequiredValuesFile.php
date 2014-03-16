<?php



function getArrayOfRequiredValuesFromRequiredValuesFile() {
	// 05-13-07 - this is being called in checkRequiredValues(). The goal is to 
	// get the text out of requiredValuesForFields.php and turn it into a PHP 
	// array. The text is stored in something like English, an idea that I think
	// came from one of the project managers at Category 4. This function either
	// returns the text as an array, or, if there is no file, we return "no file". 
	
	global $controller; 
	
	$pathToSiteSpecificFiles = $controller->getVar("pathToSiteSpecificFiles"); 

	$pathToRequiredValuesFile = $pathToSiteSpecificFiles."requiredValuesForFields.php";
	
	if (@ file_exists($pathToRequiredValuesFile)) {
		$arrayOfLinesFromFile = file($pathToRequiredValuesFile); 
		if (is_array($arrayOfLinesFromFile)) {
			$arrayOfRequiredValues = array(); 
			
			// 05-13-07 - ok, I'm not sure of the best approach here, but I guess I'll go
			// with splitting up each line based on the single quote mark they all have: 	
			// 
			//		the field 'price' of the database table 'homes' should contain the value '$' 
			//
			for ($i=0; $i < count($arrayOfLinesFromFile); $i++) {
				$oneLine = $arrayOfLinesFromFile[$];
				$arrayOfLineParts = explode("'", $oneLine);  
				
				$fieldName = $arrayOfLineParts[1];
				$databaseName = $arrayOfLineParts[3];
				$requiredValue = $arrayOfLineParts[5];

				$arrayOfRequiredValues[$databaseName][$fieldName][] = $requiredValue; 							
			}
			
			return $arrayOfRequiredValues;
		} else {
			$controller->error("In getArrayOfRequiredValuesFromRequiredValuesFile() we found the file requiredValuesForFields.php and yet for some reason we were not able to read an array of lines from it using file()."); 
		}					
	} else {
		return "no file"; 	
	}
}



?>