<?php



function deleteThisRecordForThisUser($id=false, $whichDatabaseTable=false) {
	// 05-19-08 - We have long needed to make this framework more safe. 
	// One of the great security holes has been the ability of anyone to 
	// delete anything they want. We hope now to fix this. 
	// executeChoiceMadeEach should call getTheSafeVersionOfThisCommand which
	// should transform deleteThisRecord into deleteThisRecordForThisUser. 
	// This function should then ensure that the user trying to delete stuff
	// is the owner of the stuff being deleted, or the owner of the entry
	// to which the posts are being made (that is, either the current user
	// owns the item being deleted, or they own the weblog or chat group
	// from which they are deleting items. 
	// 
	// 
	// 
	// 
	// 
	// 09-18-06 - most times this is being called from loopArray which is being called from
	// deleteFromDatabase. We are being given the id of a record that needs to be deleted, 
	// and the name of the table from which we should delete the record. In loopArray, we
	// will be building up an array of results, full of either the string "success" or 
	// "failure". That array will be returned to deleteFromDatabase, and will be used to 
	// shape the message given to the user. 

	global $controller; 

	$userId = $controller->command("getIdOfLoggedInUser"); 
	if (!$userId) {
		$controller->addToResults("You must log in to delete anything."); 
		return false; 	
	}
	
	if (is_numeric(!$id)) {
		$controller->error("In deleteThisRecordForThisUser we expected the first parameter to be the id of the record we should delete from the database, but instead we got this: '$id'."); 
	}

	if (!is_string($whichDatabaseTable)) {	
		$controller->error("In deleteThisRecordForThisUser we expected the second parameter to be the name of the database for us to delete from, but instead we got this: '$whichDatabaseTable'."); 
	}

	
	$alternativeDatabaseToCheckWhenDeletingItems = $controller->getVar("alternativeDatabaseToCheckWhenDeletingItems"); 
	if ($alternativeDatabaseToCheckWhenDeletingItems) {
		$fieldToCheckInTheCurrentDatabaseTable = "id_".$alternativeDatabaseToCheckWhenDeletingItems;
		$query = "SELECT $fieldToCheckInTheCurrentDatabaseTable FROM $whichDatabaseTable WHERE id_users='$userId' "; 
		$result = $controller->command("makeQuery", $query, "deleteThisRecordForThisUserForThisUser"); 
		$row = $controller->command("getRowWithTrust", $result); 
		$idOfEntryInAlternativeDatbaseTableToCheck = $row[$fieldToCheckInTheCurrentDatabaseTable];
		if ($idOfEntryInAlternativeDatbaseTableToCheck) {
			$query = "SELECT id_users FROM $alternativeDatabaseToCheckWhenDeletingItems WHERE id_users='$userId' "; 
			$result = $controller->command("makeQuery", $query, "deleteThisRecordForThisUserForThisUser"); 
			$row = $controller->command("getRowWithTrust", $result); 
			if ($row["id_users"] != $userId) {
				$controller->addToResults("You are not allowed to delete that entry"); 	
				return false; 			
			}
		} else {
			$controller->addToResults("You are not allowed to delete that entry"); 	
			return false; 
		}
		$query = "DELETE FROM $whichDatabaseTable WHERE id=$id"; 
	} else {
		$query = "DELETE FROM $whichDatabaseTable WHERE id=$id and id_users='$userId' "; 
	}

	$result = $controller->command("makeQuery", $query, "deleteFromDatabase"); 

	// 05-19-08 - I'm changing this line so that it only 
	// returns true when something was really deleted. 
	if (mysql_affected_rows()) {
		return "success"; 	
	} else {
		return "failure"; 
	}
}



?>