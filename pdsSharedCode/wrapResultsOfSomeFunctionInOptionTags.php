<?php




function wrapResultsOfSomeFunctionInOptionTags($functionName=false, $return=false, $param1=false, $param2=false, $param3=false, $param4=false, $param5=false, $param6=false) {
	// 11-06-06 - on oneToManyForm.htm I want to get a list of all the database tables
	// and wrap them option tags for a select box. This function is the most general 
	// (and therefore flexible) way that I can think to write this function. This
	// function accepts the name of a function, hopefully one that returns a 
	// one dimensional array. We'll then wrap the values in that array in an option
	// tag and either return or echo that, depending on the second parameter. 
	
	global $controller;

	$someArrayReturnedFromSomeFunction = $controller->command($functionName, $param1, $param2, $param3, $param4, $param5, $param6); 	

	$stringForOptionTags = "";
	if (is_array($someArrayReturnedFromSomeFunction)) {
		for ($i=0; $i < count($someArrayReturnedFromSomeFunction); $i++) {
			$someValue = $someArrayReturnedFromSomeFunction[$i];

			// 11-06-06 - at the risk of being too clever and making this function too complicated
			// we will loop through each row of each row, if we have been given a 2 dimensional array. 
			// Otherwise, we simply skip down and treat the current value as a string, with simpler
			// results. 
			if (is_array($someValue)) {
				while (list($key,$val) = each($someValue)) {
					$stringForOptionTags .= "<option value=\"$val\">$val</option>\n";
				}
			} else {
				$stringForOptionTags .= "<option value=\"$someValue\">$someValue</option>\n";
			}
		}

		if ($return) {
			return $stringForOptionTags;
		} else {
			echo $stringForOptionTags;
		}
	} else {
		$controller->error("In wrapResultsOfSomeFunctionInOptionTags we expected to get an array back from the function '$functionName' but instead all we got was: '$someArrayReturnedFromSomeFunction'."); 
	}
}



?>