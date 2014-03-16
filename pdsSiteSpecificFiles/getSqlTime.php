<?php



function getSQLTime($minute, $merediem){
  if($merediem == "PM"){
    $parts = explode(":", $minute);
    if($parts[0]!="12"){
      return ($parts[0]+12).":".$parts[1].":00";
    }
  }
  return $minute.":00";
}
