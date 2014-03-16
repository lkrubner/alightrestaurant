<?php



function totalFormInputsFindRows($databaseTable=false, $fieldToMatchAgainst=false, $idOfRow=false) {
  // 2013-12-02 - consider a page like this:
  //
  // http://dev4.krubner.com/admin.php?page=admin_edit_occurrence&occurrence_type=preview_dinner&currentEditingId=41
  //
  // Suppose we want to find the name of a member who has made a reservation. We need to look in totalFormInputs,
  // inside the rows for the database table for users, and find a row where the user_id matches the user_id
  // of the reservation -- then we can get the first_name and last_name for that user. 
  //
  // on that same page, a call like this: 
  //
  // $controller->command(" totalFormInputsFindRows", "lk_guests", "lk_reservations_id", $reservationId);
  //
  // should get back all of the lk_guests records that are linked to this $reservationId.

  global $controller; 

  if (!$databaseTable) {
    $controller->error("In totalFormInputsFindRow() the first parameter should have been the name of the database that we wanted to look up but we got nothing."); 
    return false; 
  }

 if (!$fieldToMatchAgainst) {
    $controller->error("In totalFormInputsFindRow() the second parameter should have been the name of the field to match against that we wanted to look up but we got nothing."); 
    return false; 
  }

 if (!$idOfRow) {
    $controller->error("In totalFormInputsFindRow() the third parameter should have been the id of the row  we wanted to look up but we got nothing."); 
    return false; 
  }

  $matchingRows = array(); 
  $arrayOfRowsForThisDatabaseTable = $controller->arrayOfAllCarriedInfo["totalFormInputs"][$databaseTable];

 
  if (is_array($arrayOfRowsForThisDatabaseTable)) {
    foreach ($arrayOfRowsForThisDatabaseTable as $row) {
      if ($row[$fieldToMatchAgainst] == $idOfRow) {
	$matchingRows[] = $row;
      }
    }
  }

  return $matchingRows; 
}