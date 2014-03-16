<?php



function processValidCaptcha($totalFormInputs=false) {
	// 06-09-08 - this is mostly for checking to see if comments are valid. In use here:
	//
	// http://www.cyberbitten.com/weblog.php?editId=139
	//
	// You'll only see the captcha input if you not logged in. 
	
	global $controller; 

	// 06-09-08 - if you are logged in, then this does not apply to you
	if ($controller->command("getIdOfLoggedInUser")) {
		return $totalFormInputs;
	}


	// 06-09-08 - this next line is incorrect. I'm in a hurry
	// so I'm doing it wrong. The correct way to do this would be
	// to loop through totalFormInputs looking for a specified database.
	$formInputs = current(current($totalFormInputs)); 
	$description = $formInputs["description"];

	$captcha = $controller->getVar("captcha"); 
	$valid = $controller->command("isThisCaptchaValid", $captcha); 


	if (!$valid) {
		$controller->addToResults("The number you typed in was not a valid number. Please try again."); 
		if ($description) $controller->addToResults("You wrote: '$description'"); 
		unset($totalFormInputs);
		// 06-14-08 - this next line is to carry some information to notifyJournalOwnerOfCommentToOneOfTheirPosts
		$controller->carry("processValidCaptcha", "invalid captcha"); 
	}
	
	return $totalFormInputs;
}



