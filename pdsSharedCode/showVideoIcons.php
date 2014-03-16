<?php



function showVideoIcons($oddOrEven="odd", $start="0", $limit="100") {
	// 10-13-07 - this is for the front page of thesecondroad.org. This is what controls
	// which videos show up on a page, and in what order. 
	//
	//
	// @param - oddOrEven - enum ("odd" or "even") - Laura Denyes, the designer, wants
	// the videos to appear in two seperate tracks. The easiest way to achieve this is to
	// sort them by their id, and put all the rows with odd-numbered ids into one track, 
	// and all the rows with even-numbered ids into another track. 
	//
	//
	// 11-29-07 - this is now in use on the front page but also on this inner page: 
	//
	// http://www.cyberbitten.com/videos.php


	
	global $controller; 

	if ($controller->getVar("oddOrEven")) $oddOrEven = $controller->getVar("oddOrEven"); 
	if ($controller->getVar("start")) $start = $controller->getVar("start"); 
	if ($controller->getVar("limit")) $limit = $controller->getVar("limit"); 

	
	
	// 10-14-07 - we always need at least 6 icons for the front page, so we will test
	// and make sure that our combination of "LIMIT $start, $limit" is not giving us less 
	// than 6. 
	$query = "SELECT * FROM video WHERE private != 't' ORDER BY order_of_appearance"; 
	$result = $controller->command("makeQuery", $query, "showVideoIcons"); 
	$howManyTotal = mysql_num_rows($result); 
	
	if ($start + $limit > $howManyTotal) {
		$start = $howManyTotal - $limit; 	
	}
		
	$query = "SELECT * FROM video WHERE private != 't' ORDER BY order_of_appearance LIMIT $start, $limit"; 
	$result = $controller->command("makeQuery", $query, "showVideoIcons"); 
	$controller->command("loopOddOrEven", $result, "renderPartial", "video_icons_for_playing_video.htm", $oddOrEven); 
}



