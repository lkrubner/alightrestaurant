<?php



function getEntryIfThisUserOwnsThisRecord($databaseTable=false, $id=false, $userId=false) {
	// 05-17-08 - this function is based off of getEntryWhereUserOwnsRecord,
	// the changes are stylistic, rather than functional. And I like the
	// name better. 
	//
	// 11-24-07 - this was based on getEntryWithTrust, but this is being used in
	// setEntryForPageWhereUserOwnsRecord() which expects this function to check
	// and be sure the current user is also the owner of whatever record we are
	// returning. 
	// 
	// 
	// 
	// 09-18-06 - on many forms we need for the form to autofill with the correct values.
	// For instance, when we are editing a condition, we expect the form to fill out 
	// with the values that were previously filled in for that condition. 
	//
	// 11-06-06 - we will use this on any forms where an entry from a database needs 
	// to be edited. Mostly, this is being called from setEntryForPage(), which get
	// an entry from the database and stuffs into a singleton object that then shares
	// the values with all the PHP commands (mostly currentValue) that fill out the 
	// values for a form. 

	global $controller; 

	if (!$userId) $userId = $controller->command("getIdOfLoggedInUser");
	
	if (!$databaseTable) {
		$controller->error("In getEntryIfThisUserOwnsThisRecord, we needed to be told which database table to look to, which should have been the first parameter"); 
		return false; 
	}

	if (!$id) {
		$controller->error("In getEntryIfThisUserOwnsThisRecord, we needed to be given the id of the entry to look up, which should have been the second parameter"); 
		return false; 
	}

	$query = "SELECT * FROM $databaseTable WHERE id=$id AND id_users='$userId'"; 
	$result = $controller->command("makeQuery", $query, "getEntryIfThisUserOwnsThisRecord"); 
	if (mysql_num_rows($result) > 0) {
		$row = $controller->command("getRowWithTrust", $result); 
		return $row; 		
	} else {
		$controller->addToResults("We're sorry, but we were unable to find an entry which belongs to you and has an id of '$id'"); 	
	}
}



?>