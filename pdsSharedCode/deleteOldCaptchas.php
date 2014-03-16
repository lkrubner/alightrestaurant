<?php



function deleteOldCaptchas(){
	// 03-01-07 This function deletes all of the records (rows) in the database table called 'captchas'. 
	// It is being called from the createCaptcha() function. It sets the date when the user loads 
	// the page http://www.accumulist.com/index.php?whatPage=addAnEntry.php, then subtracts 30 minutes 
	// from that value. The variable $string stores a command line (used by the function renderQuery) 
	// that tells the server to delete all of the entries in the captcha table that were created more 
	// than 30 minutes ago. -SW
	//
	// 09-15-07 - Starrie and Lawrence created this function for Accumulist. - LK

	global $controller; 

	$date = time();
	$thirtyMinutesAgo = $date - 3600;
	$string = "Delete from captchas where createdWhen < $thirtyMinutesAgo ";
	//echo $string;
	$controller->command("makeQuery", $string, "deleteOldCaptchas");

}



?>