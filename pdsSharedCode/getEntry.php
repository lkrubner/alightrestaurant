<?php



function getEntry($databaseTable=false, $id=false) {
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
	
	if ($databaseTable) {
		if ($id) {
			$query = "SELECT * FROM $databaseTable WHERE id=$id "; 	
			$result = $controller->command("makeQuery", $query, "getEntry"); 
			$row = $controller->command("getRow", $result); 

			// 06-17-08 - this next block of code already exists in getRow, yet it
			// isn't working to strip out the slashes. 
			reset($row);
			while(list($key, $val) = each($row)) {
				$row[$key] = stripslashes($val); 	
			}

			return $row; 
		} else {
			$controller->error("In getEntry, we needed to be given the id of the entry to look up, which should have been the second parameter"); 
		}
	} else {
		$controller->error("In getEntry, we needed to be told which database table to look to, which should have been the first parameter"); 
	}
}



?>