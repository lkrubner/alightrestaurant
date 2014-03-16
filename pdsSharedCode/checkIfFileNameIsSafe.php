<?php



function checkIfFileNameIsSafe($fileName=false) {
	// 07-16-06 - we need to keep hackers from uploading files with PHP, or if they do, we need to keep those files
	// from being sent to the PHP parser. An easy way to stop obvious attacks is simply to change the file extension.
	// What follows are all the file endings that we think are safe. If the uploaded
	// file does not have an ending that we think is safe, then we change the file name at the end.
	$fileSafe = false;
	$ext = substr($fileName, -4);
	
	// 06-15-06 - what follows is the word file types
	if (stristr($ext, ".doc")) $fileSafe = true;
	if (stristr($ext, ".pdf")) $fileSafe = true;
	if (stristr($ext, ".wdp")) $fileSafe = true;
	if (stristr($ext, ".wdf")) $fileSafe = true;
	if (stristr($ext, ".swx")) $fileSafe = true;
	if (stristr($ext, ".xml")) $fileSafe = true;
	if (stristr($ext, ".rss")) $fileSafe = true;
	if (stristr($ext, ".htm")) $fileSafe = true;
	if (stristr($ext, ".wmv")) $fileSafe = true;
	if (stristr($ext, ".wma")) $fileSafe = true;
	if (stristr($ext, ".mp4")) $fileSafe = true;

	// 06-15-05 - what follows is 3 letter image types
	if (stristr($ext, ".jpg")) $fileSafe = true;
	if (stristr($ext, ".jpe")) $fileSafe = true;
	if (stristr($ext, ".gif")) $fileSafe = true;
	if (stristr($ext, ".png")) $fileSafe = true;
	if (stristr($ext, ".psd")) $fileSafe = true;
	if (stristr($ext, ".avi")) $fileSafe = true;
	if (stristr($ext, ".mp3")) $fileSafe = true;
	if (stristr($ext, ".mov")) $fileSafe = true;
	if (stristr($ext, ".bmp")) $fileSafe = true;
	if (stristr($ext, ".tif")) $fileSafe = true;
	if (stristr($ext, ".aif")) $fileSafe = true;
	if (stristr($ext, ".rtf")) $fileSafe = true;

	$ext = substr($fileName, -5);		
	if (stristr($ext, ".html")) $fileSafe = true;
	if (stristr($ext, ".aiff")) $fileSafe = true;
	if (stristr($ext, ".jpeg")) $fileSafe = true;
	if (stristr($ext, ".text")) $fileSafe = true;
	if (stristr($ext, ".tiff")) $fileSafe = true;
	if (stristr($ext, ".real")) $fileSafe = true;

	if (!$fileSafe) {
		$fileName = str_replace(".", "_", $fileName); 
		$fileName = $fileName.".txt";
		
		global $controller;
		$controller->addToResults("We're sorry, but files that end with '$ext' are not allowed to be uploaded."); 
	}
	
	return $fileName; 
}



?>