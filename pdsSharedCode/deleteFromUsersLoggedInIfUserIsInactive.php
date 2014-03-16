<?php



function deleteFromUsersLoggedInIfUserIsInactive() {
	// 08-11-07 - we need to delete records from the database table "users_logged_in"
	// if the users have been inactive for an hour

	global $controller; 

	$time = time(); 
	$anHourAgo = $time - 3600; 

	// 06-03-08 - I'm adding in the second part of the WHERE clause, so admins won't get logged out.
	$query = "DELETE FROM users_logged_in WHERE last_action < $anHourAgo AND security_level != 'admin'"; 
	$result = $controller->command("makeQuery", $query, "deleteFromUsersLoggedInIfUserIsInactive"); 
	return $result; 

}



?>