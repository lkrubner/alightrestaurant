<?php



function showDocumentation() {
	// 12-05-06 - this is the function we use to show all the documentation the 
	// documentation folder, on the index.php page inside the "documentation"
	// folder. 
	//
	// 06-22-07 - I hope to keep a current copy of documentation here:
	//
	// http://www.krubner.com/documentation/

	global $controller; 
	
	$pathToSharedCode = $controller->getVar("pathToSharedCode"); 
	
	$arrayOfAllFiles = $controller->command("getAnArrayOfAllFilesInADirectory", $pathToSharedCode); 
	sort($arrayOfAllFiles); 
	
	for ($i=0; $i < count($arrayOfAllFiles); $i++) {
		$thisFile = $arrayOfAllFiles[$i];
		// 12-05-06 - we are only concerned about files that end in ".php"
		if (stristr($thisFile, ".php")) {
			$thisFileName = str_replace(".php", "", $thisFile); 
			$controller->addToOutput("<h2  id=\"$thisFileName\"><a style=\"display:block; float:right; width:50px; font-size:12px;\" href=\"code.php?fileName=$thisFile\">(code?)</a><a href=\"index.php#$thisFileName\">$thisFileName</a></h2>");
			$pathToFile = $pathToSharedCode.$thisFile;
			$arrayOfLinesFromFile = file($pathToFile); 
			// 12-05-06 - I format all files so that the primary comment starts on line 6 and goes till
			// the first empty line. And that is what we ouput. 
			$controller->addToOutput("<p>");
			for ($r=4; $r < count($arrayOfLinesFromFile); $r++) {
				$thisLine = $arrayOfLinesFromFile[$r];
				if (strlen($thisLine) > 3) {
					$startTag = "<";
					$startTag .= "?php";
					$endTag = "?";
					$endTag .= ">";
					$thisLine = str_replace("< ?php", $startTag, $thisLine); 
					$thisLine = str_replace("? >", $endTag, $thisLine); 
					$thisLine = htmlspecialchars($thisLine); 
					$thisLine = nl2br($thisLine); 
					// 04-17-07 - why not show the function signature?
					// Wouldn't that be useful? I always put it on the 
					// 5th line of each file. 
					if ($r == 4) {
						if (stristr($thisLine, "function")) $thisLine = substr($thisLine, 9);
						$thisLine = str_replace("{", "", $thisLine);
						$controller->addToOutput("<p style=\"line-height:20px;\"><strong>Signature:</strong> <em>$thisLine</em></p>"); 
					} else {
						if (stristr($thisLine, "//") || stristr($thisLine, "*")) {
							$controller->addToOutput($thisLine); 
						}
					}
				} else {
					$r = count($arrayOfLinesFromFile);
				}
			}
			$controller->addToOutput("</p>");
		}
	}
}



?>