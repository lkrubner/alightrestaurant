<?php



function importForm($formName=false) {
	// 11-30-06 - this function is misnamed. It does something like the 
	// content_for_layout method in Ruby On Rails, or what showMainContent 
	// did in the CMS that Darren Hoyt and I developed. On most pages we'll 
	// have some content that we want to include on a page, but that content
	// will be vary page by page, probably depending on variables set in the 
	// url. This function imports a sub-template (what in Ruby on Rails we'd 
	// call a partial) based on the variable $formName. 

	global $controller;

	if ($formName) {
		// 09-19-06 - as we include files, they will contain functions that have not
		// yet been imported. The next 3 lines get the form as a string, uses a regular
		// expression to get an array of function names in the form, and then loops
		// through that array, importing any PHP code that has not already been imported.
		//
		// 12-12-06 - the new command readFileAndReturnString is a refactor of the code
		// I used to have here. 
		$formAsString = $controller->command("readFileAndReturnString", $formName); 
				
		if (!$formAsString) {
			//			$controller->error("In importForm, we could not find the form '$formName' in either '$pathToSiteSpecificFiles$formName' or '$pathToSharedCode$formName'.");
		} else { 			
			// 06-22-07 - the next commands try to import all the functions that the form
			// contains, so we don't get "Fatal Error: Undefined Function".
			$arrayOfNeededFunctions = $controller->command("matchAllPhpFunctionsInString", $formAsString); 
			$arrayOfTrueAndFalseResults = $controller->command("loopArray", $arrayOfNeededFunctions, "getNeededFunctionsEach"); 

			// 06-22-07 - does any bad thing happen if I use eval here instead of include() below? 
			// (must end php or I get errors)
			$phpEnd = "?";
			$phpEnd .= "> ";
			$formAsString = $phpEnd.$formAsString; 
			
			eval($formAsString); 

			return true; 
			
			
			
			
			
			
			
			
			
			
			/*
			
			06-22-07 - I'm going to comment out the rest of this function, and instead of call include()
				below, I'll use eval() above. 
						
			// 03-25-07 - if we run these two lines, then we get something like the following array on screen:
			//
			//		e c h o " here is the array of resuls: ";
			//		print_r($arrayOfTrueAndFalseResults); 
			//
			//			 here is the array of resuls: Array
			//			(
			//			    [0] => 1
			//			    [1] => 1
			//			    [2] => 1
			//			    [3] => 1
			//			)
			//
			// We really don't want to continue if we failed to import one of these functions, we should
			// probably test that every row of the array equals true, and we should not proceed if a row
			// tests false. 
			//
			// However, I'll comment this code out if it interfere's with our ability to write arbitrary 
			// code in the forms. 
			//
			//
			// 03-24-07 - sure enough, this code doesn't allow arbitrary code, of the type we use in
			// artists_inventory_each.htm as part of the loop on this page: 
			//
			// http://www.monkeyclaus.org/media/audio/store.php?artist=Abel%20Okugawa
			//
	//		$isSafeToProceed = true; 
	//		for ($i=0; $i < count($arrayOfTrueAndFalseResults); $i++) {
	//			$isImported = $arrayOfTrueAndFalseResults[$i];
	//			if (!$isImported) {
	//				$isSafeToProceed = false; 
	//				$controller->error("In importForm, we failed to import one of the functions in the form '$formName'. "); 
	//			}
	//		}
			
	//		if ($isSafeToProceed) {	
	
	
				// 05-14-07 - I'm now moving this next if() block up to here, above the other
				// checks for the file. I want the software to look in the scaffolding folder
				// first, to aid experimentation when programmers are first building a site. 
				// I'm also adding the function deleteFileFromScaffolding, to be called from
				// deleteFile.htm, so as to make it easy for programmers to delete files from
				// scaffolding and thus allow files in site_specific_files to be found. 
				//
				// 03-25-07 - it's been somewhat frustrating to use the scaffolding and not be
				// able to immediately see the forms we are creating inside of some existing, 
				// nice-to-look-at template. So I'm adding in this next if() clause. Since 
				// there is no specified path for the scaffolding, we will use the one
				// for site_specific_files, remove the site_specific_files directory, put the 
				// scaffolding directory onto the path, and then try it, and see if it works. 
				//
				// 06-22-07 - nevermind, I've introduced another global variable into the 
				// config file - pathToScaffolding
				//$pathToScaffolding = str_replace("site_specific_files", "scaffolding", $pathToSiteSpecificFiles); 
				
				

				$included = @ include($pathToScaffolding.$formName); 
				
				// 09-20-06 - imitating the CMS that Darren Hoyt and I worked on, I
				// want there to be a folder where the graphic designer is free to override
				// my work on forms. So we check that folder first, and only if we
				// don't find the form we check the main folder. I'm also writing code, 
				// sort of like Ruby on Rails, that will automate the production of forms
				// and that code will write to the "designer_forms" folder, where forms 
				// will now go by default. 
				//
				// 11-30-06 - we could just hit $formAsString with eval(), instead of using
				// the include() commands that we use down here, but using include protects
				// us from using eval() on a string that has PHP parse errors. 
				if (!$included) $included = @ include($formName); 
				if (!$included) $included = @ include($pathToSiteSpecificFiles.$formName); 
				if (!$included) $included = @ include($pathToSharedCode.$formName); 
				
				if (!$included) {
		//			$controller->error("In importForm, we were not able to import the form '$formName'. If you're sure there is a form by that name, then check it for PHP parse errors."); 	
				}
			//}
			*/
		}
	} else {
		$controller->error("In importForm, we expected to be told the name of a form to import, but we got nothing.");
	}
}



?>