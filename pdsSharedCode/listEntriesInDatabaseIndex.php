<?php



function listEntriesInDatabaseIndex($nameOfIndexTable=false, $owningTable=false, $ownedTable=false, $arrangement=false) {



	global $controller; 

	$idOfOwningTable = $controller->getVar("id");
	
	$singletonFormValuesObject = & $controller->getObject("SingletonFormValues", "listEntriesInDatabaseIndex");

	// 01-23-07 - here is an example of a query that I tested in phpMyAdmin and it worked. 
	//
	//	SELECT cacvb_activities. *
	//	FROM cacvb_activities, index_of_cacvb_activities_and_cacvb_overview
	//	WHERE cacvb_activities.id = index_of_cacvb_activities_and_cacvb_overview.id_cacvb_activities
	//	AND index_of_cacvb_activities_and_cacvb_overview.owning_table = 'cacvb_overview'
	
	$query = "
		SELECT $ownedTable.*
		FROM $ownedTable, $nameOfIndexTable
		WHERE $ownedTable.id = $nameOfIndexTable.id_$ownedTable
		AND $nameOfIndexTable.owning_table = '$owningTable'
	";
	$owningTableFieldId = "id_".$owningTable;
	if ($idOfOwningTable) $query .= " AND $nameOfIndexTable.$owningTableFieldId = '$idOfOwningTable' ";
	
	$result = $controller->command("makeQuery", $query, "listEntriesInDatabaseIndex"); 
	$howManyRows = mysqli_num_rows($result); 

	for ($i=0; $i < $howManyRows; $i++) {
	        $row = $controller->command("row", $result, "listEntriesInDatabaseIndex"); 
		$singletonFormValuesObject->set($row); 
		$controller->command("renderPartial", $arrangement); 
	}
}



