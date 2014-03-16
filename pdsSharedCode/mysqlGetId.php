<?php



function mysqlGetId($callingCode=false) {
  // 07-16-06 - I don't want to have to write the following error checking every time I make a 
  // query against the dataase, so I'll just write this one function and then use it for every
  // query. 
	
  global $controller;

  if ($callingCode) {
    $databaseObject = & $controller->getObject("Database", "mysqlGetId"); 
    if (is_object($databaseObject)) {
      $newId =  $databaseObject->mysql_get_id(); 
    } else {
      $controller->error("In mysqlGetId(), we tried to get an instance of the class Database, but the Controller failed to return an object to us."); 
    }
  } else {
    $controller->error("In mysqlGetId(), we expected the first parameter to be the name of the function or class that is calling mysqlGetId(), but we got nothing.");
  }
}



