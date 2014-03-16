<?php



function showSourceCodeOfFile() {
	// 11-30-06 - this in use on this page, inside the scaffolding folder: 
	//
	// index.php?formName=editFileSourceCode.htm
	//
	// the url is expected to look like this: 
	//
	// index.php?formName=editFileSourceCode.htm&fileToEdit=$formName
	//
	// We need to find the file that the variable $fileToEdit is naming
	// and we need to open it, get the content as a string, escape the 
	// string for web viewing, and then show it here: 
	//
	// This isn't a perfect solution, but we are simply going to assume that
	// we are in the same folder as the file we are trying to open. 	


	global $controller;

	$fileToEdit = $controller->getVar("fileToEdit");
	$fileAsString = $controller->command("readFileAndReturnString", $fileToEdit); 
	$fileAsString = htmlspecialchars($fileAsString); 
	echo $fileAsString; 
}


?>