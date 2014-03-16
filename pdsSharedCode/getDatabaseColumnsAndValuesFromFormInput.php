<?php



function getDatabaseColumnsAndValuesFromFormInput($formInputs=false) {
	// 09-19-06 - this is called in updateRecord. It takes the info just
	// submitted by a form and turns it into a string that can be used in
	// an SQL update statement. We are taking advantage of the fact 
	// that all forms, in this software, submit all data as part of an 
	// array called formInputs, and that the keys of this array usually
	// have the same name as the columns in the database. 
	
	global $controller;
	
	if (!$formInputs) $formInputs = $controller->getVar("formInputs"); 
	$columnsAndValuesAsString = "";


	while (list($key, $val) = each($formInputs)) {
		// 06-26-08 - Laura sent me this email: 
		//
		//		found a bug here:
		//		http://www.cyberbitten.com/my_private_page.php?formName=mp_photos.htm
		//		
		//		when i upload photos.
		//		
		//		In query(), in the class Database, our last query to the database
		//		failed. The query was 'UPDATE files SET description='', private='f',
		//		time='1214269630', id_users='20', id='127' WHERE id=99'. The database
		//		server returned this message: 'Duplicate entry '127' for key 1' 
		//		In
		//		makeQuery, the call to the database failed. The query was 'UPDATE files
		//		SET description='', private='f', time='1214269630', id_users='20',
		//		id='127' WHERE id=99'. The error message from the database was
		//		'Duplicate entry '127' for key 1'. The function which made this query
		//		was 'updateRecord'.
		// 
		// Is there ever a situation where I want the code to update the id of a record? 
		// I can't think when we'd ever want this to happen. so I'm going to screen out
		// the id from the update SQL string. 
		if ($key != "id") {
			$val = addslashes($val); 
			$columnsAndValuesAsString .= "$key='$val', ";
		}
	}
	
	// 09-19-06 - we now need to remove the last comma 
	$columnsAndValuesAsString = substr($columnsAndValuesAsString, 0, -2);
	return $columnsAndValuesAsString;
}



?>