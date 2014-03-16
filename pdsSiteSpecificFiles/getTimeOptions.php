<?php



function getTimeOptions($default){
  $times = array(
		 "12:00", "12:30", "1:00", "1:30", "2:00", "2:30", 
		 "3:00", "3:30", "4:00", "4:30", "5:00", "5:30", 
		 "6:00", "6:30", "7:00", "7:30", "8:00", "8:30", 
		 "9:00", "9:30", "10:00", "10:30", "11:00", "11:30");
  $opts = "";
  foreach($times as $time){
    $selected = "";
    if($default==$time){
      $selected=" selected='selected'";
    }
    $opts .= "<option".$selected." value='".$time."'>".$time."</option>";
  }
  return $opts;
}
