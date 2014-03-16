<?php



function databaseFetchSql($query=false, $callingCode=false) {
  // 2013-12-02 - use this function when you want to run arbitrary SQL but you still
  // want the code to run security checks on what comes back. In this case, we 
  // return the data if the currently logged in user is an admin, or if the user_id
  // of the returned records matches the id of the currently logged in user, or if
  // the row has a field called "is_public" which tests true. 

  global $controller; 

  if (!$query) {
    $controller->error("In databaseFetchSql, we needed to be told what query to run, which should have been the first parameter"); 
    return false; 
  }

  if (!$callingCode) {
    $controller->error("In databaseFetchSql, we needed to be told which code called databaseFetchSql, and callingCode should have been the second parameter"); 
    return false; 
  }

  $query = trim($query); 
  if (stristr($query, "delete") || stristr($query, "update") || stristr($query, "insert")) {
    $controller->error("In databaseFetchSql, we detected either 'delete' or 'update' or 'insert' in the SQL, but databaseFetchSql is only meant to be used for SELECT statements. This is the query we were given: '$query'."); 
    return false; 
  }

  $arrayOfAllowedRows = array(); 
  $userInfoArray = $controller->command("getUserInfo"); 
  $securityLevel = $userInfoArray["security_level"]; 
  $userId = $userInfoArray["user_id"];
  $result = $controller->command("makeQuery", $query, "databaseFetchSql -- called from $callingCode"); 
  
  while ($row = $controller->command("row", $result, "databaseFetchSql")) {
    if ($securityLevel == 'admin') {
      $arrayOfAllowedRows[] = $row;
    } else if (isset($row['user_id']) && $row['user_id'] == $userId) {
      $arrayOfAllowedRows[] = $row; 
    } else if (isset($row['is_public']) && $row['is_public']) {
      $arrayOfAllowedRows[] = $row; 
    } else {
      $controller->addToResults("You tried to access some information that you do not have permission to access."); 
      $controller->error("The user with id of $userId tried to run the query '$query', but they lacked permission to see all of the records returned."); 
    }
  }

  return $arrayOfAllowedRows;
}




