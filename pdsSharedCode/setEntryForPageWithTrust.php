<?php



function setEntryForPageWithTrust($databaseTable=false, $id=false) {
	// 11-29-07 - obviously, this function is based on setEntryForPage.
	// this is for use in situations where the user has been 
	// verified somehow and can therefore be trusted. 
	//
	//
	//
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
	
	$singletonFormValuesObject = & $controller->getObject("SingletonFormValues", "setEntryForPageWithTrust"); 
	$pageInfoObject = & $controller->getObject("SingletonPageInfo", "setEntryForPageWithTrust"); 

	// 08-15-07 - on the Second Road site, I'm finding it common that the id in the url 
	// doesn't correspond to an item that I want to show or edit. Rather, the id in the url
	// is the user id and specifies the user's page. When the user wants to edit an entry,
	// I've been getting bugs and conflicts trying to use "id" in the url, when so much
	// of the page is expecting "id" to specify the user. So I'm introducing a new common
	// variable to live in my urls, and that is "editId". 
	if (!$id) $id = $controller->getVar("editId"); 
	if (!$id) $id = $controller->getVar("id"); 
	
	if ($databaseTable) {
		if ($id > 0) {
			$entry = $controller->command("getEntryWithTrust", $databaseTable, $id); 
		} else {
			// 11-06-06 - this is not always an error, there may be times setEntryForPageWithTrust is 
			// is used in a template where the id is not always in the URL. So we do nothing
			// here. 
		}
		
		$singletonFormValuesObject->set($entry); 
		$pageInfoObject->set($entry); 
	} else {
		$controller->error("In setEntryForPageWithTrust we expected to be told what database table to look for, but we were not."); 
	}
}



?>