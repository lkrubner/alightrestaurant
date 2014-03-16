<?php



function setDataForPage($databaseTable=false, $editId=false) {
	// 06-06-08 - this function is identical to setEntryForPage, except we only look
	// for editId. Looking for both editId and id is causing bugs. 
	//
	//
	// 09-19-06 - called at the top of each page, in initiate.php, this uses getEntry()
	// to fetch an entry that is appropriate for the page, given what information is in
	// the URL, and then we set that in SingletonFormValues, so that form will 
	// automatically fill out with the correct values, assuming currentValueFromLists()
	// has been used correctly to set the form values. 
	//
	// 06-06-07 - I'm adding a second parameter today. This is so that this function
	// can be called from randomlyPickSomeItemToShow(). That function will feed a 
	// randomly choosen item to this function. 
	
	global $controller;
	
	$singletonFormValuesObject = & $controller->getObject("SingletonFormValues", "setDataForPage"); 
	$pageInfoObject = & $controller->getObject("SingletonPageInfo", "setDataForPage"); 

	// 08-15-07 - on the Second Road site, I'm finding it common that the id in the url 
	// doesn't correspond to an item that I want to show or edit. Rather, the id in the url
	// is the user id and specifies the user's page. When the user wants to edit an entry,
	// I've been getting bugs and conflicts trying to use "id" in the url, when so much
	// of the page is expecting "id" to specify the user. So I'm introducing a new common
	// variable to live in my urls, and that is "editId". 
	if (!$editId) $editId = $controller->getVar("editId"); 
	
	if ($databaseTable) {
		if (is_numeric($editId) && $editId > 0) {
			$entry = $controller->command("getEntry", $databaseTable, $editId); 
		} else {
			// 11-06-06 - this is not always an error, there may be times setDataForPage is 
			// is used in a template where the id is not always in the URL. So we do nothing
			// here. 
		}
		
		$singletonFormValuesObject->set($entry); 
		$pageInfoObject->set($entry); 
	} else {
		$controller->error("In setDataForPage we expected to be told what database table to look for, but we were not."); 
	}
}



?>