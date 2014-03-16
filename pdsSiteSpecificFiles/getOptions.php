<?php



function getOptions($options, $default=null){
  foreach($options as $val=>$choice){
    $selected = "";
    if(!is_null($default) && $default==$val){
      $selected=" selected='selected'";
    }
    $opts .= "<option".$selected." value='".$val."'>".$choice."</option>";
  }
  return $opts;
}


