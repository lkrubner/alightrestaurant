<?php



function executeChoiceMadeEach($thisChoice=false) {
	// 09-18-06 - this is being called from loopArray which is being called from executeChoiceMade.
	// This function is called on the value of each row in the array variable choiceMade. 
	//
	//
	//
	// 05-17-08 - this framework was built in a hurry, back in 2006,
	// and every job where it was used, at both Category4.com and 
	// at bluewallllc.com, we were always in a rush and security was
	// always a secondary concern. The fact that any random visitor
	// to the website can execute any function they want, simply by
	// typing "choiceMade[]=deleteAll" into the browser has made this
	// framework unsuitable for real world use. I'd always assumed I'd
	// have time to come back and fix this, and now I finally am. 
	// I admit, I am surprised this somehow took so long. We will use
	// getTheSafeVersionOfThisCommand to find a safe version of the 
	// requested command, if the current user is not an admin. 
	
	global $controller; 

	$arrayOfUserInfo = $controller->command("getUserInfo"); 
	$userSecurityLevel = $arrayOfUserInfo["security_level"];
	if ($userSecurityLevel != "admin") {
		$thisNewChoice = $controller->command("getTheSafeVersionOfThisCommand", $thisChoice); 
		if ($thisNewChoice) $thisChoice = $thisNewChoice;
	}

	$controller->import($thisChoice, "executeChoiceMadeEach");
	if (function_exists($thisChoice)) {
		$thisChoice();
	} else {
		$controller->error("In executeChoiceMadeEach, we were asked to execute '$thisChoice' but there is no such function.");	
	}
}



