<?php



function testForUniqueValue($databaseTable=false, $databaseFieldName=false, $valueToTest=false) {
	// 12-11-06 - I want to generate this as a general part of the framework, but for today
	// I have to create this in a hurry for a hard-coded test that grows out of the form on 
	// this page:
	//
	// http://cacvb.cat4dev.com/sign_up.php

	global $controller; 

	if ($databaseTable && $databaseFieldName) {
		$query - "SELECT id FROM $databaseTable WHERE $databaseFieldName='$valueToTest' ";
		$result = $controller->command("makeQuery", $query, "testForUniqueValue"); 
		$numberOfRowsThatHaveThisValue = mysql_num_rows($result); 
		return $numberOfRowsThatHaveThisValue;
	} else {
		$controller->error("In testForUniqueValue we needed to know what database table and field to test for a unique value, but we only got '$databaseTable' and '$databaseFieldName'."); 
	}
}



?>