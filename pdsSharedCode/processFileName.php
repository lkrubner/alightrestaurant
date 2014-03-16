<?php



function processFileName($fileName=false) {
	// 07-14-06 - this function is called in copyFileToServer(). We are checking for disallowed 
	// characters in the file name, just in case the user has an operating system that allows
	// characters that our web server will not know how to deal with. 
	
	global $controller; 
	
	if ($fileName) {
		$fileName = trim($fileName);
		$fileName = str_replace(" ", "_", $fileName);
		$fileName = str_replace(",", "_", $fileName);
	//	$fileName = str_replace(".", "_", $fileName); messes up file extensions
		$fileName = str_replace("'", "_", $fileName);
		$fileName = str_replace("\"", "_", $fileName);
		$fileName = str_replace("=", "_", $fileName);
		$fileName = str_replace("\\", "_", $fileName);
		$fileName = str_replace("|", "_", $fileName);
		$fileName = str_replace("+", "_", $fileName);
		$fileName = str_replace("/", "_", $fileName);
		$fileName = str_replace("`", "_", $fileName);
		$fileName = str_replace("@", "_", $fileName);
		$fileName = str_replace("#", "_", $fileName);
		$fileName = str_replace("$", "_", $fileName);
		$fileName = str_replace("%", "_", $fileName);
		$fileName = str_replace("^", "_", $fileName);
		$fileName = str_replace("&", "_", $fileName);
		$fileName = str_replace("*", "_", $fileName);
		$fileName = str_replace("(", "_", $fileName);
		$fileName = str_replace(")", "_", $fileName);
		$fileName = str_replace("{", "_", $fileName);
		$fileName = str_replace("}", "_", $fileName);
		$fileName = str_replace("[", "_", $fileName);
		$fileName = str_replace("]", "_", $fileName);
		$fileName = str_replace("<", "_", $fileName);
		$fileName = str_replace(">", "_", $fileName);
		$fileName = str_replace("\n", "", $fileName);
		$fileName = str_replace("\r", "", $fileName);
		$fileName = str_replace("?", "_", $fileName);
		$fileName = str_replace(";", "_", $fileName);
		// $fileName = str_replace("-", "_", $fileName);
		$fileName = htmlspecialchars($fileName); 
		
		return $fileName;
	} else {
		$controller->error("In processFileName we expected to be given a file name, but we were not.", "processFileName"); 		
	}
}



?>