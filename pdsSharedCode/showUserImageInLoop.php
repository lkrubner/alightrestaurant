<?php



function showUserImageInLoop() {
	// 06-11-08 - it turns out that showPersonalUserImage can not be used in
	// a loop. Where members have not yet assigned themselves an image, that
	// function will default to filling in the currently logged in user's image
	// for all users who have a blank for an image. So I'm creating this function
	// for use here:
	//
	// http://www.cyberbitten.com/members_list.php

	global $controller; 
	global $pathToImageFolder; 

	$imageName = $controller->command("currentValueFromLists", "upload_file", "", "return", "noError"); 
	if (!$imageName) $imageName = "anon_avatar2.jpg";
	$path = $pathToImageFolder."small_".$imageName; 

	return $path;
}



?>