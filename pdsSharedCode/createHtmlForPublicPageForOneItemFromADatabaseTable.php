<?php



function createHtmlForPublicPageForOneItemFromADatabaseTable($nameOfTable=false, $return=false) {
	// 01-08-07 - the function createPublicPages got too complicated, so we are refactoring
	// it today.This is now mostly called from generateScaffoldingFiles(). See 
	// generateScaffoldingFiles for more details. 
	//
	// This function is creating a single page to show one item from a database. For instance,
	// imagine we have a database table called "nurses" and in that table we have fields such
	// as "name", "years_of_experience" and "description". This function creates HTML (and then
	// a file) that will show the public the name, years of experience and description of this 
	// nurse. This code is pretty dumb and it is almost certain that the designer will have to
	// rework the HTML before the page looks acceptable. This function is merely meant to give
	// the designer something to work with, it is not meant to replace the designer. We make
	// very few assumptions about what the page should really look like in the end. 

	global $controller; 

	if ($nameOfTable) {
		// 11-05-06 - the designer can always change these names if they way, but we must
		// have some file names, so these will be the defaults. 
		$nameOfOneItemPage = $nameOfTable."_public.htm"; 

		// 11-05-06 - we have to embed the PHP tags in our HTML pages without causing a great many errors
		$phpStart = "<";
		$phpStart .= "?"; 
		$phpStart .= "php"; 
		$phpStop = "?";
		$phpStop .= ">";

		// 01-08-07 - we rely on the database to generate what the default pages should look like.
		$arrayOfDatabaseTableFields = $controller->command("getListOfFieldsForDatabaseTable", $nameOfTable); 

		// 12-11-06 - in terms of performance, this next loop is wasteful, since we could probably do all this work
		// in the loops below. However, I suspect that I will eventually make this next loops its own function. Also,
		// in scaffolding I don't feel that performance matters, since neither the client nor the public ever sees it.
		// But I think this system will eventually depend on actions that are triggered by field names. The field names,
		// in other words, become magical. Right now I've only 2 tests that depend on field names, but later there
		// will like be more. 
		for ($i=0; $i < count($arrayOfDatabaseTableFields); $i++) {
			$fieldName = $arrayOfDatabaseTableFields[$i];
			if ($fieldName == "upload_file") $imageFieldNeeded = true; 
			// 12-11-06 - I'm not sure if this is the right way to do this. I'm looking for a field that starts with "id_"
			// which should indicate that this table is owned by another, that is, has a many-to-one relationship with
			// another table. 
			$firstThreeCharacters = substr($fieldName, 0, 3); 
			if ($firstThreeCharacters == "id_") {
				$fieldIndicatingOwnership = $fieldName; 
				$nameOfOwningTable = str_replace("id_", "", $fieldName); 		
				$nameOfOneItemPageOwningPage = "show_".$nameOfOwningTable.".htm"; 
				$nameOfOwnedTable = $nameOfTable;
				$controller->command("createOwningPageWhenCreatingNewForms", $nameOfOwningTable, $nameOfOwnedTable, $fieldName); 
			}
			// 04-17-07 - I'm adding another magical test. This one is probably
			// not very carefully thought through. Here I'm wondering if there
			// is a field name called "name" or "title". If there is, I'm going 
			// to use it as the title down below, where I create the actual
			// HTML for the page. Otherwise I'll simply use the first field
			// from the database, even though that will often not be what
			// really deserves to be treated as the headline for the page. 
			if ($fieldName == "name" || $fieldName == "title") {
				$titleIsInUse = true; 
			}
		}

		// 11-06-06 - we will make a big string out of all values and use that as the basis of the Show page
		// and also the Each page when listing items. 
		$stringOfValuesForIndividualPage = ""; 
		for ($i=0; $i < count($arrayOfDatabaseTableFields); $i++) {
			$fieldName = $arrayOfDatabaseTableFields[$i];
			$publicName = str_replace("_", " ", $fieldName); 
			$publicName = ucfirst($publicName); 
			// 11-08-06 - don't do anything if the field is id. Why show the id to the public? 
			//
			// 04-17-07 - I've decided that we don't need to add in any HTML if
			// the field name is "upload_file". The field name "upload_file" means
			// that the user can upload files to records that belong to the 
			// whatever database table we are currently creating a page for. 
			// Instead of adding the input here, I'll add the function 
			// showFileInFormIfItExists() down below. That is a smart function
			// that will show an image if an image has been uploaded and 
			// will also give a link to a file if a non-image file has 
			// been uploaded. 
			if ($fieldName != "id" && $fieldName != "upload_file") {
				if ($i == 1 && $titleIsInUse != true) {
					$stringOfValuesForIndividualPage .= "
						<h1>$phpStart currentValueFromLists(\"$fieldName\"); $phpStop </h1>
					";
				} else if ($fieldName == "name" || $fieldName == "title") {
					$stringOfValuesForIndividualPage .= "
						<h1>$phpStart currentValueFromLists(\"$fieldName\"); $phpStop </h1>
					";
				} else {
					$stringOfValuesForIndividualPage .= "
						<p>$phpStart currentValueFromLists(\"$fieldName\"); $phpStop </p>
					";
				}
			} 
		}

		// 11-06-06 - this is the public page where the public gets to see this information. 
		$stringForOneItemPage = "

			$phpStart setEntryForPage(\"$nameOfTable\"); $phpStop 

			$stringOfValuesForIndividualPage

	
		";

		if ($imageFieldNeeded) {
			// 04-17-07 - showFileInFormIfItExists() is a smart function
			// that will show an image if an image has been uploaded or 
			// will give a link to a file if a non-image file has 
			// been uploaded. 
			$stringForOneItemPage .= "

				$phpStart showFileInFormIfItExists(); $phpStop

			";
		}

		if ($return) {
			return $stringForOneItemPage;
		} else {
			$result = $controller->command("writeFileToDisk", $nameOfOneItemPage, $stringForOneItemPage, "./" );
			if ($result) {
				$controller->addToResults("Success. We wrote the file <a href=\"index.php?formName=$nameOfOneItemPage\">$nameOfOneItemPage</a> to disk."); 
			} else {
				$controller->addToResults("Error: unable to write the file '$nameOfListForm'."); 
			}
		}
	}
}



?>