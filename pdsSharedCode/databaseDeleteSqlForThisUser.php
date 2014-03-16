<?php



function databaseDeleteSqlForThisUser($query=false, $callingCode=false) {
  // 2014-01-11 - this function is similar to databaseDeleteSql, but that function is for
  // admins and allows the admin to delete anything, whereas this limits things to the current
  // user who is logged in. This is safer in that limits what the current user can 
  // delete.

  global $controller; 

  if (!$query) {
    $controller->error("In databaseDeleteSqlForThisUser, we needed to be told what query to run, which should have been the first parameter"); 
    return false; 
  }

  if (!$callingCode) {
    $controller->error("In databaseDeleteSqlForThisUser, we needed to be told which code called databaseDeleteSqlForThisUser, and callingCode should have been the second parameter"); 
    return false; 
  }

  $loggedInId = $controller->command("getUserIdFromUserLoggedInTable");	  
  $query = $query . " AND user_id = '$loggedInId' "; 

  $result = $controller->command("makeQuery", $query, "databaseDeleteSqlForThisUser -- called from $callingCode"); 
  $controller->addToResults("We have deleted that item."); 
}




