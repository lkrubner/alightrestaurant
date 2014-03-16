<?php



function checkToSeeIfThisAccountIsAllowedAnyMoreUploads($totalFormInputs=false) {
	// 06-18-08 - for this page:
	//
	// http://www.cyberbitten.com/my_private_page.php?formName=mp_photos.htm
	// 
	// When a file is uploaded, checkToSeeIfUserIsInExcessOfFileUploadAccountLimit will
	// see if this user's account is over the 20 meg limit. If yes, then failure is
	// recorded using carry(), which we can get here using retrieve. If the user
	// is overlimit, then we need to unset() any data going to the "files" database
	// table. 

	global $controller; 

	$accountStatus = $controller->retrieve("uploadImageTsr"); 
	if ($accountStatus == "user account in excess of limit") unset($totalFormInputs["files"]);

	return $totalFormInputs;
}



?>