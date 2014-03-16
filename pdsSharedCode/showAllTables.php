<?php



function showAllTables($arrangement=false) {
	// 11-05-06 - you can see this funciton in use on this page: 
	// 
	// http://craigbuilders.cat4dev.com/authorized/development/index.php?formName=showAllTables.htm
	//
	// The goal is to give people an easy way to see what the database is looking 
	// like, without necessarily having to add phpMyAdmin to the website. 
	
	global $controller; 
	
	$formValuesObject = & $controller->getObject("SingletonFormValues", "showAllTables"); 

	$arrayOfAllTablesInDatabase = $controller->command("getListOfAllTables");

	for ($i=0; $i < count($arrayOfAllTablesInDatabase); $i++) {
		$row = $arrayOfAllTablesInDatabase[$i];
		list($key, $table) = each($row); 
		$controller->addToOutput("<h5>The table is '$table'</h5>\n");
		$query = "EXPLAIN $table"; 
		$result = $controller->command("makeQuery", $query, "showAllTables"); 
		$howManyResults = mysqli_num_rows($result); 

		for ($r=0; $r < $howManyResults; $r++) {
			// 11-13-06 - this should be as flexible as the rest of the software, so 
			// I'm adding in the ability to handle arrangements. 
		        $entry = $controller->command("row", $result, "showAllTables"); 
			$entry["table"] = $table;
			if ($arrangement) {
				$formValuesObject->set($entry); 
				$controller->command("importForm", $arrangement); 
			} else {
				while(list($key, $val) = each($entry)) {
					if ($key != "table") {
						if ($key == "Field") {
							$controller->addToOutput("<p>$val ");
						} else {
							$controller->addToOutput(" &nbsp;&nbsp; $val ");
						}
					}
				}
			} 
		}
		$controller->addToOutput("<p> &nbsp; </p><p> &nbsp; </p>\n\n");
	}
}



?>