<?php



function userLoginUpdate() {
	// 01-15-07 - this function cleans out entries from the table users_logged_in that are more than 1 day old 
	// old. It also updates the last_action field for the current user, if the current user is logged in. 

	global $controller; 

	$time = time();
	$time = $time - 86400;
	$query = "DELETE FROM users_logged_in WHERE last_action < $time  AND security_level != 'admin'";
	$result = $controller->command("makeQuery", $query, "userLoginUpdate"); 

	$time = time(); 
	$sessionId = session_id(); 
	$query = "UPDATE users_logged_in SET last_action=$time WHERE session_id='$sessionId'";
	$result = $controller->command("makeQuery", $query, "userLoginUpdate"); 
	$numberOfRowsUpdated = @ mysql_affected_rows($result); 
	return $numberOfRowsUpdated;
}



