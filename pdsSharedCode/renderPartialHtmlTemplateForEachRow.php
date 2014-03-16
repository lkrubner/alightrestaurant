<?php



function renderPartialHtmlTemplateForEachRow($oneDimensionalArray=false, $partialHtmlTemplateToFormatTheOutput=false) {
	// 11-30-07 - the function loopArray was written mostly to handle one 
	// dimensional arrays. It has been around for awhile but I've never
	// been able to use it the way I use loop(), because there was no easy way to
	// feed a two dimensional array to loop array and get it to give its rows
	// to a function that then stores the row in the singleton for list values, 
	// and then calls renderPartial. This has been needed for a long time, for all
	// the times we have a two dimensional array and want an easy way to send
	// the data to the screen, using some html file that the designer controls. 
	//
	// In loopArray, each row will be called like this: 
	//
	// $controller->command($functionName, $value, $param1);
	//
	// In this case, the $functionName will be this function, renderPartialHtmlTemplateForEachRow(),
	// the value will be the one dimensional row, and the $param1 will be the html sub-template
	// used to format the output. 
	//
	// For instance, on groups_search.php, this function gets invoked this way: 
	//
	//			$arrayOfResults = $controller->retrieve("group_search"); 
	//			$controller->command("loopArray", $arrayOfResults, "renderPartialHtmlTemplateForEachRow", "chat_rooms_posts_list_each_public.htm"); 
	
	global $controller; 
	
	if (!$oneDimensionalArray) {
		// 11-30-07 - is this an error, or no? 
		return false; 	
	}
	
	if (!is_array($oneDimensionalArray)) {
		$controller->error("In renderPartialHtmlTemplateForEachRow(), we expected the first parameter to be an array, but instead we got this: '$oneDimensionalArray'."); 
		return false; 	
	}

	if (!$partialHtmlTemplateToFormatTheOutput) {
		$controller->error("In renderPartialHtmlTemplateForEachRow(), we expected the second parameter to tell us the name of the html file we'd use to structure the format, but we got nothing."); 
		return false; 
	}
		

	$singletonFormValuesObject = & $controller->getObject("SingletonFormValues", "renderPartialHtmlTemplateForEachRow");
	$singletonFormValuesObject->set($oneDimensionalArray); 
	$controller->command("renderPartial", $partialHtmlTemplateToFormatTheOutput); 
}



