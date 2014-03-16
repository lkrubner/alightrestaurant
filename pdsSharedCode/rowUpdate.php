<?php



function rowUpdate($whichDatabaseTable, $formInputs) {
  // 2013-11-30 - This old comment was originally written for updateRecord(), back when updateRecord()
  // existed and was called from processInput(): 
  // 
  // 09-19-06 - we use this function for all updates. We need to be 
  // given 3 pieces of information from the form that is submitting to
  // this action:
  // 
  // id - we need to know the id of the record we are trying to update. 
  //		This info will normally be stored in a hidden input on the form
  //		that is submitting to this page.
  //
  // whichDatabaseTable - we need to know the name of the table we are updating. 
  //		Again, we assume this will be hidden in a hidden input on the form that
  //		is submitting. 
  //
  // formInputs - all the data to be put into the database as an update should be
  //		in the array formInputs, and the keys of formInputs should match the names
  // 		of the columns that are updating. The command getDatabaseColumnsAndValuesFromFormInput
  // 		will use formInputs to create the string of column names and values that will update
  // 		the database. A typical input from the form formEditConcierge.php looks like this (without
  // 		space between PHP markers):
  //
  // 		<p>First name: <input type="text" name="formInputs[first_name]" value="< ?php currentValue("first_name"); ? >" /></p>
  //
  //
  // 06-27-08 - this is pretty damn obvious, but this function is based on updateRecord.
  // The differences are minor - this function will only get the id it is suppose to update
  // by looking in the formInputs that it is being handed. This function is in use in
  // processInput - probably not anywhere else. 
	
  global $controller;

	
  if (!$whichDatabaseTable) {
    $controller->addToResults("There was an error. We were unable to save your data: " . print_r($formInputs, true)); 
    $controller->error("In rowUpdate(), we expected to be told what the database table name was, but we got nothing."); 
    return false; 
  }

  if (!$formInputs || !is_array($formInputs)) {
    $controller->addToResults("There was an error. We were unable to save your data: " . print_r($formInputs, true)); 
    $controller->error("In rowUpdate(), we expected to be given an array for formInputs, but we only got ."  . print_r($formInputs, true));
    return false; 
  }
						
  $allMandatoryFieldsHaveBeenFilled = $controller->command("checkToSeeIfMandatoryFieldsAreFilled", $formInputs, $whichDatabaseTable); 

  if ($allMandatoryFieldsHaveBeenFilled) {	
    // 11-17-06 - okay, I'm making a huge change today. From now on, there won't be seperate 
    // update and create forms. Intead, they'll just be one, and it'll call this function. 
    // When no id is supplied, we will assume we are creating a new record, but when there 
    // is an id, then we will update that record. 

    if (is_numeric($formInputs["id"]) && $formInputs["id"] > 0) {
      // 09-03-07 - introducing the new variable 'editId' because on the Second Road site 
      // I'm getting conflicts between the multiple meanings of "id". 
      $id = $formInputs["id"]; 
      $formInputs["updated_at"] = date('Y-m-d H:i:s'); 
      $databaseColumnsAndValuesAsString = $controller->command("getDatabaseColumnsAndValuesFromFormInput", $formInputs);
      if (!$thisPrimarykey) $thisPrimarykey =  $controller->arrayOfAllCarriedInfo['arrayOfPrimaryKeys'][$whichDatabaseTable];      
      if (!$thisPrimarykey) $thisPrimarykey =  'id'; 
      $query = "UPDATE $whichDatabaseTable SET $databaseColumnsAndValuesAsString WHERE $thisPrimarykey = $id";
      $controller->command("makeQuery", $query, "update"); 

      // 2013-11-26 - in createRecordsForMultipleDatabaseTables() we might be updating many records for
      // many different database tables. There may be additional processing that needs to happen after
      // these records have been updated, so we must record all of the ids that have been updated. 
      $arrayOfIdsOfRecordsThatWereJustUpdated = $controller->arrayOfAllCarriedInfo["arrayOfIdsOfRecordsThatWereJustUpdated"];
      $arrayOfIdsOfRecordsThatWereJustUpdated[$whichDatabaseTable][$id] = $id;							
      $controller->arrayOfAllCarriedInfo["arrayOfIdsOfRecordsThatWereJustUpdated"] = $arrayOfIdsOfRecordsThatWereJustUpdated;
    } else {
      unset($formInputs["id"]);			
      $controller->command("rowCreate", $whichDatabaseTable, $formInputs);
    }
  }
}



