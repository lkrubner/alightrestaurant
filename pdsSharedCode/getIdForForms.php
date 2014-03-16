<?php



function getIdForForms() {
	// 01-09-07 - with our scaffolding software we auto-generate a form that allows
	// users to add new records to whatever database table they've invented. The same 
	// form also allows us to edit existing records. With the monkeyclaus CMS, I noticed
	// that users liked to be able a given record several times in a row. However, a 
	// problem was arising in this software, because I was using currentValue to 
	// capture the id into the form, in  a manner that looked like this: 
	//
	// <input type="hidden" name="id" value="< ?php currentValue("id"); ? >" />
	//
	// This worked to create an item or edit an item if you got to the edit form
	// through a page that listed all entries and put the id of a specific entry
	// into the URL. However, if we created an item and then attempted to edit it,
	// without ever leaving that one form, then we got errors, because of the way
	// currentValue works. currentValue does not ever look for a new created id. 
	// So we are creating this function to look for a new id in the database. If 
	// there is none, then we use currentValue like normal. 
	//
	// this function is the same as findIdForForms

	global $controller; 
	$id = $controller->command("getIdOfNewlyCreatedRecord"); 
	if (!$id) $id = $controller->command("currentValue", "id", "", "return");
	$controller->addToOutput($id); 
}



?>