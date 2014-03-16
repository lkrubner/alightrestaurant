<?php



function deleteFieldsFromDatabaseTables() {
	// 11-16-06 - this is being called from this page: 
	// 
	// http://craigbuilders.cat4dev.com/authorized/scaffolding/index.php?formName=deleteFieldForm.htm
	// 
	// An example of the input that is being input to this function: 
	//
	// <p>name <input type="checkbox" name="fieldToChoose[pdfs_for_neighborhoods][]" value="name" /> </p>
	// 
	// We need to take this array and loop through and delete each field that the user has asked
	// to be deleted. 

	global $controller; 

	$fieldToChoose = $controller->getVar("fieldToChoose"); 

	if (is_array($fieldToChoose)) {
		while (list($key, $val) = each($fieldToChoose)) {
			/*
				11-17-06 - what does this key and val look like?

					echo "here is the key: $key \n\n ";
					print_r($val); 

					outputs: 

					here is the key: pdfs_for_neighborhoods 
					
					Array
					(
					[0] => description
					[1] => date_created
					[2] => last_modified
					)
			*/
			for ($i=0; $i < count($val); $i++) {
				$thisField = $val[$i];
				$result = $controller->command("deleteField", $key, $thisField);	
				if ($result) {
					$controller->addToResults("We've deleted the field '$thisField' from the database table '$key'."); 
				} else {
					$controller->addToResults("Error: We failed to delete the field '$thisField' from the database table '$key'."); 
					$controller->error("Error: We failed to delete the field '$thisField' from the database table '$key'."); 
				}
			}
	
			// 01-09-07 - we've made a change to the database, so now we have to
			// regenerate all the forms that are based on this table. 
			$nameOfTable = $key; 
			$controller->command("generateScaffoldingFiles", $nameOfTable); 
		}
	} else {
		$controller->error("In deleteFieldsFromDatabaseTables we expected to get a variable called fieldToChoose, which should have been a variable, but instead all we got was this: '$fieldToChoose'."); 
	}
}



?>