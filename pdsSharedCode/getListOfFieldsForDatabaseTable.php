<?php



function getListOfFieldsForDatabaseTable($databaseTableName=false) {
	// 11-05-06 - in both createAdminForms() and createPublicPages() we need
	// to know what fields a table in the database has, so we can generate 
	// forms and pages that correctly show the material. So, assuming we've
	// been given the 
	//
	// 04-17-07 - we've discarded the functions createAdminForms and createPublicPages()
	// These have been replaced by the  various commands that are called by
	// generateScaffoldingFiles(). Those commands usually need to know the
	// names of the fields in whatever database table that they are trying
	// to generate a form for. 
	//
	// This function gets the field names and returns
	// a one dimensional array. This function is similar to 
	// getListOfFieldsAndFieldTypesForDatabaseTable
	// but that function returns a 2 dimensional array,
	// where each row carries both the field name and
	// its type. 
	
	global $controller; 

	// 11-06-06 - a bit of a hack, but I want this function to work on oneToManyForm2.htm
	// simply using the input from the form oneToManyForm.htm. The variably "manyTable" 
	// is being input from oneToManyForm.htm. 
	if (!$databaseTableName) $databaseTableName = $controller->getVar("databaseTableName"); 
	if (!$databaseTableName) $databaseTableName = $controller->getVar("manyTable"); 
	
	if ($databaseTableName) {
		$arrayOfFieldNames = array(); 
		$query = "EXPLAIN $databaseTableName"; 
		$result = $controller->command("makeQuery", $query, "showAllTables"); 
		$howManyResults = mysqli_num_rows($result); 
	
		for ($r=0; $r < $howManyResults; $r++) {
		        $entry = $controller->command("row", $result, "getListOfFieldsForDatabaseTable"); 
			while(list($key, $val) = each($entry)) {
				if ($key == "Field") $arrayOfFieldNames[] = $val;
			} 
		}

		return $arrayOfFieldNames; 
	} else {
		$controller->error("In getListOfFieldsForDatabaseTable we needed to be handed a name of a table in the database, but instead we were only given this: '$databaseTableName'."); 
	}
}



?>