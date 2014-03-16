<?php



function processInputAddNewDateIfUserHasPickedANewDate($totalFormInputs) {
  // 2013-12-07 - this is being called from a hidden input on this page: 
  //
  // http://dev4.krubner.com/admin.php?page=admin_edit_occurrence&occurrence_type=preview_dinner&currentEditingId=41
  //
  // this is then caught and processed in createRecordsForMultipleDatabaseTables() like this: 
  //
  //  	$processInputWithTheseFunctionsArray = $controller->getVar("processInputWithTheseFunctions");
  //  	if ($processInputWithTheseFunctionsArray) {
  //  		if (is_array($processInputWithTheseFunctionsArray)) {
  //  			reset($processInputWithTheseFunctionsArray);	
  //  			while(list($key, $nameOfFunction) = each($processInputWithTheseFunctionsArray)) {
  //  				$totalFormInputs = $controller->command($nameOfFunction, $totalFormInputs); 
  //  			}
  //  		} else {
  //  			$controller->error("In createRecordsForMultipleDatabaseTables, we had an input called 'processInputWithTheseFunctionsArray'. This should be an array, but it was not. Instead we got this: '$processInputWithTheseFunctionsArray'"); 
  //  		}
  //  	}
  //  
  // If there is a value in this date: 
  //
  //	    Change the date? <input type="text" id="new_date_for_occurrence" name="new_date_for_occurrence">
  //
  // then we want to override the date that is here: 
  //
  // <input type="text" name="totalFormInputs[lk_occurrences][< ?php echo currentEditingId(); ? >][start_day]" value="">

  global $controller; 

  $isThereANewDate = $controller->getVar("new_date_for_occurrence"); 

  if ($isThereANewDate) {
    while (list($key, $twoDimensionalArrayWithDatabaseIdAsKey) = each($totalFormInputs)) {
      while (list($rowKey, $row) = each($twoDimensionalArrayWithDatabaseIdAsKey)) {
	if ($key == 'lk_occurrences') {
	  $totalFormInputs[$key][$rowKey]['start_day'] = $isThereANewDate;
	}
      }
    }
  }

  return $totalFormInputs; 
}



