<?php



function processTimestamp($fieldName=false) {
	// 01-17-07 - on CACVB I'm seeing a MySql type called timestamp that looks like this:
	//
	// 20040628105423
	//
	// that's year, month, day, hour, minute, second. 

	global $controller; 

	$timestampMysqlFormat = $controller->command("currentValueFromLists", $fieldName, "", "return"); 

	if ($timestampMysqlFormat) {
		$year = substr($timestampMysqlFormat, 0, 4); 
		$month = substr($timestampMysqlFormat, 4, 2); 
		$day =  substr($timestampMysqlFormat, 6, 2); 
		$hour = substr($timestampMysqlFormat, 8, 2); 
		$minute = substr($timestampMysqlFormat, 10, 2); 
		$second =  substr($timestampMysqlFormat, 12, 2);

		$unix_timestamp = mktime($hour, $minute, $second, $month, $day, $year);
		$formattedTime = date("n-j-Y g:i A", $unix_timestamp); 
		echo $formattedTime; 
	}
}



?>