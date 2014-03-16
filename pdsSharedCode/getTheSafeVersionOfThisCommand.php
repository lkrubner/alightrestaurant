<?php



function getTheSafeVersionOfThisCommand($thisChoice=false) {
	// 05-17-08 - this is now called in executeChoiceMadeEach
	// This is the introductory note that I wrote over in that 
	// function: 
	//
	//	 05-17-08 - this framework was built in a hurry, back in 2006,
	//	 and every job where it was used, at both Category4.com and 
	//	 at bluewallllc.com, we were always in a rush and security was
	//	 always a secondary concern. The fact that any random visitor
	//	 to the website can execute any function they want, simply by
	//	 typing "choiceMade[]=deleteAll" into the browser has made this
	//	 framework unsuitable for real world use. I'd always assumed I'd
	//	 have time to come back and fix this, and now I finally am. 
	//	 I admit, I am surprised this somehow took so long. We will use
	//	 getTheSafeVersionOfThisCommand to find a safe version of the 
	//	 requested command, if the current user is not an admin. 
	//
	// This function will simply check an array that specifices
	// a safe alternative for whatever command was just requested. 
        // 
        // 2013-10-26 - I just created a new method in the Controller called  
        // safeCommand(), and I use that any time in a template I need to limit
        // some pages to admins. safeCommand() calls this function and executes
        // whatever this function returns.

	global $controller; 

	$arrayOfSafeFunctions = $controller->arrayOfAllCarriedInfo['arrayOfSafeFunctions'];

	if (is_array($arrayOfSafeFunctions)) {
		if (array_key_exists($thisChoice, $arrayOfSafeFunctions)) {
			$newChoice = $arrayOfSafeFunctions[$thisChoice];
		}
	}

	if (isset($newChoice)) $thisChoice = $newChoice;
	return $thisChoice; 
}





