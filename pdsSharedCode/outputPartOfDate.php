<?php



function outputPartOfDate($whichPart="year", $whichDate=false) {
	// 08-17-07 - sometimes on forms we will have dates that are formed
	// by 3 SELECT boxes - day, month, year. If someone is editing a record
	// that already exists, then ideally we should be able to fill
	// in each of the SELECT boxes with the pre-existing values. This
	// function assumes it is being given a date like this: 
	//
	// 2007-04-03
	//
	// That is, year, month, date, the MySql standard. We will parse
	// it as such.

	$year = substr($whichDate, 0, 4); 
	$month = substr($whichDate, 5, 2); 
	$day = substr($whichDate, -2); 

	
	
	if ($whichPart == "year") return $year;
	if ($whichPart == "month") return $month;
	if ($whichPart == "day") return $day;
}



?>