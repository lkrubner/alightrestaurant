<?php



function currentValueForLists($key=false, $format=false, $return=false, $noError=false, $keyForStorageInSingleton=false) {
	// 06-22-07 - a pretty common source of error is mistyping currentValueFromLists().
	// I called the same function currentValueForLists() during 2003-2006, when I built
	// my CMS. So I'm creating this alias.
		
	global $controller;

	$controller->command("currentValueFromLists", $key, $format, $return, $noError, $keyForStorageInSingleton);
}



?>