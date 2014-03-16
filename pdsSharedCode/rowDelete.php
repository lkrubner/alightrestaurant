<?php



function rowDelete($whichDatabaseTable=false, $id=false, $fieldToMatchAgainst=false) {
  // 09-18-06 - most times this is being called from loopArray which is being called from
  // deleteFromDatabase. We are being given the id of a record that needs to be deleted, 
  // and the name of the table from which we should delete the record. In loopArray, we
  // will be building up an array of results, full of either the string "success" or 
  // "failure". That array will be returned to deleteFromDatabase, and will be used to 
  // shape the message given to the user. 
  //
  // 2013-11-30 - because this is called in loopArray, the first parameter must be the id
  // since loopArray will be looping over an array of ids to delete.

  global $controller; 

  $userId = $controller->command("getIdOfLoggedInUser"); 
  if (!$userId) {
    $controller->addToResults("You must log in to delete anything."); 
    return false; 	
  }
	
  if (is_numeric(!$id)) {
    $controller->error("In deleteThisRecord we expected the first parameter to be the id of the record we should delete from the database, but instead we got this: '$id'."); 
  }

  if (!is_string($whichDatabaseTable)) {	
    $controller->error("In deleteThisRecord we expected the second parameter to be the name of the database for us to delete from, but instead we got this: '$whichDatabaseTable'."); 
  }
	
  if (!$fieldToMatchAgainst) $fieldToMatchAgainst =  $controller->arrayOfAllCarriedInfo['arrayOfPrimaryKeys'][$databaseTable];      	
  if (!$fieldToMatchAgainst) $fieldToMatchAgainst = 'id'; 
  $query = "DELETE FROM $whichDatabaseTable WHERE $fieldToMatchAgainst = $id "; 
  $result = $controller->command("makeQuery", $query, "deleteFromDatabase"); 

  // 05-19-08 - I'm changing this line so that it only 
  // returns true when something was really deleted. 
  if (mysql_affected_rows()) {
    return "success"; 	
  } else {
    return "failure"; 
  }
}



