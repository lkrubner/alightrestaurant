<?php



function deleteField($databaseTableName=false, $fieldName=false) {
	// 11-16-06 - this is being called from deleteFieldsFromDatabaseTables, which
	// is receiving input from this page (as an example): 
	//
	// http://craigbuilders.cat4dev.com/authorized/scaffolding/index.php?formName=deleteFieldForm.htm
	//
	// deleteFieldsFromDatabaseTables parses the input from that page and, for each
	// field to be dropped, it calls this function, giving it the name of the table in the database
	// to be altered and the name of the field that is to be dropped. 
	//
	// returns bool, I think

	global $controller; 

	if ($databaseTableName && $fieldName) {
		// 11-16-06 - example SQL from phpMyAdmin:
		//	
		// ALTER TABLE `troupe` DROP `theTime` ;
		$query =  "ALTER TABLE $databaseTableName DROP $fieldName";
		$result = $controller->command("makeQuery", $query, "deleteField"); 
		return $result; 
	} else {
		$controller->error("In deleteField we expected the name of the database table the name of the field we were suppose to delete, but instead all we got was '$databaseTableName' and '$fieldName'."); 
	}
}



?>