<?php



function updateUserLastAction() {
	// 09-18-07 - the flip side of this is in deleteFromUsersLoggedInIfUserIsInactive:
	//
	// 	$query = "DELETE FROM users_logged_in WHERE last_action < $anHourAgo "; 
        //
	// 10-22-07 - 
	// 2013-10-21 - by default a PHP session ends after 20 minutes. I want to use a value 
	// that lasts at least an hour. machineId is set in initiate.php,
	// The code that creates this cookie looks like this: 
	//
	//  $machineId =  $_COOKIE["machineId"];
	//  if (!$machineId) {
	//   if (!headers_sent()) {
	//     $machineId = md5(uniqid(rand()));
	//     $success = setcookie("machineId", $machineId, time() + 1000000000);	
	//   } else {
	//     // not relevent in the config file
	//   }
	// }

	global $controller; 

	if (isset($_COOKIE["machineId"])) {
	  $machineId =  $_COOKIE["machineId"];
	} else {
	  $machineId = '';
	}

	$time = time(); 	
	$query = "UPDATE users_logged_in SET last_action='$time' WHERE machineId='$machineId' ";
	$result = $controller->command("makeQuery", $query, "updateUserLastAction"); 
}


