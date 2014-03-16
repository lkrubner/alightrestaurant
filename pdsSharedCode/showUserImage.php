<?php



function showUserImage($nameOfOtherTableThatIsNowBeingCalled=false, $userId=false) {
	// 05-14-08 - an alias for showPersonalUserImage, which has a name I never remember

	global $controller; 
	$path = $controller->command("showPersonalUserImage", $nameOfOtherTableThatIsNowBeingCalled, $userId);    
	return $path;
}



?>