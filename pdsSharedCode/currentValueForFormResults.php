<?php



function currentValueForFormResults($key=false, $format=false, $return=false) {
	// 09-18-06 - if you look at a form like formEditCondition, you'll see that
	// it relies on this function to correctly fill out the values
	// for the form, as it is included. There is no way to pass values to this
	// form, in a PHP page that is being included, except through an object
	// that is passed by reference. That is the job accomplished by SingletonResultsFormValues.
	//
	// @param - key - string - not optional - we assume that SingletonFormValues always
	// 	contains a one dimensional associative array. The function needs to be given a key
	// 	so we'll know what row of that array we should get. 
	//
	// @param - format - string - optional - if the programmer wants to run the value
	//	through a command, then format should be the name of the command.
	//
	//
	// 04-08-07 - adding the addToOutput method to the controller makes 
	// it pointless and redundant to control output via a function argument
	// so this need to be rewritten. 
	//
	//
	//
	// @param - return - bool - optional - by default, we assume we are echoing to 
	// 	the screen whatever value we get back from the array we get from SingletonFormValues.
	// 	However, we can return this value instead of echoing it. 
	//
	// 11-03-06 - I'm creating this class today. It is identical to currentValue in all ways
	// except it draws its values from SingletonResultsFormValues instead of SingletonPageInfo
	//
	// 05-13-07 - I don't understand this code, and I don't understand why it is needed. It 
	// seems like SingletonFormValues and currentValue could have done what this code does.
	// I wonder if I created this code while sleep-deprived? Why not use SingletonFormValues
	// instead? 
	
	
	global $controller;

	if (is_string($key)) {	
		$singletonFormValuesObject = & $controller->getObject("SingletonResultsFormValues", "currentValue");
		$entry = $singletonFormValuesObject->get(); 

		if (is_array($entry)) {
			if (array_key_exists($key, $entry)) {
				$value = $entry[$key];	
				if ($format) $value = $controller->command($format, $value); 
			
				if ($return) {
					return $value; 
				} else {
					// 04-08-07 - adding the addToOutput method to the controller makes 
					// it pointless and redundant to control out via a function argument
					// so this need to be rewritten. 
					$controller->addToOutput($value); 	
				}
			} else {
				$arrayAsString = print_r($entry, 1); 
				$controller->error("In currentValueForFormResults, we tried to find the key '$key' in the array '$arrayAsString'	 but it was not there."); 
			}
		} else {
			$controller->error("In currentValueForFormResults, we were unable to get an array back from SingletonFormValues.");
		}
	} else {
		$controller->error("In currentValueForFormResults we needed to be told a key to get from the current array, but we got nothing."); 	
	}
}



?>