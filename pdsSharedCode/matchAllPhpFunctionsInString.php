<?php



function matchAllPhpFunctionsInString($subject=false) {
	/*
	* 09-19-06 - in the print_r statement below, $matches looks like this:  
	*				Array
	*				(
	*				    [0] => Array
	*				        (
	*				            [0] => <?php showMainContent(
	*				            [1] => <?php showEars(
	*				            [2] => <?php showHats(
	*				        )
	*				
	*				)
	*/
	//
	// 11-08-06 - this is being called in importForm
	//
	// 04-17-07 - the idea is to find all the php functions that are in
	// use in the template that we are about to import, so that we can
	// make those functions have been included() or required() before
	// we try to execute them. 

	$pattern = "<";
	$pattern .= "\?php.*\(.*\); \?";
	$pattern .= ">";
	$pattern = "/$pattern/";
	preg_match_all($pattern, $subject, $matches);
	// print_r($matches);

	// 09-19-06 - there is no point returning a 2 dimensional array, so we will make 
	// it one dimensional.
	$arrayOfPhpFunctionNames = $matches[0];

	// 04-30-07 - the following is a hack. I want to remove any code block that
	// contains the controller, since most uses of the controller will look like
	// this:
	//
	// $controller->command("doThisAmazingThing");
	//
	// Since that command will do its own importing, we don't need to import anything.
	// However, the correct way to weed out the controller is to modify the pattern 
	// above. But it is late and I'm in a hurry, and regex is hard, so I'll do the
	// weeding out the simple way, here below. 

	$newArray = array(); 
	for ($i=0; $i < count($arrayOfPhpFunctionNames); $i++) {
		$thisOneLine = $arrayOfPhpFunctionNames[$i];
		if (!stristr($thisOneLine, "controller->")) {
			$newArray[] = $thisOneLine;
		}
	}

	return $newArray; 
}



