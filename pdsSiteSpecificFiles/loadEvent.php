<?php



function loadEvent($id, $type, $night, $start, $end){
  global $controller; 

  if($type == EVENT_MEMBER_DINNER){
    $memberDinnerObject = $controller->getObject("MemberDinner", "loadEvent");  
    $memberDinnerObject->setAttributes($id, $night, $start, $end);	
    return $memberDinnerObject;
  } else if($type == EVENT_SPECIAL){
    $spEventObject = $controller->getObject("SpEvent", "loadEvent");
    return $spEventObject->setAttributes($id, $night, $start, $end);	
  }

}
