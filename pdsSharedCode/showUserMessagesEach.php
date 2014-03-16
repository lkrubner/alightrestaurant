<?php



function showUserMessagesEach($message=false) {
	// 09-19-06 - this is being called from loopArray, which in this case is being called from
	// showUserMessages. This command is in the template somewhere, and this is where the software
	// gives messages to the user. 
	
	global $controller; 
	$message = stripslashes($message); 
	$controller->addToOutput("<div class=\"userMessages\">$message</div>");	
}



