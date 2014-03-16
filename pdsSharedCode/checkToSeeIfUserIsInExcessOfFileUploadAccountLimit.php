<?php



function checkToSeeIfUserIsInExcessOfFileUploadAccountLimit() {
	// 06-16-08 - this is being called inside of uploadImageTsr(). 
	// We wish to begin enforcing a limit on users accounts. They 
	// will only be allowed to upload 20 megs of stuff. We must
	// add up how much they've uploaded so far. If it is less than	
	// 20 megs, we return true, otherwise we return false. 
	//
	// useful to check the input from this page: 
	//
	// http://www.cyberbitten.com/my_private_page.php?formName=mp_photos.htm

	global $controller; 

	$total = $controller->command("howMuchHasThisUserUploadedSoFar");

	// 06-18-08 - for now we will hardcode a limit of 20 megs on each account
	if ($total > 20000000) {
		return false; 
	} else {
		return true; 
	}
}



?>