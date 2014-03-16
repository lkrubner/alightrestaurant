<?php



function getMostRecentEntryCreatedByThisUserInThisDatabaseTable($databaseTable=false) {
	// 05-17-08 - this is being used in addMembersToGroup. This
	// is invoked when this line returns nothing:
	//
	// $id_chat_rooms = $controller->command("getIdOfInput", "chat_rooms"); 
	//
	// If nothing came back, then we will want to find the last
	// (that is, most recent) entry that this user has created in
	// the database table specified by this function's argument. 

	global $controller; 

	$userId = $controller->command("getIdOfLoggedInUser"); 
	if (!$userId) {
		$controller->addToResults("You must be logged in to do that."); 
		return false; 
	}

	$query = "SELECT * FROM $databaseTable WHERE id_users='$userId' ORDER BY id DESC LIMIT 1"; 
	$result = $controller->command("makeQuery", $query, "getMostRecentEntryCreatedByThisUserInThisDatabaseTable"); 
	$entry = $controller->command("getRowWithTrust", $result); 

	return $entry; 
}



?>