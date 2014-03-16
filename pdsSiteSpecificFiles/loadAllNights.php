<?php



function loadAllNights($start, $end){
  global $controller;

  //For querying and date comparisons
  $startStr = $start->format(P_SQL_DATE);
  $endStr = $end->format(P_SQL_DATE);

  //Get all the events for each night
  $sql = "
			SELECT * 
			FROM night_events
			WHERE night >= '".$startStr."'
			AND night <= '".$endStr."'";
  $results = $controller->command("makeQuery", $sql, "loadAllNights"); 

  $events = array();
  $eventObjects = $controller->getObject("Event", "loadAllNights"); 

  while($row = mysql_fetch_object($results)){
    if(!key_exists($row->night, $events)){
      $events[$row->night] = array();
    }
    $night = new DateTime($row->night);
    $events[$row->night][] = $controller->command("loadEvent", $row->event_id, $row->event_type_id, $night, $row->start, $row->end, true);
  }

  //Build each night object and return as array
  $nights = array();

  $daysInRange = round( ($end->format('U') - $start->format('U')) / (60*60*24)) + 1;

  $curNight = clone $start;
  for($i=0; $i<$daysInRange; $i++){
    $curDateStr = $curNight->format(P_SQL_DATE);
    if(key_exists($curDateStr, $events)){
      $nightObject = $controller->getObject("Night", "loadAllNights", "makeNewObject"); 
      $nightObject->setAttributes(clone $curNight, $events[$curDateStr]); 
      $nights[] = $nightObject;
      } else {
      $nightObject = $controller->getObject("Night", "loadAllNights", "makeNewObject"); 
      $nightObject->setAttributes(clone $curNight, array());

      $nights[] = $nightObject;
    }
    $curNight->modify('+1 day');
  }
  
  return $nights;
}
