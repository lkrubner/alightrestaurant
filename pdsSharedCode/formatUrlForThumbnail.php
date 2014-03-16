<?php



function formatUrlForThumbnail($fileName=false) {
	// 09-21-06 - called on formEditConcierge.php. Used as a parameter to currentValueFromLists
	// to format the returned value
	
	$fileName = str_replace(".", "_thumb.", $fileName); 
	return $fileName; 
}



?>