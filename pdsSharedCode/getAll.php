<?php



function getAll($databaseTable=false, $subForm=false, $orderBy="id") {
	// 09-18-06 - this is used on pages where we want to delete something, or on pages
	// where we want to edit something. We first need to list the entries from
	// the database, so the user can choose the item they wish to edit or delete. 
	//
	// @param - databaseTable - string - not optional - name of the table from which we
	// 		should select items.
	//
	// @param - subForm - string - not optional - name of form we should use to format the
	// 		appearance of each particular item that comes back from the database, as we
	//		send that information to the users screen. 
	//
	// @param - orderBy - string - optional - this should be the name of one of the fields
	//		in the database. The information returned will sort by this field. 
	//
	// Suppose you have a table called dentists and you have a field called last_name 
	// and you want to list the dentists alphabetically by last name, then you would call 
	// this command like this:
	//
	//		< ?php getAll("dentists", "list_dentists.htm", "last_name"); ? >
	//
	// If you want to list the dentists in reverse alphabetical order you would use the "desc"
	// keyword (which is short for "descending", as in values that are descending in order, 
	// rather than ascending). 
	//
	// 		< ?php getAll("dentists", "list_dentists.htm", "last_name desc"); ? >
	
	global $controller; 
	
	if ($databaseTable) {
		if ($subForm) {			
			$query = "SELECT * FROM $databaseTable ORDER BY $orderBy DESC "; 
			$result = $controller->command("makeQuery", $query, "listAll"); 
			if ($result) {
				$controller->command("loop", $result, "importForm", $subForm);
			}
		} else {
			$controller->error("In getAll, we were not given the name of a sub form to use to format each item."); 
		}
	} else {
		$controller->error("In getAll, we were not told which database table we should get info from."); 
	}
}



?>