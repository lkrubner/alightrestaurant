<?php



function deleteTable() {
	// 11-05-06 - this is being called from this page: 
	// 
	// http://craigbuilders.cat4dev.com/authorized/development/index.php?formName=deleteTableForm.htm
	//
	// That form inputs an array of database table names that we are suppose to delete. We call
	// loop array, which calls deleteThisDatabaseTable on each table name. 

	global $controller; 

	$databaseTablesToDelete = $controller->getVar("databaseTablesToDelete");

	if (is_array($databaseTablesToDelete)) {
		$arrayOfResults = $controller->command("loopArray", $databaseTablesToDelete, "deleteThisDatabaseTable"); 
		// 11-05-06 - assuming we just deleted 9 tables from the database, then $arrayOfResults
		// should now have 9 rows in it, each with the string "success" in them. We want
		// to count the instances of the rows with the word "success" in them, and use that
		// number in the message we send to the user. 
		$arrayOfSuccessAndFailureStoredAsStrings = array_count_values($arrayOfResults);
		$totalNumberDeleted = $arrayOfSuccessAndFailureStoredAsStrings["success"];
		$controller->addToResults("We deleted $totalNumberDeleted tables from the database."); 

		$message = "";
		$message .= "These tables currently exist in the database: "; 
		$message .= $controller->command("getListOfDatabaseTablesAsAString"); 
		$controller->addToResults($message); 
	} else {
		$controller->error("In listDatabaseTables we needed an array of table names. Instead we only got this: '$arrayOfAllTablesInDatabase'.");	
	}
}



?>