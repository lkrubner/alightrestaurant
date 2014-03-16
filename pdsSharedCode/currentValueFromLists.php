<?php



function currentValueFromLists($key=false, $format=false, $return=false, $noError=false, $keyForStorageInSingleton=false) {
	// 09-18-06 - if you look at a form like formEditCondition, you'll see that
	// it needs it relies on this function to correctly fill out the values
	// for the form, as it is included. There is no way to pass values to this
	// form, in a PHP page that is being included, except through an object
	// that is passed by reference. That is the job accomplished by SingletonFormValues.
	//
	// @param - key - string - not optional - we assume that SingletonFormValues always
	// 	contains a one dimensional associative array. The function needs to be given a key
	// 	so we'll know what row of that array we should get. 
	//
	// @param - format - string - optional - if the programmer wants to run the value
	//	through a command, then format should be the name of the command.
	//
	// @param - return - bool - optional - by default, we assume we are echoing to 
	// 	the screen whatever value we get back from the array we get from SingletonFormValues.
	// 	However, we can return this value instead of echoing it. 
	
	global $controller;

	if (is_string($key)) {	
		$singletonFormValuesObject = & $controller->getObject("SingletonFormValues", "currentValueFromLists");
		$entry = $singletonFormValuesObject->get($keyForStorageInSingleton); 
		if (is_array($entry)) {
			if (array_key_exists($key, $entry)) {
				$value = $entry[$key];	
				if ($format) $value = $controller->command($format, $value); 
			
				if ($return) {
					return $value; 
				} else {
					//RK 6/12/08 stripslashes() was added to remove slashes from the names of chat rooms
					$value=stripslashes($value);
					$controller->addToOutput($value); 	
				}
			} else {
				$arrayAsString = print_r($entry, 1); 
				if (!$noError) $controller->error("In currentValueFromLists, we tried to find the key '$key' in the array '$arrayAsString'	 but it was not there."); 
			}
		} else {
			if (!$noError) $controller->error("In currentValueFromLists, we were unable to get an array back from SingletonFormValues.");
		}
	} else {
		if (!$noError) $controller->error("In currentValueFromLists we needed to be told a key to get from the current array, but we got nothing."); 	
	}
}



