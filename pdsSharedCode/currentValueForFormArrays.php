<?php



function currentValueForFormArrays($key=false, $format=false, $return=false) {
	// 05-03-07 - this is the same function as currentValue, the only difference being
	// that when there is no value, we output or return a zero. This function is
	// used in createHtmlForFormInput(). 
	//
	//
	// 09-18-06 - if you look at a form like formEditCondition, you'll see that
	// it relies on this function to correctly fill out the values
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
		$singletonFormValuesObject = & $controller->getObject("SingletonPageInfo", "currentValue");
		$entry = $singletonFormValuesObject->get(); 

		// 11-13-06 - what happens when someone fills out a form to create a new entry, and they
		// hit the submit button, and their input fails some sort of validation test? They need 
		// to end up back on the create page, but all their info needs to be preserved. So,
		// current value needs to be used on the create page. And if there is no data stored 
		// in the form values object, then we need to look in $_POST and see if maybe we are 
		// receiving some input. 
		//
		// 05-13-07 - we rarely, perhaps never, now use 'formInputs' as the input array on forms.
		// We've switched to using 'totalFormInputs'. Because of this, the next line will fail 
		// to find us anything, at least most of the time. However, in updateRecord, we have
		// started preserving the formInputs data in the $_SESSION array, like so: 
		//
		// 		$_SESSION["formInputs"] = $formInputs; 
		//
		// So that is now the better place for us to look for the just input data. 
		if (!$entry) $entry = $controller->getVar("formInputs"); 
		if (!$entry) $entry = $_SESSION["formInputs"]; 
		
		if (is_array($entry)) {
			// 01-09-07 - I'm adding this next if block today. I realized that there will be time
			// when we want the code to do stuff even when (or perhaps because) the entry is blank
			// or a key is missing. An example is when we are expecting a key called "id" and it is 
			// not there. I just created findIdForForm as a work-around for the lack of this 
			// functionality but it occurs to me that it would be nice if we could stay inside of
			// currentValue and use if for everything. 
			if ($format) {
				$functionName = "process".$format;
				if (function_exists($functionName)) {
					$value = $controller->command($functionName, $value); 
				}
			}
			if (array_key_exists($key, $entry)) {
				$value = $entry[$key];	
				if ($format) $value = $controller->command($format, $value); 
			
				// 05-03-07 - this next line is the only line that makes this function different
				// from currentValue()
				if (!$value) $value = "0";
				
				if ($return) {
					return $value; 
				} else {
					$controller->addToOutput($value); 	
				}
			} else {
			// 01-24-07 - I'd like to bring this error back at some point, it is useful for catching mistakes
			// but for now it is being too strict. 
			//	$arrayAsString = print_r($entry, 1); 
			//	$controller->error("In currentValue, we tried to find the key '$key' in the array '$arrayAsString' but it was not there."); 
			
				// 05-03-07 - this next line is the only line that makes this function different
				// from currentValue()
				if (!$value) $value = "0";
				
				if ($return) {
					return $value; 
				} else {
					$controller->addToOutput($value); 	
				}

			}
		} else {
			// 11-13-06 - this is no longer an outright error,  because we are going to start using it on create pages
			// and so we will expect the value to sometimes be empty. 
			// $controller->error("In currentValue, we were unable to get an array back from SingletonFormValues.");

			// 05-03-07 - this next line is the only line that makes this function different
			// from currentValue()
			if (!$value) $value = "0";
			
			if ($return) {
				return $value; 
			} else {
				$controller->addToOutput($value); 	
			}

		}
	} else {
		$controller->error("In currentValue we needed to be told a key to get from the current array, but we got nothing."); 	
	}
}



