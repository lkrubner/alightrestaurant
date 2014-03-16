<?php



function setEntryForPageForUser($databaseTable="users", $alternateFieldToMatch=false) {
	// 09-19-06 - called at the top of each page, in initiate.php, this uses getEntry()
	// to fetch an entry that is appropriate for the page, given what information is in
	// the URL, and then we set that in SingletonFormValues, so that form will 
	// automatically fill out with the correct values, assuming currentValueFromLists()
	// has been used correctly to set the form values. 
	//
	// 05-14-08 - We are adding a new function parameter: $alternateFieldToMatch
	// We are adding this to deal with a problem that we are facing on this page:
	// 
	// http://www.cyberbitten.com/my_private_page.php?formName=mp_inbox_one_message.htm&editId=96
	// 
	// We need to enforce privacy on the email. I should not be able to read your
	// email merely by changing the number in the URL. And yet, I do not "own" the 
	// email that you sent to me, because the field "id_users" does not equal my
	// user id. It is the sender who "owns" the email, in that sense. In this case, 
	// both the sender and the receiver should be free to see an email. The code 
	// allows the sender to see it, because their user id will equal the value in 
	// the field id_users. But we need to indicate an alternate field, which if 
	// matches my user id, should be considered an acceptable, safe, secure match.
	
	
	global $controller;
	
	$singletonFormValuesObject = & $controller->getObject("SingletonFormValues", "setEntryForPageForUser"); 
	$pageInfoObject = & $controller->getObject("SingletonPageInfo", "setEntryForPageForUser"); 

	// 05-15-08 - if, for security, or because there is no id to be found,
	// I'd like these values to be blanked. 
	$singletonFormValuesObject->set(false); 
	$pageInfoObject->set(false); 


	if (!$databaseTable) {
		$controller->error("In setEntryForPageForUser we expected to be told what database table to look for, but we were not."); 
		return false; 
	}

	// 05-13-08 - I'm copying and pasting this next block from setEntryForPage.
	//
	// 08-15-07 - on the Second Road site, I'm finding it common that the id in the url 
	// doesn't correspond to an item that I want to show or edit. Rather, the id in the url
	// is the user id and specifies the user's page. When the user wants to edit an entry,
	// I've been getting bugs and conflicts trying to use "id" in the url, when so much
	// of the page is expecting "id" to specify the user. So I'm introducing a new common
	// variable to live in my urls, and that is "editId". 
	if (!$id) $id = $controller->getVar("editId"); 
	if (!$id) $id = $controller->getVar("id"); 

	if (!$id) return false; 

	// 05-13-08 - why does this function exist? The next line says "getEntry". 
	// I'm going to change it to "getEntryWithTrust". I don't understand what
	// the use of this function is, unless it is fetching files that are otherwise
	// private. I ran into a problem on this page: 
	//
	// http://www.cyberbitten.com/my_private_page.php?formName=mp_my_journal.htm&editId=115
	//
	// I was using setEntryForPage on that page, but when I marked the 
	// entry private, I lost the ability to view it. So I need another
	// way to fetch data, so I'm going to use this function. 
	$entry = $controller->command("getEntryWithTrust", $databaseTable, $id); 

	$userId = $controller->command("getUserIdFromUserLoggedInTable"); 
	if (!$userId) {
		// 11-06-06 - this is not always an error, there may be times setEntryForPageForUser is 
		// is used in a template where the id is not always in the URL. So we do nothing
		// here. 
		$controller->addToResults("You must be logged in to try that."); 
		return false; 
	}

	//
	// 05-14-08 - We are adding a new function parameter: $alternateFieldToMatch
	// We are adding this to deal with a problem that we are facing on this page:
	// 
	// http://www.cyberbitten.com/my_private_page.php?formName=mp_inbox_one_message.htm&editId=96
	// 
	// We need to enforce privacy on the email. I should not be able to read your
	// email merely by changing the number in the URL. And yet, I do not "own" the 
	// email that you sent to me, because the field "id_users" does not equal my
	// user id. It is the sender who "owns" the email, in that sense. In this case, 
	// both the sender and the receiver should be free to see an email. The code 
	// allows the sender to see it, because their user id will equal the value in 
	// the field id_users. But we need to indicate an alternate field, which if 
	// matches my user id, should be considered an acceptable, safe, secure match.
	if ($entry["id_users"] == $userId || $entry["id"] == $userId || $entry[$alternateFieldToMatch] == $userId) {
		$singletonFormValuesObject->set($entry); 
		$pageInfoObject->set($entry); 
	} else {
		return false; 
	}
}



?>