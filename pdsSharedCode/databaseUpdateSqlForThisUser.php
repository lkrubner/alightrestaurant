<?php



function databaseUpdateSqlForThisUser($query=false, $callingCode=false) {
  // 2014-01-13 - this is a safe way to allow random users to engage the site, and trigger
  // updates in the database, because we add this: 
  //
  // AND user_id = '$loggedInId'
  //
  // to every query, which should limit how much damage a user could do, even
  // if they tried to run arbitrary SQL. 
  //
  // We also check the query: it must start with "update".

  global $controller; 

  $query = trim($query); 
  $query = strtolower($query); 

  if (!$query) {
    $controller->error("In databaseUpdateSqlForThisUser, we needed to be told what query to run, which should have been the first parameter"); 
    return false; 
  }

  if (!$callingCode) {
    $controller->error("In databaseUpdateSqlForThisUser, we needed to be told which code called databaseUpdateSqlForThisUser, and callingCode should have been the second parameter"); 
    return false; 
  }

  if (substr($query, 0, 6) != 'update') {
    $controller->error("In databaseUpdateSqlForThisUser, we need for this query to start with the word 'update', but instead we got this: $query "); 
    return false; 
  }

  $loggedInId = $controller->command("getUserIdFromUserLoggedInTable");	  
  if(stristr($query, "where")) {
    $query = $query . " AND user_id = '$loggedInId' "; 
  } else {
    $query = $query . " WHERE user_id = '$loggedInId' "; 
  }


  $result = $controller->command("makeQuery", $query, "databaseUpdateSqlForThisUser -- called from $callingCode"); 
}







