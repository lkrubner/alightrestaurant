<?php



function generateScaffoldingFiles($nameOfTable=false) {
	// 01-08-07 - this function replaces both createAdminForms and createPublicPages. 
	//
	// 11-05-06 - the whole idea of scaffolding is that the software autogenerates the forms
	// that the user must use to create edit and delete records in the database. This is the
	// function that autogenerates the forms. This function is one of several that are called 
	// from this page: 
	// 
	// http://craigbuilders.cat4dev.com/authorized/development/index.php?formName=createTableForm
	//
	// We are also receiving the name of the datatbase table, submitted from that form, and the only
	// variable that is mandatory to all of the functions being called from that page. We will assume
	// that this table has already been created in the database, then we fetch all the fields from
	// the database, and we will create forms that create inputs for each of those fields. 
	//
	// This function is also called from any scaffolding function that alters the database and therefore needs 
	// regenerate the scaffolding files. 

	global $controller; 

	// 11-16-06 - I'm now going to call this directly from addAFieldToATable, so I'm adding $nameOfTable
	// as an optional parameter to the function. When it isn't there, we go back to looking for it in
	// the POST variables. 
	if (!$nameOfTable) $nameOfTable = $controller->getVar("nameOfTable"); 

	if ($nameOfTable) {
		// 01-10-07 - apparently if you create a table name in the upper case, MySql makes it
		// lowercase by default. So if you are stupid enough to do that, and plenty of users 
		// will be, then you end up with mismatched forms and tables - the table is lowercase, 
		// but the forms all reference an uppercase table name, and the calls are all case
		// senstive. So I'm adding this next line, to protect against these mistakes (a mistake
		// I just made while testing).
		//
		// 03-13-07 - hmmm, this line is unneeded on the Redhat servers we are getting
		// from Rackspace. It is also making it impossible to integrate the old 
		// database tables from CubeCart, because the table names are upper case.
		// So I'm going to comment this line out. 
		//	$nameOfTable = strtolower($nameOfTable); 

		// 01-08-07 - createAdminForms got too complicated so I'm refactoring it and putting it in
		// smaller functions, like createTheCreateAndUpdateForm
		$controller->command("createTheCreateAndUpdateForm", $nameOfTable); 
		$controller->command("createTheListViewFormForUpdates", $nameOfTable); 
		$controller->command("createHtmlForEachItemForListsWhenUpdating", $nameOfTable); 
		$controller->command("createHtmlForPublicPageForOneItemFromADatabaseTable", $nameOfTable); 
		$controller->command("createHtmlForPublicListsOfPagesFromADatabaseTable", $nameOfTable); 
		$controller->command("createHtmlForEachItemOnAPublicListOfPagesFromADatabaseTable", $nameOfTable); 
		
		// 03-25-07 - today I'm working on this page: 
		//
		// https://www.monkeyclaus.org/admin.php
		//
		// https://www.monkeyclaus.org/index.php
		//
		// I find it tiresome that as I add new forms to the software, I have to hand-code
		// the link to each one into my design. Perhaps I could auto-generate a navigation
		// bar, and have that be its own file, and then the template on admin.php could simply
		// include that navigation bar, and so as the navigation changed, the changes would 
		// appear automatically, because the software would keep updating the changes? The
		// goal would be convenience during the scaffolding phase. The navigation bar could be 
		// thrown out once the scaffolding phase was done. The designer would be free to do 
		// the final design how they saw fit. The auto-generated navigation bar would only be
		// for the first phase of the project when the database table strucure, and therefore 
		// the forms, and therefore the links to the forms, are all changing rapidly. So I'm
		// going to create a command that auto-generates this naviation bar for me, and I'll
		// call it on the next line. 
		$controller->command("createNavigationBarForNewForms", $nameOfTable); 
	} else {
		$controller->error("In generateScaffoldingFiles() we needed a name for this database table to have been input from the form that was just submitted to the server, however, we were not able to find a database table name in the input."); 
	}
}



