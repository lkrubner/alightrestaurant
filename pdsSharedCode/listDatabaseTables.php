<?php



function listDatabaseTables($arrangement=false) {
	// 09-22-06 - this functions shows the conditions that are linked to a given life phase. 
	// We assume that "idOfLifePhase" will be available in the URL. If it isn't there, then
	// there is no point showing any conditions. 
	
	global $controller; 

	$arrayOfAllTablesInDatabase = $controller->command("getListOfAllTables"); 	

	if (is_array($arrayOfAllTablesInDatabase)) {
		$arrayOfResults = $controller->command("loopArray", $arrayOfAllTablesInDatabase, "showEachDatabaseTable", $arrangement); 
	} else {
		$controller->error("In listDatabaseTables we needed an array of table names. Instead we only got this: '$arrayOfAllTablesInDatabase'.");	
	}
}



?>