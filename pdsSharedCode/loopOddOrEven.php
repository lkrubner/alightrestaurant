<?php



function loopOddOrEven($resourcePointerToDatabaseResult=false, $nameOfActionToBeUsedToProcessEachRow=false, $param1=false, $oddOrEven=false) {		
	// 07-20-06 - on other code projects I have not had a standard way of doing loops, and
	// so for() loops have been one of the most common things I write. I'm hoping to perhaps only 
	// have one or two for() loops in my code, on this project, by writing some general loops
	// like this one. 
	//
	// We expect 2 things: a resource pointer that has just returned from a
	// successful query of the database, and the name of an function to call for each row.
	//		
	//	
	// 10-14-07 - this function is being created today. It is based on loop(), but here
	// we only allow through rows with odd or even ids. 
	//
	// @param - oddOrEven - enum ("odd" or "even") - Laura Denyes, the designer, wants
	// the videos to appear in two seperate tracks. The easiest way to achieve this is to
	// sort them by their id, and put all the rows with odd-numbered ids into one track, 
	// and all the rows with even-numbered ids into another track. 
	
	
	global $controller; 
	$singletonFormValuesObject = & $controller->getObject("SingletonFormValues", "loopDatabase"); 
	
	if (is_resource($resourcePointerToDatabaseResult)) {	
		$howManyItemsToLoopOver = mysql_num_rows($resourcePointerToDatabaseResult); 
				

		for ($i=0; $i < $howManyItemsToLoopOver; $i++) {				
			$row = $controller->command("getRow", $resourcePointerToDatabaseResult); 

			reset($row); 
			while (list($key, $val) = each($row)) {
				$row[$key] = stripslashes($val); 
			}
							
			// 09-22-06 - on many designs we'd like for alternate rows to be alernate colors
			// so we add something to this row to indicate odd or even, and later designers
			// can use this to give different rows different CSS classes.
			if (is_int($i / 2)) {
				$row["oddOrEven"] = "Even"; 
			} else {
				$row["oddOrEven"] = "Odd"; 
			}
			
			
			// 09-08-07 - there are many times where we want to list a few items, and we want
			// those items to have unique ids that are the same as the index of the for()
			// loop that we are now in. For instance, on the Second Road site, on the 
			// Sharing Wall, we list 6 community posts. We want these to have unique ids
			// one through six, so that we can use Javascript to cause to fade them out
			// and then fade others in. 
			$row["rowId"] = $i + 1;

			
			if (!$oddOrEven) {									
				$singletonFormValuesObject->set($row, $keyForStorageInSingleton);
				$controller->command($nameOfActionToBeUsedToProcessEachRow, $param1); 
			} else {
				$thisRowsOddOrEvenness = strtolower($row["oddOrEven"]);
				if ($thisRowsOddOrEvenness == $oddOrEven) {
					$singletonFormValuesObject->set($row);
					$controller->command($nameOfActionToBeUsedToProcessEachRow, $param1); 					
				}
			}
		}
		
		return $howManyItemsToLoopOver;
	} else {
		$contentsOfResourcePointerVariable = print_r($resourcePointerToDatabaseResult, true); 
		$controller->error("In loopDatabase(), we expected to be given a database resource pointer as the first parameter, but instead we were given this: '$contentsOfResourcePointerVariable'."); 
	}	
}



?>