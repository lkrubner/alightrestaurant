<?php



function listAllEntriesThatBelongToThisUser($arrayOfAllDatabaseTablesThatWeShouldCheckForEntries=false, $functionNameToRunOnEachRow=false) {
	// 01-16-07 - it would be nice to have a function that can universally seek though a database and 
	// find all of the entries that belong to a user. I suppose such a function could be written, but 
	// it would be expensive in terms of performance. So we will take an array of database table
	// names as a parameter. We're going to make some assumptions in this code. We're going to assume
	// that there is a table called users_logged_in and that we can retrieve a username from it. Then
	// we're going to assume that all of the database tables we're looking at have a field called 
	// "username". If all these assumptions are true, then everything should go peachy. 
	//
	// To keep the function relatively flexibile, the second parameter will be the function that the
	// programmer wants run on each row retrieved. 
	//
	// @param - $arrayOfAllDatabaseTablesThatWeShouldCheckForEntries - array - not optional - array of the names
	//	of all the database tables we should look into for entries belonging to the current user.
	//
	// @param - $functionNameToRunOnEachRow - string - not optional - the name of the function we should run
	//	on each row returned from the database. 

	global $controller; 
	$username = $controller->command("getUserNameFromUserLoggedInTable"); 

	if ($username) {
		if (is_array($arrayOfAllDatabaseTablesThatWeShouldCheckForEntries)) {
			if ($functionNameToRunOnEachRow) {
				for ($i=0; $i < count($arrayOfAllDatabaseTablesThatWeShouldCheckForEntries); $i++) {
					$oneDatabaseTableName = $arrayOfAllDatabaseTablesThatWeShouldCheckForEntries[$i];
					$query = "SELECT * FROM $oneDatabaseTableName WHERE username='$username' ";
					$result = $controller->command("makeQuery", $query, "listAllEntriesThatBelongToThisUser"); 
					$howManyRows = mysqli_num_rows($result); 
					for ($r=0; $r < $howManyRows; $r++) {
					        $row = $controller->command("row", $result, "listAllEntriesThatBelongToThisUser"); 
						$controller->command($functionNameToRunOnEachRow, $row, $oneDatabaseTableName); 
					}
				}
			} else {
				$controller->error("In listAllEntriesThatBelongToThisUser we needed the second parameter to tell us what function to run on each row retrieved from the database, but the second parameter was blank.");
			}
		} else {
			$controller->error("In listAllEntriesThatBelongToThisUser we expected to be given an array of database tables that we should look into, but instead we were only given this: '$arrayOfAllDatabaseTablesThatWeShouldCheckForEntries'."); 
		}
	}
}



