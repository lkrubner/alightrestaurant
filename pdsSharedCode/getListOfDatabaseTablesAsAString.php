<?php



function getListOfDatabaseTablesAsAString() {
	// 11-05-06 - I find that for error messages and result messages I'm wanting,
	// in several places, to get a list of all the database tables, and turn that
	// into a string so I can use it in the message. I'm creating this function so
	// I don't have to keep writing the same code. 
	
	global $controller; 

	$arrayOfAllTablesInDatabase = $controller->command("getListOfAllTables"); 
	
	if (is_array($arrayOfAllTablesInDatabase)) {
		$message = "";
		for ($i=0; $i < count($arrayOfAllTablesInDatabase); $i++) {
			$row = $arrayOfAllTablesInDatabase[$i];
			$tableName = current($row); 
			if ($i > 0) $message .= ", "; 
			$message .= $tableName; 
		}
		return $message; 
	} else {
		$controller->error("In getListOfDatabaseTablesAsAString we expected to get an array of table names back from getListOfAllTables, but instead all we got was this: '$arrayOfAllTablesInDatabase'."); 
	}	
}



?>