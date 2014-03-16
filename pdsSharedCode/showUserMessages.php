<?php



function showUserMessages() {
	// 07-16-06 - this function is the way the software can give messages to the
	// user. For instance, if the user uploads images from the file	adm_library_upload.php
	// then the command uploadImages will use the controller to record a message of
	// "You've just uploaded images to the server." This function then shows those
	// messages to the user, but only if the designer includes this function in the
	// design of the page. 
	
	global $controller;
	
	$arrayOfResultMessagesForUser = $controller->getArrayOfResultMessagesForUser(); 
	$controller->command("loopArray", $arrayOfResultMessagesForUser, "showUserMessagesEach"); 
}




