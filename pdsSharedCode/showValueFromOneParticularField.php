<?php



function showValueFromOneParticularField($databaseTableName=false, $idOfEntryToGet=false, $fieldToGet=false, $return=false) {
	// 11-08-06 - there are many places in a template where a designer needs to show a particular value from a particular
	// database table. So we've 3 parameters that specify what value to get: 
	// 
	// $databaseTableName - which database table should we query?
	// 
	// $idOfEntryToGet - what is the id of the specific record we are trying to get? 
	// 
	// $fieldToGet - which field in this record should we get? 
	// 
	// $return - we normally echo the values to the screen, unless this parameter is true, in which case we return it.

	global $controller;

	if ($databaseTableName && $idOfEntryToGet && $fieldToGet) {
		$query = "SELECT $fieldToGet FROM $databaseTableName WHERE id= $idOfEntryToGet"; 
		$result = $controller->command("makeQuery", $query, "showValueFromOneParticularField");
		$row = $controller->command("getRow", $result); 
		$value = $row[$fieldToGet];
		
		if ($return) {
			return $value; 
		} else {
			echo $value; 
		}
	} else {
		$controller->error("In showValueFromOneParticularField we needed the name of the database table to query, then the id of the record to get, then the name of the field to get, but all we got was '$databaseTableName' and '$idOfEntryToGet' and '$fieldToGet'."); 
	}
}



?>