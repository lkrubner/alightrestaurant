<?php



function getDateFromMySqlTimeStamp($mysqlTimestamp=false, $formatedDateString="F jS Y") {
	// 06-21-07 - a MySql timestamp looks like this: 
	//
	//  2007-06-22 02:28:34 
	
	$year = substr($mysqlTimestamp, 0, 4);
	$month = substr($mysqlTimestamp, 5, 2);
	$day = substr($mysqlTimestamp, 8, 2);
	
	$hour = substr($mysqlTimestamp, 11, 2);
	$minute = substr($mysqlTimestamp, 14, 2);
	$second = substr($mysqlTimestamp, 17, 2);
		
	$unixTimestamp = mktime($hour, $minute, $second, $month, $day, $year);
	
	$date = date($formatedDateString, $unixTimestamp);
	echo $date; 
}



?>