<?php



function renderPartialThisManyTimes($arrangement=false, $thisManyTimes=false, $arrangementIfZero=false) {
	// 06-21-07 - in use on this page: 
	//
	// http://www.ihanuman.com/reviews.php
	//
	// This function is fed a number, like 3 or 5 or 1, which relates to the rating a reader
	// gave an album. We use the number to include an arrangement. The arrangement could have
	// an image, like a star, allowing a star rating system. 
	
	
	global $controller; 
	
	if ($thisManyTimes) {	
		for ($i=0; $i < $thisManyTimes; $i++) {
			$controller->command("renderPartial", $arrangement); 	
		}
	} else {
		if ($arrangementIfZero) $controller->command("renderPartial", $arrangementIfZero); 
	}
}



