<?php



function getAllFromTableForThisFieldForThePublic() {
	// 10-14-07 - this is mostly called through Ajax. I assume it will mostly be called
	// through api.php. For instance, on the thesecondroad.org site, a lot of the 
	// stuff on the profile page is filled on request via Ajax calls. 


	global $controller; 

	$whichTable = $controller->getVar("whichTable"); 
	$whichField = $controller->getVar("whichField");
	$whichArrangement = $controller->getVar("whichArrangement"); 
	$limit = $controller->getVar("limit"); 
	$location = $controller->getVar("location"); 
	
	$positionOfId = strpos($location, "id="); 
	$positionOfValue = $positionOfId + 3; 
	$userId = substr($location, $positionOfValue); 


	$idOfViewingUser = $controller->command("getUserIdFromUserLoggedInTable"); 
	if (!$idOfViewingUser) {
		$controller->command("You must log in to view this content"); 
	}


	if (!$userId) {
		$userId = $idOfViewingUser;
	}


	if (!$whichTable) {
		$controller->error("In getAllFromTableForThisFieldForThePublic (which is usually called via Ajax) we were not told what database table to fetch info from."); 
		return false; 
	}
	if (!$whichField) {
		$controller->error("In getAllFromTableForThisFieldForThePublic (which is usually called via Ajax) we were not told what database field to fetch info from."); 		
		return false; 
	}
	if (!$userId) {
		$controller->error("In getAllFromTableForThisFieldForThePublic (which is usually called via Ajax) we did not receive a user id."); 		
		return false; 
	}
	if (!$location) {
		$controller->error("In getAllFromTableForThisFieldForThePublic (which is usually called via Ajax) we did not receive the variable 'location' which should have held the full url of the calling page."); 
		return false; 
	}
	



		
	if ($whichTable == "users") {		
		$query = "SELECT $whichField FROM $whichTable WHERE id='$userId' AND private != 't' "; 
	} else {
		$query = "SELECT $whichField FROM $whichTable WHERE id_users='$userId' AND private != 't' "; 		
	}

	if ($orderBy) {
		$query .= " ORDER BY $orderBy "; 
	} else {
		$query .= " ORDER BY id DESC "; 
	}

	if ($limit) {
		$query .= " LIMIT $limit "; 	
	}
	
	

	$result = $controller->command("makeQuery", $query, "getAllFromTableForThisFieldForThePublic"); 

	if ($whichArrangement) {
		$controller->command("loopPublic", $result, "renderPartial", $whichArrangement);	
	} else {
		$row = $controller->command("getRowPublic", $result); 
		$value = $row[$whichField];	
		echo $value;
	}



}



?>