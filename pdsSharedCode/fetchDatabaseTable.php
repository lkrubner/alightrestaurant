<?php



function fetchDatabaseTable($databaseTable, $fieldsToGet=array()) {
  // 2013-11-26 - right now there is too much SQL all over the code. I'm adding this to 
  // deal with the simple case where I need to grab some fields out of a database table. 

  global $controller; 


  if (count($fieldsToGet) > 0) {
    $stringOfFieldsToGet = '';
    foreach ($fieldsToGet as $field) {
      $stringOfFieldsToGet .= $field . ', ';
    }
    $stringOfFieldsToGet = substr($stringOfFieldsToGet, 0, -2); 
  } else {
    $stringOfFieldsToGet = ' * ';
  } 

  $query = "SELECT $stringOfFieldsToGet FROM $databaseTable";
  $result = $controller->command("makeQuery", $query, "fetchDatabaseTable"); 

  $arrayOfResults = array(); 

  if ($result) {
    while ($row =  $controller->command("row", $result, "fetchDatabaseTable")) {
      $arrayOfResults[] = $row; 
    }
  }

  return $arrayOfResults; 
}



