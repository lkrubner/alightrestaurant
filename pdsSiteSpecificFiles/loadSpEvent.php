<?php




function loadSpEvent($id) {
  global $controller; 
		
  $sql = "
			SELECT s.*, e.* 
			FROM sp_events s
			JOIN night_events e
			ON s.sp_event_id = e.event_id
			WHERE e.event_id=".$db->real_escape_string($id)."
			AND event_type_id=".EVENT_SPECIAL;

  $result = $controller->command("makeQuery", $sql, "loadSpEvent"); 
  $row = mysql_fetch_object($result); 

  $spEventObject = $controller->getObject("SpEvent", "loadSpEvent", "makeNewObject");
  $spEventObject->setAttributes($id, new DateTime($row->night), $row->start, $row->end, $row->sp_data); 

  return $spEventObject;
}