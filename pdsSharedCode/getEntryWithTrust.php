<?php



function getEntryWithTrust($databaseTable=false, $id=false) {
	// 09-18-06 - on many forms we need for the form to autofill with the correct values.
	// For instance, when we are editing a condition, we expect the form to fill out 
	// with the values that were previously filled in for that condition. 
	//
	// 11-06-06 - we will use this on any forms where an entry from a database needs 
	// to be edited. Mostly, this is being called from setEntryForPage(), which get
	// an entry from the database and stuffs into a singleton object that then shares
	// the values with all the PHP commands (mostly currentValue) that fill out the 
	// values for a form. 
        // 
        // 
        // 
        // 

	global $controller; 

	if (!$databaseTable) {
		$controller->error("In getEntryWithTrust, we needed to be told which database table to look to, which should have been the first parameter"); 
		return false; 
	}

	if (!$id) {
		$controller->error("In getEntryWithTrust, we needed to be given the id of the entry to look up, which should have been the second parameter"); 
		return false; 
	}

	$primaryKey = $controller->arrayOfAllCarriedInfo['arrayOfPrimaryKeys'][$databaseTable];
	if (!$primaryKey) $primaryKey = 'id';
	
	$query = "SELECT * FROM $databaseTable WHERE $primaryKey=$id "; 	
	$result = $controller->command("makeQuery", $query, "getEntryWithTrust"); 
	$row = $controller->command("getRowWithTrust", $result); 

	// 05-13-08 - adding this next check so that the errors come from my code
	// and not PHP. Getting a warning from setEntryForPageForUser, when
	// the id is input. 
	if (!is_array($row)) {
		$controller->error("In getEntryWithTrust we expected to get an array back from getRowWithTrust, but we did not. The query was '$query'."); 
		return false; 
	}
				
	reset($row); 
	while (list($key, $val) = each($row)) {
		$row[$key] = stripslashes($val); 	
	}
	
	return $row; 
}



