<?php



function getUserNameFromUserLoggedInTable() {
	// 01-16-07 - most of the time, when we want to see if a user is logged in or not,
	// we would look in the table users_logged_in, to see if there is a username there
	// that matches the current session id. This function grabs the username that 
	// matches the id. 

	global $controller; 

	
	// 10-25-07 - we no longer us sessionId, we now use machineId, for this next 
	// database call. 
	$machineId = $_COOKIE["machineId"];
	
	if (!$machineId) return false; 
	
	$query = "SELECT username FROM users_logged_in WHERE machineId='$machineId'";
	$result = $controller->command("makeQuery", $query, "userLoginUpdate"); 
	$row = $controller->command("row", $result, "getUserNameFromUserLoggedInTable"); 
	$username = $row["username"];
	return $username;

}



