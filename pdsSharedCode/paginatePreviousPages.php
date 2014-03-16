<?php



function paginatePreviousPages($link=false) {
	// 11-18-06 - used in paginate() to make the links before the current one

	global $controller; 

	$dbPage = $controller->getVar("slice");	 
	if ($dbPage > 0 && is_numeric($dbPage)) {
		for ($i=0; $i < $dbPage; $i++) {
			// 01-24-06 - this next block puts a limit on how many pages will display
			if ($i < 1) {
				if ($dbPage > 8) {
					$i = $dbPage - 5;
				}
			}
			$previousLink = $link."&dbPage=$i";
			$r = $i + 1;
			$controller->addToOutput("<a href=\"$previousLink\">$r</a> | "); 
		}
	}
}



?>