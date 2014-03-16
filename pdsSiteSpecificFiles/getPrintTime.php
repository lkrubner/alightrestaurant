<?php



function getPrintTime($time){
  $date = new DateTime("2000-01-01 ".$time);
  if($date->format("i")=="00"){
    return $date->format("gA");
  } else {
    return $date->format("g:iA");
  }
}




