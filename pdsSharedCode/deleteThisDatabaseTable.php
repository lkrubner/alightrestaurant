<?php



function deleteThisDatabaseTable($databaseTableName=false) {
	// 11-05-06 - this will be called from loopArray, which is being called from
	// deleteTable, which is being called from this page: 
	// 
	// http://craigbuilders.cat4dev.com/authorized/development/index.php?formName=deleteTableForm.htm
	//
	// deleteTable fetches an array from the form input of database table names to delete, and each
	// table name is fed to this function, one at a time.

	global $controller; 

	if (is_string($databaseTableName) && $databaseTableName != "") {
		$query = "DROP TABLE IF EXISTS $databaseTableName";
		$result = $controller->command("makeQuery", $query, "deleteThisDatabaseTable"); 
		if ($result) {
			// 11-09-06 - we will try to delete all the forms associated with this table name
			//
			// 01-23-07 - too tightly coupled. We will call deleteForms from deleteTableForm.htm
			//
			//$controller->command("deleteForms", $databaseTableName); 
			return "success"; 	
		} else {
			return "failure"; 
		}
	} else {
		$controller->error("In deleteThisDatabaseTable we expected to be given the name of a database table, but we were only handed this: '$databaseTableName'."); 
	}
}



?>