<?php



function loadMemberDinner($id) {
  global $controller;
		
  $sql = "
			SELECT d.*, e.* 
			FROM mbr_dinners d
			JOIN night_events e
			ON d.mbr_dinner_id = e.event_id
			WHERE e.event_id=".addslashes($id)."
			AND event_type_id=".EVENT_MEMBER_DINNER;
  $results = $controller->command("makeQuery", $sql, "loadMemberDinner"); 
  $row = mysql_fetch_object($results); 

  $max_guests = array(
		      'total'=>$row->max_total,
		      'hour'=>$row->max_hour,
		      'halfhour'=>$row->max_halfhour,
		      );
  $mealObject = $controller->command("loadMeal", $row->meal_id);

  $memberDinnerObject = $controller->getObject("MemberDinner", "loadMemberDinner", "makeNewObject"); 
  $memberDinnerObject->setAttributes($id, new DateTime($row->night), $row->start, $row->end, $max_guests, $mealObject);
  return $memberDinnerObject;
}


