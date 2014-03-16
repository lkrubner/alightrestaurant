<?php


function createEvent($id, $type, $night, $start, $end) {
  global $controller;
  $sql = "
			INSERT INTO night_events (
				night,
				event_id,
				event_type_id,
				start,
				end
			) VALUES (
				'".$night->format(P_SQL_DATE)."',
				".$id.",
				".$type.",
				'".$start."',
				'".$end."'
			)";
  $controller->command("makeQuery", $sql, "Event->create"); 
}
