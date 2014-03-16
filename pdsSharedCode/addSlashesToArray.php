<?php



function addSlashesToArray($arrayOneDimensional=false) {
	// 09-17-06 - the main use of this is to take the input from a form and addslashes
	// to the content of the array, so that slashes will be added, so the content
	// can then be used in a query string and inserted into the database. 
	//
	// 05-13-07 - I don't believe this function is currently in use anywhere in
	// the code. 
		
	global $controller; 
	
	$processedArray = array(); 
	if ($arrayOneDimensional) {
		while (list($key, $val) = each($arrayOneDimensional)) {
			$processedArray[$key] = addslashes($val); 
		}		
		
		return $processedArray;
	} else {
		$controller->error("In addSlashesToArray we were not given an array to start with, we were instead given this: '$arrayOneDimensional'."); 
	}
}



?>