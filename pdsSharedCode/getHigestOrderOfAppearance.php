<?php



function getHigestOrderOfAppearance() {
	// 10-13-07 - this is used here:
	//
	// http://www.teamlalala.com/tsr/admin.php?id=5&formName=list_all_files.htm
	//
	// I'm telling the staff of Second Road that they need to give a video a high
	// number if they want to appear first. I need to tell them the number of the
	// video that is currently appearing first. 
	
	global $controller; 
	
	$query = "SELECT order_of_appearance FROM video ORDER BY order_of_appearance DESC LIMIT 1"; 
	$result = $controller->command("makeQuery", $query, "getHighestOrderOfAppearance"); 
	$row = $controller->command("getRow", $result); 
	$order_of_appearance = $row["order_of_appearance"];
	
	return $order_of_appearance; 
}



?>