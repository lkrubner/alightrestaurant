<?php



function createTheListViewFormForUpdates($nameOfTable=false) {
	// 01-08-07 - this is being called from createAdminForms, here we create the form 
	// that lists all entries in a database table. 
	//
	// 04-17-07 - we've discarded createAdminForms. This is now being called from
	// generateScaffoldingFiles() 

	global $controller; 

	if ($nameOfTable) {
		// 11-05-06 - the designer can always change these names if they way, but we must
		// have some file names, so these will be the defaults. 
		//
		// 11-17-06 - a huge change, we will no longer have a separate form for creating and
		// updating an item. Instead, we will have one form. If there is an id, we will update
		// the record that has that id. If there is no id, then we will create a new record.
		// $nameOfCreateForm = "create_".$nameOfTable."_Form.htm"; 
		$nameOfListForm = $nameOfTable."_list_Form.htm"; 
		$nameOfListEachForm = $nameOfTable."_list_each_Form.htm"; 
		
		// 11-05-06 - we have to embed the PHP tags in our forms without causing a great many errors
		$phpStart = "<";
		$phpStart .= "?"; 
		$phpStart .= "php"; 
		$phpStop = "?";
		$phpStop .= ">";

		// 11-05-06 - here is the form that gets every item from the database and then hands each 
		// row to the form created by $stringForListEachForm for formatting. 
		$stringForListForm = "
	
			<form method=\"post\" action=\"index.php\">
	
			$phpStart getAll(\"$nameOfTable\", \"$nameOfListEachForm\"); $phpStop
	
			<p><input type=\"submit\" value=\"Delete\" />
			<input type=\"hidden\" name=\"whichDatabase\" value=\"$nameOfTable\" />
			<input type=\"hidden\" name=\"choiceMade[]\" value=\"deleteFromDatabase\" />

			</form>
	
		";
	
		$result = $controller->command("writeFileToDisk", $nameOfListForm, $stringForListForm, "./" );
		if ($result) {
			$controller->addToResults("Success. We wrote the file <a href=\"index.php?formName=$nameOfListForm\">$nameOfListForm</a> to disk."); 
		} else {
			$controller->addToResults("Error: unable to write the file '$nameOfListForm'."); 
		}
	}
}




