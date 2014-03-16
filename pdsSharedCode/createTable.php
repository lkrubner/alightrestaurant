<?php



function createTable() {
	// 11-03-06 - we want to imitate the scaffolding that Ruby On Rails offers. 
	// The process starts on this page: 
	//
	// http://craigbuilders.cat4dev.com/authorized/development/index.php?formName=createTableForm
	//
	// The input from that form gets processed by formatSqlToCreateTables(), which makes
	// a string that is shown to the user on showSqlForTableCreationForm.htm. The user
	// has a final chance to edit the SQL, and then that form is input and this function 
	// is called to actually create the table. 

	global $controller; 

	$stringOfSql = $controller->getVar("stringOfSql"); 
	$result = $controller->command("makeQuery", $stringOfSql, "createTable"); 
	
	if ($result) {
		$message = "The query was successful. "; 
	} else {
		if (is_resource($result)) {
			$errorMessage = mysql_error($result); 
			$message = "The query failed. The message from the database: '$errorMessage'. "; 
		} else {
			$controller->error("In createTable the query to the database failed and no valid resource was returned, not even one that says false"); 
			$controller->addToResults("Error: the query to the database failed and no valid resource was returned, not even one that says false"); 
		}
	}

	$message .= "These tables currently exist in the database: "; 
	$message .= $controller->command("getListOfDatabaseTablesAsAString"); 
	$controller->addToResults($message); 
}



?>