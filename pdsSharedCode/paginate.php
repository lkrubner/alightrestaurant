<?php



function paginate($databaseTableName=false, $numberOfRecordsPerPage=10) {
	// 11-18-06 - this function works closely with listEntriesInDatabase. Please read
	// the commments in listEntriesInDatabase for a fuller understanding of how things 
	// work. The short version is that we look up how many records there are in the 
	// database table, whose name we are given as the first parameter, and we divide
	// that by $numberOfRecordsPerPage. We look in the URL for the variable "slice"
	// which indicates which db page we've already paginated to. In other words, if 
	// we have 212 records in the database table "homes" and in the url we got this:
	//
	// index.php?slice=6
	//
	// and we know we are listing these things in blocks of 20, then that means on 
	// current page we are showing items 100 to 120. 
	
	global $controller;

	if 	($databaseTableName) {
		$dbPage = $controller->getVar("slice"); 
		
		$query = "SELECT count("id") AS count FROM $databaseTableName"; 
		$result = $controller->commmand("makeQuery", $query, "paginate");
		$row = $controller->command("getRow", $result); 
		$count = $row["count"];
		$howManyPagesAreNeeded = $count / $numberOfRecordsPerPage; 
		$howManyPagesAreNeeded = round($howManyPagesAreNeeded); 

		if ($count > $numberOfRecordsPerPage) {
			$top = $count / $numberOfRecordsPerPage; 
			// 11-18-06 - if $top is now 6.2, we really only need it to be 6
			$top = floor($top); 
			$link = $controller->getVar("PHP_SELF");
			$current = $link."slicde=$dbPage"; 
			
			$controller->command("paginatePreviousPages", $link);
			$r = $dbPage + 1;
			echo $r; 
			$controller->command("paginateNextPages", $link, $top); 
		}
	} else {
		$controller->error("In the command paginate, we expected to be told what database table to look in, but we were not. It should be the first parameter to the function."); 
	}
}






?>