<?php



function updateFile() {
	// 12-04-06 - this being called inside the scaffolding folder, from the form on this page:
	//
	// index.php?formName=editFileSourceCode.htm
	//
	// That form gives designers the chance to edit the source code of the files they just generated.
	// I'm imaging the ability to do such quick edits might be useful if you want to show a client
	// a mockup while you're in a meeting and still talking to them. 
	//
	// That form submits two variables to this function: 
	//
	// fileToEdit
	// sourceCode
	//
	// We need to capture the sourceCode and then write that to the file named by fileToEdit. 

	global $controller; 

	$fileToEdit = $controller->getVar("fileToEdit"); 
	$sourceCode = $controller->getVar("sourceCode"); 
	$sourceCode = stripslashes($sourceCode);

	if ($fileToEdit) {
		// 12-04-06 - the last parameter to this command is telling it to write the file
		// in the current directory. Again, we are assuming we are in the scaffolding directory,
		// which needs to be writeable. 
		$controller->command("writeFileToDisk", $fileToEdit, $sourceCode, "./");
	} else {
		$controller->error("In updateFile(), we expected that the form just submitted would have a field named 'fileToEdit' which would tells us which file to update, but we found no such information."); 
	}
}



?>