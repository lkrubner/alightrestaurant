<?php



function loopAndReturnString($resourcePointerToDatabaseResult=false, $nameOfActionToBeUsedToProcessEachRow=false, $param1=false) {
	// 08-11-07 - we need sometimes to be able to process the returns from a
	// database and collect the whole result as one big string. For instance,
	// we need this ability in generateXmlForThisDatabaseTable.
	//
	// returns string

	global $controller;

	if ($resourcePointerToDatabaseResult) {
		$howManyRowsWereReturnedFromTheDatabase = mysql_num_rows($resourcePointerToDatabaseResult); 
		$resultsWhichWeWillCollectAsAString = "";

		for ($i=0; $i < $howManyRowsWereReturnedFromTheDatabase; $i++) {
			$resultsWhichWeWillCollectAsAString .= $controller->command($nameOfActionToBeUsedToProcessEachRow, $resourcePointerToDatabaseResult, $param1);
		}

		return $resultsWhichWeWillCollectAsAString;
	} else {
		$controller->error("In loopAndReturnString we expected to get a database resource pointer returned to us, but we got nothing."); 
	}
}



?>