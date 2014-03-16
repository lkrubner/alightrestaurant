<?php



function databaseDeleteSql($query=false, $callingCode=false) {
  // 2013-12-06 - this function is called from databaseDeleteAllItemsInDeleteArray().
  // It is for situations where you need to write a complicated bit of SQL does a 
  // delete operation. 

  global $controller; 

  if (!$query) {
    $controller->error("In databaseDeleteSql, we needed to be told what query to run, which should have been the first parameter"); 
    return false; 
  }

  if (!$callingCode) {
    $controller->error("In databaseDeleteSql, we needed to be told which code called databaseDeleteSql, and callingCode should have been the second parameter"); 
    return false; 
  }

  $userInfoArray = $controller->command("getUserInfo"); 
  $securityLevel = $userInfoArray["security_level"]; 

  if ($securityLevel == 'admin') {
    $result = $controller->command("makeQuery", $query, "databaseDeleteSql -- called from $callingCode"); 
  } else {
    $controller->addToResults("You tried to delete something, but you are not an admin, and only admins can delete those items.");
  }
}




