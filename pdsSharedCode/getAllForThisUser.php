<?php



function getAllForThisUser($databaseTable=false, $subForm=false, $orderBy="id", $limit=100) {
	// 08-15-07 - getAll() turned out to be useless function that caused more errors
	// than it solved. There is pretty much never a time when we want administrative
	// software to list everything in a database table. Instead, what we want 99%
	// of the time, is for software to list stuff based on the user that owns it. 
	// So this function is a modification of getAll(), one that queries the database
	// based on the user. We assume the existence of a field called "id_users". This
	// function should not be used with tables that don't have a field named "id_users".
	// The remaining comments, below, are held over from getAll(). 
	//
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
	//		< ?php getAllForThisUser("dentists", "list_dentists.htm", "last_name"); ? >
	//
	// If you want to list the dentists in reverse alphabetical order you would use the "desc"
	// keyword (which is short for "descending", as in values that are descending in order, 
	// rather than ascending). 
	//
	// 		< ?php getAllForThisUser("dentists", "list_dentists.htm", "last_name desc"); ? >
	
	global $controller; 
	
	if ($databaseTable) {
		if ($subForm) {			
			$id_users = $controller->command("getIdOfLoggedInUser");  

			if ($id_users) {			
				$query = "SELECT * FROM $databaseTable WHERE id_users='$id_users' ORDER BY $orderBy DESC LIMIT $limit "; 
				$result = $controller->command("makeQuery", $query, "listAll"); 
				if ($result) {
					if (mysql_num_rows($result) > 0) {
						$controller->command("loop", $result, "importForm", $subForm);
					} else {
						$formName = $_GET["formName"];
						$firstForm = str_replace(".htm", "", $formName);
						$arrangement = $firstForm . "_first_entry.htm"; 
						if (@file_exists("site_specific_files/$arrangement")) {						
							$controller->command("renderPartial", $arrangement); 
						} else {						
							$controller->command("loop", $result, "importForm", $subForm);
						}
					}
				}
			}
		} else {
			$controller->error("In getAllForThisUser, we were not given the name of a sub form to use to format each item."); 
		}
	} else {
		$controller->error("In getAllForThisUser, we were not told which database table we should get info from."); 
	}
}



