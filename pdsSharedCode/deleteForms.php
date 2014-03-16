<?php



function deleteForms($nameOfTable=false) {
	// 11-09-06 - in the scaffolding folder, when we are developing a site, we sometimes
	// need to delete a database table. When we do so, we should also delete the forms
	// that are associated with that table. This function is called from deleteThisDatabaseTable
	// which is called from this page: 
	//
	// http://craigbuilders.cat4dev.com/authorized/scaffolding/index.php?formName=deleteTableForm.htm
	
	global $controller; 

	if (!$nameOfTable) $nameOfTable = $controller->getVar("databaseTablesToDelete");
	
	if ($nameOfTable) {
		$nameOfOneItemPage = "show_".$nameOfTable.".htm"; 
		$nameOfListPage = "list_".$nameOfTable.".htm"; 
		$nameOfListPageEach = "list_".$nameOfTable."_each.htm"; 
		$nameOfListForm = "list_".$nameOfTable."_Form.htm"; 
		$nameOfListEachForm = "list_each_".$nameOfTable."_Form.htm"; 
		$nameOfEditForm = "edit_".$nameOfTable."_Form.htm"; 

		// 01-23-07 - I just created index tables for many-to-many relationships 
		// and the index tables follow a different naming format. So perhaps
		// these next commands should test with file_exists first so as to not
		// get an error. 
		if (file_exists($nameOfOneItemPage)) $controller->command("deleteThisFile", $nameOfOneItemPage);
		if (file_exists($nameOfListPage)) $controller->command("deleteThisFile", $nameOfListPage);
		if (file_exists($nameOfListPageEach)) $controller->command("deleteThisFile", $nameOfListPageEach);
		if (file_exists($nameOfListForm)) $controller->command("deleteThisFile", $nameOfListForm);
		if (file_exists($nameOfListEachForm)) $controller->command("deleteThisFile", $nameOfListEachForm);
		if (file_exists($nameOfEditForm)) $controller->command("deleteThisFile", $nameOfEditForm);
	} else {
		$controller->error("In deleteForms we expected to be told what table was being deleted, so we could figure out what forms to delete, but we were not told what database table was being deleted."); 
	}
}


?>