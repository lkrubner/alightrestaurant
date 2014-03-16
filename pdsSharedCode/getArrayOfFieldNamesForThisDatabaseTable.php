<?php



function getArrayOfFieldNamesForThisDatabaseTable($tableName=false) {
	// 08-15-07 - I just looked at showAllTables() and was disappointed to see that it focuses on
	// outputting HTML for a form. What I'd like is to have information about each database table
	// in createRecordsForMultipleDatabaseTables. I'd like to facillitate the magic nature of 
	// certain field names, such as "id_users" and "time". 
	
	global $controller;
	
	if (!$tableName) {
		$controller->error("In getArrayOfFieldNamesForThisDatabaseTable we expected the first argument to be the name of the table, but we got nothing."); 
		return false; 	
	}
	
	
	$arrayOfTableInfo = array();
	
	$query = "EXPLAIN $tableName"; 	
	$result = $controller->command("makeQuery", $query, "getArrayOfFieldNamesForThisDatabaseTable");
	$howManyRows = mysqli_num_rows($result); 
	
	for ($i=0; $i < $howManyRows; $i++) {
	  $row = $controller->command("row", $result, "getArrayOfFieldNamesForThisDatabaseTable");
		$arrayOfTableInfo[] = $row["Field"];
	}
	
	return $arrayOfTableInfo; 



}



