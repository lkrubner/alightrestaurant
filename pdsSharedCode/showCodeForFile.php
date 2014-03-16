<?php



function showCodeForFile($whichFile=false) {
	// 09-21-06 - I'm using this function in the test pages. Mostly, I have this
	// on each page and it looks for the actual file whose code is being tested, and
	// it gets that code and shows it on screen. The idea here, of course, is to 
	// document the code as completely as possible, and to make it easier for 
	// another programmer to pick it up. So along with testing the code, we also
	// want to show it. For an example of this code in use, look at test_copyFileToServer.php.
	// On that page this function is being invoked, at which point the code gets the
	// name of the page, strips off the "test_" and then gets the actual code as a
	// string. 
	//
	// 04-17-07 - Ignore the comment above. I've redone things quite a bit. I'm
	// now using this function as part of the documentation. On any full install
	// of this framework they'll be a documentation folder. For instance, right
	// now, there is such a folder on iHanuman: 
	//
	//	http://www.ihanuman.com/documentation/index.php
	//
	// That page lists every every function in the code. It also offers a link
	// that lets you go to another page (code.php) where you can see the actual
	// code that makes up a function. For instance:
	//
	// http://www.ihanuman.com/documentation/code.php?fileName=showCodeForFile.php
	// 
	// This function is in use on code.php. It looks to the URL to find a 
	// variable called fileName. It then looks in the directory sharedCode
	// for a file with the same name. If it finds such a file, it opens it
	// renders it viewable on the web with htmlspecialchars, and displays it.
	// The goal is to make it easy for programmers to learn the framework. 

	global $controller; 
	
	$pathToSharedCode = $controller->getVar("pathToSharedCode"); 
		
	if (!$whichFile) $whichFile = $_GET["fileName"];
	if (!$whichFile) {	
		$self = $_SERVER["PHP_SELF"];
		$whichFile = basename($self); 
	}
	
	$whichFile = str_replace("test_", "", $whichFile); 
	$codeForThisFunctionAsString = @ file_get_contents($pathToSharedCode.$whichFile);

	if ($codeForThisFunctionAsString != "") {
		$codeForThisFunctionAsString = htmlspecialchars($codeForThisFunctionAsString);
		
		// 09-21-06 - we need some formatting for this to be readable, newline breaks 
		// and tabbing, especially.
		$codeForThisFunctionAsString = nl2br($codeForThisFunctionAsString);
		$codeForThisFunctionAsString = str_replace("\t", "&nbsp;&nbsp;&nbsp;\t", $codeForThisFunctionAsString);
		
		$controller->addToOutput($codeForThisFunctionAsString);
	} else {
		$controller->addToOutput("Sorry, we could not find the file '$whichFile', or if we found it, it was empty."); 
	}
}



?>