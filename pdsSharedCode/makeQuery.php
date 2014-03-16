<?php



function makeQuery($query=false, $callingCode=false) {
	// 07-16-06 - I don't want to have to write the following error checking every time I make a 
	// query against the dataase, so I'll just write this one function and then use it for every
	// query. 
	
	global $controller;

	if ($query) {
		if ($callingCode) {
			$databaseObject = $controller->getObject("Database", "makeQuery"); 
			if (is_object($databaseObject)) {
				$result = $databaseObject->query($query); 

				// 09-08-06 - I'm surprised I didn't do this a long time go. I meant to do this in July. We should 
				// test to see if the query to database worked correctly. If not, this is the best place to generate
				// an intelligent error message. I'm adding this next if() statement today.
				if ($result) {
					return $result;
				} else {
					$dbErrorMessage = mysql_error(); 
					$controller->error("In makeQuery, the call to the database failed. The query was '$query'.  The error message from the database was '$dbErrorMessage'. The function which made this query was '$callingCode'."); 
				}
			} else {
				$controller->error("In makeQuery(), we tried to get an instance of the class Database, but the Controller failed to return an object to us."); 
			}
		} else {
			$controller->error("In makeQuery(), we expected the second parameter to be the name of the function or class that is calling makeQuery(), but we got nothing.");
		}
	} else {
		$controller->error("In makeQuery(), we expected the first parameter to be the query to be made against the database, but we got nothing."); 
	}
}



