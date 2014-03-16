<?php



function showFormsForDevelopment($defaultForm=false) {
	// 11-03-06 - scaffolding should be like its own bit of software, so we are going to 
	// store the form
	
	global $controller; 
	
	$pathToSiteSpecificFiles = $controller->getVar("pathToSiteSpecificFiles"); 
	$pathToSharedCode = $controller->getVar("pathToSharedCode"); 	

	// 09-22-06 - If a form is specified in the URL, it takes precedence over 
	// the form specified as the parameter to this function.			
	if (array_key_exists("formName", $_GET)) {
		$formName = $_GET["formName"];
	}
	if ($formName == "") $formName = $defaultForm; 
		
	if ($formName != "") {
		// 11-03-06 - I think I want the forms to all have HTML endings, rather than 
		// PHP. So I'm changing these next to lines to "htm". I think having it as
		// HTML makes it slightly easier for designers to look at these pages in 
		// Dreamweaver. 
		$formName = str_replace(".htm", "", $formName); 
		$formName .= ".htm";

		// 11-03-06 - I've changed my mind about enforced naming conventions like this one. I
		// like the consistency they offer, but that is offset by the confusiion they cause 
		// the designers. This won't be backwards compatible with mjhforwomen but I'm ending 
		// this bit of naming enforcement. 
		//		$formName = ucfirst($formName); 
		//		$formName = "form".$formName; 
		
		$controller->command("importForm", $formName); 
	}
}



?>