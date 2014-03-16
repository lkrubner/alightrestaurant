<?php



function paginateNextPages($link=false, $top=false) {
	// 11-17-06 - used in paginate() to make the links after the current one

	global $controller;

	$dbPage = $controller->getVar("slice");	
	if (!$dbPage) $dbPage = 0;
	$nextPage = $dbPage + 1; 
	if ($top > 0 && is_numeric($top)) {
		for ($i=$nextPage; $i <= $top; $i++) {
			$nextLink = $link."&dbPage=$i";
			$r = $i + 1;			
			$controller->addToOutput(" | <a href=\"$nextLink\">$r</a>");
			// 01-24-06 - this next block puts a limit on how many pages will display
			if ($i > $nextPage + 4) {
				if ($top > 15) {
					$i = $top; 
				}
			}
		}
	}
}



?>