<?php



function getTimeParts($time){
  $date = new DateTime("2000-01-01 ".$time);
  return array($date->format("g:i"), $date->format("A"));
}








