<?php



function showEachDatabaseTable($databaseTableName=false, $arrangementToUse=false) {
	// 11-05-06 - on this page: 
	//
	// http://craigbuilders.cat4dev.com/authorized/development/index.php?formName=deleteTableForm.htm
	//
	// the command listDatabaseTables is called, which then gets a list of all tables in the database 
	// and then  calls loopArray, which calls this function for each table in the database, which
	// imports whatever arrangement its told to import, in the case of deleteTableForm its likely 
	// to be an arrangement that will hand an array of table names to deleteTable so that the tables
	// can be deleted. 

	global $controller; 
	$singletonFormValuesObject = & $controller->getObject("SingletonFormValues", "showEachDatabaseTable"); 
	list($key, $val) = each($databaseTableName); 
	$row = array(); 
	$row["databaseTableName"] = $val;
	$singletonFormValuesObject->set($row); 
	$controller->command("importForm", $arrangementToUse); 
}



?>