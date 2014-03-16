<?php



function setEntryForPageWhereUserOwnsRecord($databaseTable=false, $id=false) {
	// 11-24-07 - I'm creating this entry for situations where the user must own
	// the record they are about to edit, and the id of the record should be 
	// derived from the editId which should be in the URL. The first use of this
	// function is here: 
	//
	// http://www.cyberbitten.com/profile_form.php?editId=127&formName=my_posts_edit.htm
	//
	// Obviously, this function is based on setEntryForPage. 
	//
	//
	//
	//
	// 11-12-07 - I'm creating this page today because of a bug I noticed here:
	// 
	// http://www.thesecondroad.org/profile.php?formName=view_my_private_journal.htm&id=20
	//
	// The info in the right-hand column needs to remain private. If the user is logged
	// in, then the id of the logged in user is used to find the correct information to
	// display. However, I've been using currentValue to show the values, and if the user
	// is not logged in, then setEntryForPage will look to the URL to find an id to figure
	// out what info to show. This allows any malicious hacker to put into the URL the 
	// id of the person whose info they want to see. Thus I need a version of setEntryForPage
	// that doesn't look to the url for info. 
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
	
	$pageInfoObject = & $controller->getObject("SingletonPageInfo", "setEntryForPage"); 
	$userId = $controller->command("getIdOfLoggedInUser"); 
	
	if (!$userId) {
		return false; 	
	}
	
	// 08-15-07 - on the Second Road site, I'm finding it common that the id in the url 
	// doesn't correspond to an item that I want to show or edit. Rather, the id in the url
	// is the user id and specifies the user's page. When the user wants to edit an entry,
	// I've been getting bugs and conflicts trying to use "id" in the url, when so much
	// of the page is expecting "id" to specify the user. So I'm introducing a new common
	// variable to live in my urls, and that is "editId". 
	if (!$id) $id = $controller->getVar("editId"); 


	if ($databaseTable) {
		if (is_numeric($id) && $id > 0) {
			$entry = $controller->command("getEntryWhereUserOwnsRecord", $databaseTable, $id, $userId); 
		} else {
			// 11-06-06 - this is not always an error, there may be times setEntryForPage is 
			// is used in a template where the id is not always in the URL. So we do nothing
			// here. 
		}
		
		$pageInfoObject->set($entry); 
	} else {
		$controller->error("In setEntryForPageWhereUserOwnsRecord we expected to be told what database table to look for, but we were not."); 
	}
}



?>