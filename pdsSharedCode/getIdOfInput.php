<?php



function getIdOfInput($databaseTable=false) {
	// 05-17-08 - the first place I'm using this function is in
	// addMembersToGroup(). The id is to find out whether the data
	// (that we assume has just been submitted from some form
	// somewhere) is meant to create a new entry, or if we are 
	// dealing with the update of an exist entry, and if so, 
	// what is the id of that entry? We assume input data
	// is in the array totalFormInputs, which is an array with
	// this structure: 
	//
	// totalFormInputs[database_table_name][id_of_input][name_of_field_in_the_database]
	//
	// when id_of_input is zero, the code in processInput assumes
	// it is time to create a new record in the database, but when
	// id_of_input equals some number, processInput assumes it is
	// time to update that record. Code such as addMembersToGroup
	// needs to know, so this function will provide the answer. 

	global $controller; 

	$totalFormInputs = $controller->getVar("totalFormInputs"); 

	if (is_array($totalFormInputs)) {
		$thisDatabaseArray = $totalFormInputs[$databaseTable];
		if (is_array($thisDatabaseArray)) {
			// 05-17-08 - this will only work in those situations
			// where there is just one record id for this database
			// table.
			$idOfInput = key($thisDatabaseArray); 
		}
	}

	return $idOfInput;
}



?>