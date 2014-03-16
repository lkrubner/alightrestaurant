<?php



function getListOfFieldsAndFieldTypesForDatabaseTable($databaseTableName=false) {
	// 01-08-07 - in createTheCreateAndUpdateForm()  we need
	// to know what fields a table in the database has, so we can generate 
	// forms and pages that correctly show the material. So, assuming we've
	// been given the names of the fields, createTheCreateAndUpdateForm should
	// be able to generate inputs. We also want to know the type of the field,
	// as that will influence what type of input createTheCreateAndUpdateForm
	// generates. 
	
	global $controller; 
	
	if ($databaseTableName) {
		$arrayOfFieldNames = array(); 
		$query = "EXPLAIN $databaseTableName"; 
		$result = $controller->command("makeQuery", $query, "showAllTables"); 
		$howManyResults = mysqli_num_rows($result); 
	
		for ($i=0; $i < $howManyResults; $i++) {
   		        $entry = $controller->command("row", $result, "getListOfFieldsAndFieldTypesForDatabaseTable");
			$row = array(); 
			while(list($key, $val) = each($entry)) {
				if ($key == "Field") $row["field"] = $val;
				if ($key == "Type") $row["type"] = $val;
			} 
			$arrayOfFieldNames[] = $row; 
		}

		return $arrayOfFieldNames; 
	} else {
		$controller->error("In getListOfFieldsAndFieldTypesForDatabaseTable we needed to be handed a name of a table in the database, but instead we were only given this: '$databaseTableName'."); 
	}
}



?>