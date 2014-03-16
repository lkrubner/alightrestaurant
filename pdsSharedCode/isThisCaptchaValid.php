<?php



function isThisCaptchaValid($captchaThatTheUserJustSubmitted=false) {
	// 09-15-07 - When people want to create a new user account on Second Road,
	// we embed a captcha in the sign-up form. A number is generated randomly,
	// then stored in the database, then an image is created with that number 
	// on it, and the user must correctly type the number if they membership
	// account is to be created. This function ensures the correctness of the
	// submitted answer. This function is called from userCreateAccountSecondRoad().
	//
	// returns bool
	
	
	global $controller; 
	
	$thisCaptchaIsTotallyValid = false; 
		
	if ($captchaThatTheUserJustSubmitted) {
		$query = "SELECT captchas_number FROM captchas WHERE captchas_number='$captchaThatTheUserJustSubmitted' ";
		$result = $controller->command("makeQuery", $query, "isThisCaptchaValid"); 
		if (mysql_num_rows($result) > 0) $thisCaptchaIsTotallyValid = true;
	}	
	
	return $thisCaptchaIsTotallyValid; 
}



