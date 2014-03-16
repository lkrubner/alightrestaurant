<?php



function doesThisFormExist($formName=false) {
	// 03-25-07 - created today for use in createNaviationBarForNewForms()

	global $controller;
	global $pathToSiteSpecificFiles;
	global $pathToSharedCode;
	
	// 03-25-07 - it's been somewhat frustrating to use the scaffolding and not be
	// able to immediately see the forms we are creating inside of some existing, 
	// nice-to-look-at template. So I'm adding in this next if() clause. Since 
	// there is no specified path for the scaffolding, we will use the one
	// for sharedCode, remove the sharedCode directory, put the scaffolding
	// directory onto the path, and then try it, and see if it works. 	
	$pathToScaffolding = str_replace("sharedCode", "scaffolding", $pathToSharedCode); 
				
	$doesFormExist = false; 
	
	$doesFormExist = @ file_exists($formName); 
	if (!$doesFormExist) $doesFormExist = @ file_exists($pathToSiteSpecificFiles.$formName); 
	if (!$doesFormExist) $doesFormExist = @ file_exists($pathToSharedCode.$formName); 
	if (!$doesFormExist) $doesFormExist = @ file_exists($pathToScaffolding.$formName); 
	
	return $doesFormExist; 
}



?>