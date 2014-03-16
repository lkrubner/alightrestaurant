<?php



function addAFieldToATable($tableToAlter=false, $fieldToCreate=false) {
	// 11-16-06 - we are receiving input from this page: 
	//
	// http://craigbuilders.cat4dev.com/authorized/scaffolding/index.php?formName=addFieldForm.htm
	//
	// We are receiving two variables:
	//
	// tableToAlter
	// fieldsToCreate
	//
	// The variable fieldToCreate is an array containing these rows: 
	//
	// type
	// name
	// defaultValues
	//
	// The goal is to add a new field (specified by fieldToCreate) to the database table
	// specified by tableToAlter. 
	
	global $controller; 
	
	// 01-12-07 - I want to be able to call this function from createManyToOneRelationship, 
	// so I'm now allowing for the two main variables to be passed as parameters. If they 
	// are not passed as parameters, then we find them in the $_POST array as always. 
	if (!$tableToAlter) $tableToAlter = $controller->getVar("tableToAlter");
	if (!$fieldToCreate) $fieldToCreate = $controller->getVar("fieldsToCreate"); 

	if ($tableToAlter) {
		if (is_array($fieldToCreate)) {
			// 01-12-07 - watching Lark work on the software, I realized that often times 
			// a person will need to add many additional fields, so the form on addFieldForm.htm
			// needs to be able to add multiple fields. I've added the Javascript to addFieldForm.htm
			// to enable it to add many fields. I'm adding a loop into this function, so it can 
			// process $fieldToCreate as a 2 dimensional array. It has, till now, been a 1 dimensional
			// array. 
			for ($i=0; $i < count($fieldToCreate); $i++) {
				$row = $fieldToCreate[$i];
				$name = "";
				$type = "";
				$name = $row["name"];
				$type = $row["type"];
	
				// 11-16-06 - some SQL examples I'm stealing from phpMyAdmin:
				//
				// ALTER TABLE `troupe` ADD 'hello' VARCHAR( 255 ) NOT NULL ;
				// ALTER TABLE `troupe` ADD `theTime` DATETIME NOT NULL ;
				//
				// Note that phpMyAdmin seems to add backticks where nothing is actually needed.
				$stringOfSql = "ALTER TABLE $tableToAlter ADD ";
	
				if ($name != "") {
					$name = $controller->command("formatDatabaseTableAndFieldNamesForSafety", $name); 
					if (stristr($type, "varchar") || stristr($type, "int") || stristr($type, "char") || stristr($type, "char")) {
						if (!$defaultValues) {
							if (stristr($type, "varchar")) $defaultValues = 255; 
							if (!$defaultValues && stristr($type, "char")) $defaultValues = 1; 
							if (!$defaultValues && stristr($type, "int")) $defaultValues = 11; 
						}
						$stringOfSql .= "$name $type($defaultValues) NOT NULL";
					} else {
						if (!$defaultValues) {
								if (stristr($type, "text")) $defaultValues = ""; 
								if (stristr($type, "timestamp")) $defaultValues = ""; 
								if (stristr($type, "datetime")) $defaultValues = "0000-00-00 00:00:00"; 
						}
						$stringOfSql .= "$name $type NOT NULL";
					}
				} else {
					$controller->addToResults("In addAFieldToATable we needed a name for the field that we were suppose to add to the table '$tableToAlter', but there was no name."); 
					$controller->error("In addAFieldToATable we needed a name for the field that we were suppose to add to the table '$tableToAlter', but there was no name."); 
				}
	
				$result = $controller->command("makeQuery", $stringOfSql, "addAFieldToATable"); 
				if ($result) {
					$controller->addToResults("We've added the field '$name' to the database table '$tableToAlter'."); 
				} else {
					$controller->addToResults("Error: We failed to add the field '$name' to the database table '$tableToAlter'."); 
					$controller->error("Error: We failed to add the field '$name' to the database table '$tableToAlter'."); 
				}	
			}
			// 11-16-06 - this next line recreates all the forms and public pages for the table
			// specified by $tableToAlter. This next line will overwrite any existing files for 
			// this table. If this table has a many to one relationship with another table,
			// the many to one relationship will need to be recreated. 
			$controller->command("generateScaffoldingFiles", $tableToAlter); 
		} else {
			$controller->error("In addAFieldToATable we expected an a variable called 'fieldToCreate' which should have been an array, but instead all we got was: '$fieldToCreate'."); 
		}
	} else {
		$controller->error("In addAFieldToATable we expected to get a variable called tableToAlter which would tell us which database table we were going to alter, but we got nothing. "); 
	}
}



?>