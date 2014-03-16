<?php



function getAllFromTableForThisFieldForThisUser() {
	// 10-14-07 - this is mostly called through Ajax. I assume it will mostly be called
	// through api.php. For instance, on the thesecondroad.org site, a lot of the 
	// stuff on the profile page is filled on request via Ajax calls. 


	global $controller; 

	$whichTable = $controller->getVar("whichTable"); 
	$whichField = $controller->getVar("whichField");
	$whichArrangement = $controller->getVar("whichArrangement"); 
	$limit = $controller->getVar("limit"); 
	
	$userId = $controller->command("getUserIdFromUserLoggedInTable"); 

	if (!$whichTable) {
		$controller->error("In getAllFromTableForThisFieldForThisUser (which is usually called via Ajax) we were not told what database table to fetch info from."); 
		return false; 
	}
	if (!$whichField) {
		$controller->error("In getAllFromTableForThisFieldForThisUser (which is usually called via Ajax) we were not told what database field to fetch info from."); 		
		return false; 
	}
	if (!$userId) {
		$controller->error("In getAllFromTableForThisFieldForThisUser (which is usually called via Ajax) we did not receive a user id."); 		
		return false; 
	}
	
		
	if ($whichTable == "users") {		
		$query = "SELECT $whichField FROM $whichTable WHERE id='$userId'"; 
	} else {
		$query = "SELECT $whichField FROM $whichTable WHERE id_users='$userId'"; 		
	}

	if ($limit) {
		$query .= " LIMIT $limit "; 	
	}
		
			
	$result = $controller->command("makeQuery", $query, "getAllFromTableForThisFieldForThisUser"); 

	if ($whichArrangement) {
		$controller->command("loop", $result, "renderPartial", $whichArrangement);	
	} else {
		$row = $controller->command("getRow", $result); 
		$value = $row[$whichField];	
		echo $value;
	}
}



?>