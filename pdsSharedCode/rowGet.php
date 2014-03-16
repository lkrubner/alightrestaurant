<?php



function rowGet($databaseTable=false, $id=false, $fieldToMatchAgainst=false) {
  // 09-18-06 - on many forms we need for the form to autofill with the correct values.
  // For instance, when we are editing a condition, we expect the form to fill out 
  // with the values that were previously filled in for that condition. 
  //
  // 11-06-06 - we will use this on any forms where an entry from a database needs 
  // to be edited. Mostly, this is being called from setEntryForPage(), which get
  // an entry from the database and stuffs into a singleton object that then shares
  // the values with all the PHP commands (mostly currentValue) that fill out the 
  // values for a form. 

  global $controller; 

  if (!$databaseTable) {
    $controller->error("In rowGet, we needed to be told which database table to look to, which should have been the first parameter"); 
    return false; 
  }

  if (!$id) {
    $controller->error("In rowGet, we needed to be given the id of the entry to look up, which should have been the second parameter"); 
    return false; 
  }

  if (!$fieldToMatchAgainst) $fieldToMatchAgainst =  $controller->arrayOfAllCarriedInfo['arrayOfPrimaryKeys'][$databaseTable];      
  $query = "SELECT * FROM $databaseTable WHERE $fieldToMatchAgainst=$id "; 	
  $result = $controller->command("makeQuery", $query, "rowGet"); 
  $row = $controller->command("row", $result, "rowGet"); 

  // 2013-11-30 - when you can trust the user, use rowGetWithTrust, so you can skip this security check
  $userInfoArray = $controller->command("getUserInfo"); 
  $securityLevel = $userInfoArray["security_level"]; 
  $userId = $userInfoArray["user_id"];
  if ($securityLevel != 'admin') {
    if ($row['user_id'] != $userId) {
      $controller->addToResults("You tried to access some information that you do not have permission to access."); 
      $controller->error("The user with id of $userId tried to access the $id record in the $databaseTable database table, but they lacked permission to do so, so we denied them access to the record."); 
      return false; 
    }
  }

  return $row; 
}




