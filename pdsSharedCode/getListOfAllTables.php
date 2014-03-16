<?php



function getListOfAllTables() {
	// 09-20-06 - this gets a list of all the tables in the database. This can 
	// be used in such tasks as auto-generating forms for each new site we use
	// this code on. 
	
	/*
	
	03-25-07 - an example of how to use this function would be in showAllTables(): 
					
					$arrayOfAllTablesInDatabase = $controller->command("getListOfAllTables");
				
					for ($i=0; $i < count($arrayOfAllTablesInDatabase); $i++) {
						$row = $arrayOfAllTablesInDatabase[$i];
						list($key, $table) = each($row); 
						e c h o "<h5>The table is '$table'</h5>\n";
						$query = "EXPLAIN $table"; 
						$result = $controller->command("makeQuery", $query, "showAllTables"); 
						$howManyResults = mysqli_num_rows($result); 
				
						for ($r=0; $r < $howManyResults; $r++) {
							// 11-13-06 - this should be as flexible as the rest of the software, so 
							// I'm adding in the ability to handle arrangements. 
							$entry = mysqli_fetch_assoc($result); 
							$entry["table"] = $table;
							if ($arrangement) {
								$formValuesObject->set($entry); 
								$controller->command("importForm", $arrangement); 
							} else {
								while(list($key, $val) = each($entry)) {
									if ($key != "table") {
										if ($key == "Field") {
											e c h o "<p>$val ";
										} else {
											e c h o " &nbsp;&nbsp; $val ";
										}
									}
								}
							} 
						}
	*/
		
	global $controller; 
	$query = "SHOW TABLES"; 
	$result = $controller->command("makeQuery", $query, "getListOfAllTables"); 
	$howManyResults = mysqli_num_rows($result);

	$arrayOfTableNames = array(); 

	for ($i=0; $i < $howManyResults; $i++) {
	  $row = $controller->command("row", $result, "getListOfAllTables");
		$arrayOfTableNames[] = $row; 	
	}
	
	return $arrayOfTableNames;
}



