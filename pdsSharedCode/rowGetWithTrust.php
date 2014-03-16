<?php



function rowGetWithTrust($databaseTable=false, $id=false) {
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

  $fieldToMatchAgainst =  $controller->arrayOfAllCarriedInfo['arrayOfPrimaryKeys'][$databaseTable];      
  if (!$fieldToMatchAgainst) $fieldToMatchAgainst = 'id'; 

  $query = "SELECT * FROM $databaseTable WHERE $fieldToMatchAgainst=$id "; 	
  $result = $controller->command("makeQuery", $query, "rowGetWithTrust"); 
  $row = $controller->command("row", $result, "rowGetWithTrust"); 

  return $row; 
}




