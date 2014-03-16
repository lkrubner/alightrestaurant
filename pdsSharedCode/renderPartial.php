<?php



function renderPartial($fileToInclude=false) {
	// 11-30-06 - the name of this is meant to sound familiar to the Ruby On Rails crowd. 
	// This is a conditional include statement. 
	
	global $controller; 

	if (substr($fileToInclude, -4) != ".htm") {
	  $fileToInclude = $fileToInclude . ".htm"; 
	}

	$controller->command("importForm", $fileToInclude);
}



