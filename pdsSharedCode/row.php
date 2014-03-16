<?php



function row($result=false, $callingCode=false) {
  // 09-18-06 - this function is meant to act as a mask around the built-in PHP
  // function mysqli_fetch_assoc. Masking this function allows for whatever kinds
  // of filtering might be needed that can not be achieved at the moment the SQL
  // query is formed. 
  //
  // @param - result - resource - not optional - this is a resource just returned 
  //		from the last call to mysql_query. 
  //
  //
  // 2013-11-30 - called from rowGet() or rowGetWithTrust(). Security is enforced
  // in rowGet(). 
  //
  // 2014-01-18 - adding $callingCode because it is difficult to track down errors
  // when row is handed a blank result. 

  global $controller; 

  if (!$callingCode) {
    $controller->error("In row(), the second parameter must be the code that is calling row()."); 
    return false; 
  }

  $row = mysqli_fetch_assoc($result);

  if (is_array($row)) {
    reset($row);
    while(list($key, $val) = each($row)) {
      $row[$key] = stripslashes($val); 	
    }
    return $row; 
  } else {
    // 2014-01-22 - 
    // when this is called from databaseFetchSqlWithTrust(), the while() loop calls this a final time, 
    // at which point the result is invalid, so this function returns false, which causes the while()
    // loop to stop in databaseFetchSqlWithTrust(). All of which is expected behavior, and therefore
    // we should not thrown an error here.
    //
    //    $controller->error("In row() we expected to get an array back when we used mysqli_fetch_assoc on '$result' but instead we got this: '" . print_r($row, true) . "'. PLEASE NOTE: this is not always an error. Sometimes this happens simply because the user is trying to access an item that's already been deleted. This particular error, that you're reading now, should never appear on a live site, but it will appear on development sites because sometimes this information is useful to the programmer who is working on this site. Calling code was: $callingCode"); 
  }
}



