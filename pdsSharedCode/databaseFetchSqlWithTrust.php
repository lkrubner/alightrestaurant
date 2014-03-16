<?php



function databaseFetchSqlWithTrust($query=false, $callingCode=false) {
  // 2013-12-02 - avoid this function whenever you can. Most of the time you should use
  // databaseFetchSql() which has some security checks and is therefore safer.

  global $controller; 

  if (!$query) {
    $controller->error("In databaseFetchSqlWithTrust, we needed to be told what query to run, which should have been the first parameter"); 
    return false; 
  }

  if (!$callingCode) {
    $controller->error("In databaseFetchSql, we needed to be told which code called databaseFetchSql, and callingCode should have been the second parameter"); 
    return false; 
  }

  $query = trim($query); 
  if (stristr($query, "delete") || stristr($query, "update") || stristr($query, "insert")) {
    $controller->error("In databaseFetchSqlWithTrust, we detected either 'delete' or 'update' or 'insert' in the SQL, but databaseFetchSqlWithTrust is only meant to be used for SELECT statements. This is the query we were given: '$query'."); 
    return false; 
  }

  $arrayOfAllowedRows = array(); 
  $result = $controller->command("makeQuery", $query, "databaseFetchSqlWithTrust, which is called by $callingCode"); 

  while ($row = $controller->command("row", $result, "databaseFetchSqlWithTrust")) {
    $arrayOfAllowedRows[] = $row; 
  }

  return $arrayOfAllowedRows;
}




