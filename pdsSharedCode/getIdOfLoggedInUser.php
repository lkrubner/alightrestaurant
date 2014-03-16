<?php



function getIdOfLoggedInUser() {
	// 10-22-07 - an alias for getUserIdFromUserLoggedInTable(). On some other
	// site I did the same function with a different name, so now I have trouble
	// remembering the correct name for this function. 
	
	global $controller; 
	
	$userId = $controller->command("getUserIdFromUserLoggedInTable"); 
	return $userId; 
}



