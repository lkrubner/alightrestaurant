<?php



function createManyToOneRelationship() {
	// 11-06-06 - this is called from the form on oneToManyForm.htm. It takes
	// 2 variables:
	//	
	// $oneTable - this is the table that lots of entries in the other database table can be 
	//	assigned to. 
	//
	// $manyTable - this is the table that has many entries that can be assigned to one entry
	// 	in the database table specified by oneTable. 
	//
	// 01-12-07 - when we create a one-to-many relationship, all that means is we're adding a field
	// that starts with "id_" (followed by the name of the owning table) to the owned table. In other
	// words, if we have two database tables, and one is called "weblogs" and the other is called 
	// "posts", and we want to create a one-to-many relationship between them (because lots of posts
	// can belong to one weblog) then we'll be adding a field called "id_weblogs" to the database 
	// table "posts". And in that field the user will record the id of the database that the post
	// belongs to. 

	global $controller;

	$oneTable = $controller->getVar("oneTable"); 
	$manyTable = $controller->getVar("manyTable"); 
	
	if ($oneTable && $manyTable) {
		// 01-12-07 - to work well with addAFieldToATable we have to mimic the 2 dimensional array
		// that it normally gets from a form being input. 
		$fieldsToCreate = array();
		$row = array(); 
		$row["name"] = "id_".$oneTable; 
		$row["type"] = "int"; 
		$fieldsToCreate[] = $row; 
		$controller->command("addAFieldToATable", $manyTable, $fieldsToCreate);
	} else {
		$controller->addToResults("Sorry, we needed the names of two tables and the name of a field, but all we got was '$oneTable' and '$manyTable' and '$nameOfFieldInManyTable'."); 
	}
}



?>
