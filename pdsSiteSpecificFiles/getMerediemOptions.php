<?php



function getMerediemOptions($default){
  $choices = array("AM", "PM");
  $opts = "";
  foreach($choices as $choice){
    $selected = "";
    if($default==$choice){
      $selected=" selected='selected'";
    }
    $opts .= "<option".$selected." value='".$choice."'>".$choice."</option>";
  }
  return $opts;
}


