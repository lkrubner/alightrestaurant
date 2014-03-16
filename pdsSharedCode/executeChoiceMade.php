<?php


		
function executeChoiceMade() {
	// 07-16-06 - every time any page loads, this function is called. We look for an array called
	// choiceMade. It maybe submitted from a form, or it may be in the URL. Since this gives a 
	// malicious user the power to execute an arbitrary set of commands, we need to limit which
	// functions are allowed to be executed. We will add that as a security check later this week.
	// Once the function has been checked, if it is allowed, we execute it. 
	
	global $controller; 

	if (array_key_exists("choiceMade", $_POST) || array_key_exists("choiceMade", $_GET)) {		
		$choiceMade = $_POST["choiceMade"];
		if (!$choiceMade) $choiceMade = $_GET["choiceMade"];	
		// 09-18-06 - many times I've made the mistake of submitting choiceMade as a string
		// rather than an array. I'd like to throw an error message for this. I can't throw
		// an error message just because choiceMade is not there, since it won't be on a lot
		// of pages, but when it is there, it should be an array, so after checking to see if
		// is there, it is reasonable to throw an error if it is not an array. 
		if ($choiceMade) {
			if (is_array($choiceMade)) {
				$controller->command("loopArray", $choiceMade, "executeChoiceMadeEach"); 
			} else {
				$controller->error("In executeChoiceMade, we expected choiceMade to be an array, but instead we got this: '$choiceMade'."); 	
			}
		}
	}
}



