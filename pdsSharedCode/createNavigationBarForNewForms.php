<?php



function createNavigationBarForNewForms() {
	// 03-25-07 - this function is being called in generateScaffoldingFiles, where
	// I wrote the following comment:
	//
	// "today I'm working on this page: 
	// https://www.monkeyclaus.org/admin.php
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
	// call it on the next line."
	
	global $controller;
	
	$arrayOfAllTablesInDatabase = $controller->command("getListOfAllTables");
	$self = $_SERVER["PHP_SELF"];		
	$stringOfHtml = "";
	
	
	for ($i=0; $i < count($arrayOfAllTablesInDatabase); $i++) {
		$row = $arrayOfAllTablesInDatabase[$i];
		list($key, $nameOfTable) = each($row); 			
		$nameOfEditForm = $nameOfTable."_edit_Form.htm"; 
		$nameOfListForm = $nameOfTable."_list_Form.htm"; 	
		
		if ($controller->command("doesThisFormExist", $nameOfEditForm)) {
			$stringOfHtml .= " <a href=\"?formName=$nameOfEditForm\">Create $nameOfTable</a> | ";			
		}
		if ($controller->command("doesThisFormExist", $nameOfListForm)) {
			$stringOfHtml .= " <a href=\"?formName=$nameOfListForm\">Edit $nameOfTable</a> | ";			
		}
	}
	
	// 03-25-07 - this is to remove the final "|" at the end of our string
	$stringOfHtml = substr($stringOfHtml, 0, -2); 
		
	$result = $controller->command("writeFileToDisk", "adminNavBar.htm", $stringOfHtml, "./");
	if ($result) {
		$controller->addToResults("Success. We wrote the file <a href=\"index.php?formName=adminNavBar.htm\">adminNavBar.htm</a> to disk."); 
	} else {
		$controller->addToResults("Error: unable to write the file 'adminNavBar.htm'."); 
	}
}



?>